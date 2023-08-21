import numpy as np
import pandas as pd
from binance.client import Client
from datetime import datetime
from sklearn.preprocessing import MinMaxScaler
import joblib

import os
import psutil
import requests

import keras
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Dropout
from tensorflow.keras.layers import LSTM

# global config
pid = psutil.Process(os.getpid())
pid.cpu_affinity([0, 1])


from sqlalchemy import create_engine

engine = create_engine("mysql+pymysql://dioxtfeq_haods:Syhaobn123@localhost/dioxtfeq_db")

if not os.path.exists("models"):
    os.mkdir("models")
if not os.path.exists("scalers"):
    os.mkdir("scalers")


n_months = 3
interval = Client.KLINE_INTERVAL_1HOUR
glob_end_time = int(datetime.now().timestamp() * 1000)
glob_start_time = int(
    (datetime.now() - pd.DateOffset(months=n_months)).timestamp() * 1000
)
# client = Client()
price_info = [
    "openTime",
    "open",
    "high",
    "low",
    "close",
    "volume",
    "closeTime",
    "quoteAssetVolume",
    "numberOfTrader",
    "takerBuyBaseAssetVolume",
    "takerBuyQuoteAssetVolume",
    "ignore",
]

train_cols = [
    "close",
    "high",
    "low",
    "open",
    "volume",
    "quoteAssetVolume",
    "numberOfTrader",
    "takerBuyBaseAssetVolume",
    "takerBuyQuoteAssetVolume",
]

# model variables
time_step = 24


def create_dataset(dataset, time_step=1):
    dataX, dataY = [], []
    for i in range(len(dataset) - time_step - 1):
        a = dataset[i : (i + time_step), :]
        dataX.append(a)
        dataY.append(dataset[i + time_step, 0])
    return np.array(dataX), np.array(dataY)
    
    
def get_realtime_price_jupiter(symbol: str):
    url = "https://price.jup.ag/v4/price"
    if symbol == 'BTC':
        symbol = 'WBTC'
        
    params = {
        "ids": symbol,
        "vsToken": "USDT"
    }

    response = requests.get(url, params=params)
    json_data = response.json()
    
    price = json_data["data"][symbol]["price"]
    return price


def get_price_data(symbol: str) -> pd.DataFrame:
    df = pd.DataFrame(columns=price_info)
    start_time_ms = glob_start_time
    end_time_ms = glob_end_time
    symbol += "USDT"

    print(symbol)

    while start_time_ms < end_time_ms:
        klines = client.get_klines(
            symbol=symbol, interval=interval, startTime=start_time_ms, limit=1000
        )

        for kline in klines:
            df.loc[len(df), :] = kline

        start_time_ms = df.closeTime.values[-1]
        print(start_time_ms, end_time_ms)

    df["date"] = df.openTime.apply(
        lambda x: datetime.fromtimestamp(x / 1000).strftime("%Y-%m-%d %H:%M:%S")
    )

    return df


def inverse_label(pred, scaler):
    original = pred * (scaler.data_max_[0] - scaler.data_min_[0]) + scaler.data_min_[0]
    return original


