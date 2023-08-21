import { useConnection, useWallet } from '@solana/wallet-adapter-react';
import { PublicKey } from '@solana/web3.js';
import { FC, useCallback, useEffect } from 'react';
import { notify } from "../utils/notifications";
import { ParimutuelWeb3, PositionSideEnum, WalletSigner } from '@hxronetwork/parimutuelsdk';
import { PariConfig } from './Config';

const PlacePosition: FC<{pariPubkey: string, side: PositionSideEnum, amount: string}> = (props) => {
    const { connection } = useConnection();
    const { publicKey, signTransaction } = useWallet();
    const wallet = useWallet()

    const { config } = PariConfig;
    const parimutuelWeb3 = new ParimutuelWeb3(config, connection);

    const {pariPubkey, side, amount} = props

    useEffect(() => {
    }, [pariPubkey]);

    const onClick = useCallback(async (amount: string, pariPubkey: string) => {
        if (!publicKey) {
          notify({ type: 'error', message: 'Wallet not connected!' });
          console.error('Send Transaction: Wallet not connected!');
          return;
        }
        let transactionId = '';
        try {
      
          transactionId = await parimutuelWeb3.placePosition(
            wallet as WalletSigner,
            new PublicKey(pariPubkey),
            parseFloat(amount) * (10 ** 9 / 1),
            side,
            Date.now()
          );
      
          if (transactionId) {
            console.log(`Transaction: ${transactionId}`);
            notify({ type: 'success', message: `Placed ${side === PositionSideEnum.LONG ? 'LONG' : 'SHORT'} Position`, txid: transactionId });
          }
        } catch (error) {
          notify({ type: 'error', message: 'Transaction failed!', description: error.message, txid: transactionId });
          console.error(`Transaction failed! ${error.message}`, transactionId);
          return;
        }
      }, [publicKey, notify, connection, signTransaction]);
      
    const bgGradientClass =
    side === PositionSideEnum.LONG
      ? 'bg-gradient-to-r from-indigo-500 to-teal-500 hover:from-teal-500 hover:to-indigo-500'
      : 'bg-gradient-to-r from-pink-500 to-yellow-500 hover:from-yellow-500 hover:to-pink-500';


    return (
        <div>
            <button
                className={`group w-60 m-2 btn disabled:animate-none bg-gradient-to-r ${bgGradientClass} ...`}
                onClick={() => onClick(amount, pariPubkey)} disabled={amount === '0'}
            >
                <div className="hidden group-disabled:block ">
                    Enter Amount...
                </div>
                <span className="block group-disabled:hidden" > 
                   {amount} USDC {side === PositionSideEnum.LONG? 'LONG' : 'SHORT'}
                </span>
            </button>
        </div>
    );
};

export default PlacePosition;
