import requests
import pandas as pd
from datetime import datetime
from sqlalchemy import create_engine


engine = create_engine("mysql+pymysql://dioxtfeq_haods:Syhaobn123@localhost/dioxtfeq_db")
symbols = ["WBTC", "ETH", "SOL", "HXRO"]


def get_realtime_price_jupiter(symbol: str):
    url = "https://price.jup.ag/v4/price"
    params = {
        "ids": symbol,
        "vsToken": "USDT"
    }

    response = requests.get(url, params=params)
    json_data = response.json()
    
    price = json_data["data"][symbol]["price"]
    return price
   
    
df = pd.DataFrame(columns=["symbol", "price", "update_time"])

for symbol in symbols:
    price = get_realtime_price_jupiter(symbol)
    now = datetime.now()
    
    
    idx = len(df)
    df.loc[idx, "symbol"] = symbol
    df.loc[idx, "price"] = price
    df.loc[idx, "update_time"] = now.strftime("%Y-%m-%d %H:%M:%S")

print(df)
df.to_sql("opos_price", index=False, con=engine, if_exists="append")