def run_train(symbol: str, symbol_id: int):
    coin_price = pd.read_sql(f"select * \
                            from candlestick_data \
                            where openTime >= 1684108800000 \
                            and idCoin = {symbol_id} \
                            order by openTime asc", engine)
                            
    scaler = MinMaxScaler()
    dataset = coin_price[train_cols]

    # data preprocessing
    dataset = scaler.fit_transform(dataset)

    # split train test
    training_size = int(len(dataset) * 0.9)
    test_size = len(dataset) - training_size
    train_data, test_data = (
        dataset[0:training_size, :],
        dataset[training_size : len(dataset), :],
    )

    X_train, y_train = create_dataset(train_data, time_step)
    X_test, y_test = create_dataset(test_data, time_step)

    # X_train = X_train.reshape(X_train.shape[0], X_train.shape[1], -1)
    # X_test = X_test.reshape(X_test.shape[0], X_test.shape[1], -1)

    # model build
    model = Sequential()
    model.add(LSTM(10, input_shape=(None, 9), activation="relu"))
    model.add(Dense(1))
    model.compile(loss="mean_squared_error", optimizer="adam")

    # model training
    history = model.fit(
        X_train,
        y_train,
        validation_data=(X_test, y_test),
        epochs=200,
        batch_size=64,
        verbose=1,
    )

    # train test predict
    train_predict = model.predict(X_train)
    test_predict = model.predict(X_test)

    original_ytrain = inverse_label(y_train, scaler)
    original_ytest = inverse_label(y_test, scaler)
    train_predict = inverse_label(train_predict, scaler)
    test_predict = inverse_label(test_predict, scaler)

    # stats
    ytest_diff = np.ediff1d(original_ytest)
    predtest_diff = np.ediff1d(test_predict)

    cnt = 0
    for y_test, pred in zip(ytest_diff, predtest_diff):
        if y_test * pred > 0:
            cnt += 1

    # save model
    model.save(f"models/{symbol}.keras")
    joblib.dump(scaler, f"scalers/{symbol}.scaler")

    del coin_price
    del dataset
    del model

    return history, cnt, len(ytest_diff)


def run_pipeline(symbol: str, symbol_id: int, train_flag: bool):
    history, cnt, total = None, None, None
    if train_flag:
        history, cnt, total = run_train(symbol, symbol_id)

    data_pred = pd.read_sql(f"select * \
                            from candlestick_data \
                            where openTime >= 1684108800000 \
                            and idCoin = {symbol_id} \
                            order by openTime asc", engine)
    data_pred = data_pred[train_cols].tail(24)
    scaler = joblib.load(f"scalers/{symbol}.scaler")
    model = keras.models.load_model(f"models/{symbol}.keras")

    data_pred = scaler.transform(data_pred)
    data_pred = np.expand_dims(data_pred, 0)
    pred = model.predict(data_pred)[0][0]
    pred = inverse_label(pred, scaler)
    last_close = inverse_label(data_pred[0][-1][0], scaler)
    print(pred)
    
    del scaler
    del model
    
    return pred, last_close, cnt, total


def main() -> None:
    symbols = ["BTC", "ETH", "SOL"]
    coin_info = pd.read_sql(f"select * from coin_info where 1", con=engine)
    opos_pred = pd.read_sql(f"select * \
                            from opos_test \
                            where 1", engine)
    df = pd.DataFrame(columns=["symbol", "last_close", "pred_price", "realtime_price", "update_time", "cnt", "total"])
    for symbol in symbols:
        symbol_id = coin_info.query(f"symbol == '{symbol}USDT'").id.values[0]
        now = datetime.now()
        train_flag = False
        
        # train once every day at 7am
        if (now.hour == 7): train_flag = True
        
        pred, last_close, cnt, total = run_pipeline(symbol, symbol_id, train_flag)
        symbol_opos_pred = opos_pred.query(f"symbol == '{symbol}'")
        last_pred = symbol_opos_pred.pred_price.values[0]
        if not train_flag:
            total = symbol_opos_pred.total.values[0] + 1
            cnt = symbol_opos_pred.cnt.values[0]
            if last_pred * pred > 0: cnt += 1
            
        # get realtime price from jupiter
        rt_price = get_realtime_price_jupiter(symbol)
        
        idx = len(df)
        df.loc[idx, "symbol"] = symbol
        df.loc[idx, "pred_price"] = pred
        df.loc[idx, "last_close"] = last_close
        df.loc[idx, "realtime_price"] = rt_price
        df.loc[idx, "cnt"] = cnt
        df.loc[idx, "total"] = total       
        df.loc[idx, "update_time"] = now.strftime("%d/%m/%Y %H:%M:%S")
    
    print(df.head())
    
    # save to db
    df.to_sql("opos_test", index=True, con=engine, if_exists="replace")



if __name__ == "__main__":
    main()
