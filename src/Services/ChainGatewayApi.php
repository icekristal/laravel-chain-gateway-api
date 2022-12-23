<?php

namespace Icekristal\LaravelChainGatewayApi\Services;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChainGatewayApi
{
    /**
     * Key authorize to API
     * @var string|null
     */
    public ?string $key = '';

    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $tronUrl = null;

    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $bscUrl = null;

    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $tokenTRC20 = null;

    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $tokenBEP20 = null;

    public string|null $pathUrl = null;

    public string $channelLogWallets = 'single';
    public string $channelLogSended = 'single';

    /**
     *
     */
    public function __construct()
    {
        $this->key = config('services.chain_gateway.api');
        $this->tronUrl = config('services.chain_gateway.tron_url');
        $this->tokenTRC20 = config('services.chain_gateway.token_trc20');

        $this->bscUrl = config('services.chain_gateway.bsc_url');
        $this->tokenBEP20 = config('services.chain_gateway.token_bep20');

        $this->channelLogWallets = config('services.chain_gateway.logging.channel_wallet') ?? 'single';
        $this->channelLogSended = config('services.chain_gateway.logging.channel_sended') ?? 'single';
    }


    /**
     * Sending a request to the tron
     *
     * @param array $param
     * @return array
     */
    private function sendRequestTron(array $param = []): array
    {
        $param['apikey'] = $this->key;
        return Http::post($this->tronUrl . $this->pathUrl, $param)->json();
    }

    /**
     * Creates a new wallet
     *
     * @return array
     */
    public function createNewWalletTron(): array
    {
        $this->pathUrl = '/newAddress';
        $newWallet = $this->sendRequestTron();
        Log::channel($this->channelLogWallets)->info($newWallet);
        return $newWallet;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getTronBalance(string $address): float|int
    {
        $this->pathUrl = '/getTronBalance';
        return (float)$this->sendRequestTron([
            'tronaddress' => $address
        ])['balance'] ?? 0;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getTRC20Balance(string $address): float|int
    {
        $this->pathUrl = '/getTRC20Balance';
        return (float)$this->sendRequestTron([
            'tronaddress' => $address,
            'contractaddress' => $this->tokenTRC20
        ])['balance'] ?? 0;
    }

    /**
     * Sended TRC20
     *
     * @param string $from
     * @param string $privateKey
     * @param float $amount
     * @param string $to
     * @return array
     */
    public function sendTRC20(string $from, string $privateKey, float $amount, string $to): array
    {
        $this->pathUrl = '/sendTRC20';
        $sendInfo = $this->sendRequestTron([
            'from' => $from,
            'contractaddress' => $this->tokenTRC20,
            'to' => $to,
            'privatekey' => $privateKey,
            'amount' => number_format($amount, 8, '.', '')
        ]);

        Log::channel($this->channelLogSended)->info($sendInfo);
        return $sendInfo;
    }

    /**
     * Sended TRX
     *
     * @param string $from
     * @param string $privateKey
     * @param float $amount
     * @param string|null $to
     * @return array
     */
    public function sendTron(string $from, string $privateKey, float $amount, ?string $to = NULL): array
    {
        $this->pathUrl = '/sendTron';
        $sendInfo = $this->sendRequestTron([
            'from' => $from,
            'to' => $to ?? config('payments.hot_trx'),
            'privatekey' => $privateKey,
            'amount' => number_format($amount, 8, '.', '')
        ]);
        Log::channel($this->channelLogSended)->info($sendInfo);
        return $sendInfo;
    }

    /**
     * Subscribe to hooks
     *
     * @param string $address
     * @param string $hex
     * @return array
     */
    public function subscribeTronAddress(string $address, string $hex): array
    {
        $this->pathUrl = '/subscribeAddress';
        return $this->sendRequestTron([
            'tronaddress' => $address,
            'url' => route('internal.chain_gateway.trx', $hex)
        ]);
    }

    /**
     * Subscribe to hooks
     *
     * @param string $address
     * @param string $hex
     * @return array
     */
    public function subscribeTRC20Address(string $address, string $hex): array
    {
        $this->pathUrl = '/subscribeAddress';
        return $this->sendRequestTron([
            'tronaddress' => $address,
            'contractaddress' => $this->tokenTRC20,
            'url' => route('internal.chain_gateway.trc', $hex)
        ]);


    }

    /**
     * Delete to hooks
     *
     * @param string $address
     * @param string $url
     * @return array
     */
    public function unSubscribeTronAddress(string $address, string $url): array
    {
        $this->pathUrl = '/unsubscribeAddress';
        return $this->sendRequestTron([
            'tronaddress' => $address,
            'url' => $url
        ]);
    }

    /**
     * Delete to hooks
     *
     * @param string $address
     * @param string $url
     * @return array
     */
    public function unSubscribeTRC20Address(string $address, string $url): array
    {
        $this->pathUrl = '/unsubscribeAddress';
        return $this->sendRequestTron([
            'tronaddress' => $address,
            'contractaddress' => $this->tokenTRC20,
            'url' => $url
        ]);
    }

    /**
     * List hooks
     *
     * @return array
     */
    public function subscribeTronList(): array
    {
        $this->pathUrl = '/listSubscribedAddresses';
        return $this->sendRequestTron();
    }

    /**
     * Transaction Information
     *
     * @param string $txid
     * @return array
     */
    public function getTronTransaction(string $txid): array
    {
        $this->pathUrl = '/getTransaction';
        return $this->sendRequestTron([
            'txid' => $txid
        ]);
    }


    /**
     * Sending a request to Binance Smart Chain
     *
     * @param array $param
     * @return array
     */
    private function sendRequestBSC(array $param = []): array
    {
        $param['apikey'] = $this->key;
        return Http::post($this->bscUrl . $this->pathUrl, $param)->json();
    }

    /**
     * Creates a new wallet
     *
     * @return array
     */
    public function createNewWalletBNB(): array
    {
        $this->pathUrl = '/newAddress';
        $newWallet = $this->sendRequestBSC([
            'password' => Str::random(13),
        ]);
        Log::channel($this->channelLogWallets)->info($newWallet);
        return $newWallet;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getBNBBalance(string $address): float|int
    {
        $this->pathUrl = '/getBinancecoinBalance';
        return (float)$this->sendRequestBSC([
            'binancecoinaddress' => $address
        ])['balance'] ?? 0;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getBEP20Balance(string $address): float|int
    {
        $this->pathUrl = '/getTokenBalance';
        return (float)$this->sendRequestBSC([
            'binancecoinaddress' => $address,
            'contractaddress' => $this->tokenBEP20
        ])['balance'] ?? 0;
    }

    /**
     * Sended BEP20
     *
     * @param string $from
     * @param string $privateKey
     * @param float $amount
     * @param string $to
     * @return array
     */
    public function sendBEP20(string $from, string $privateKey, float $amount, string $to): array
    {
        $this->pathUrl = '/sendToken';
        $sendInfo = $this->sendRequestBSC([
            'from' => $from,
            'contractaddress' => $this->tokenBEP20,
            'to' => $to,
            'password' => $privateKey,
            'amount' => number_format($amount, 8, '.', '')
        ]);
        Log::channel($this->channelLogSended)->info($sendInfo);
        return $sendInfo;
    }

    /**
     * Sended BNB
     *
     * @param string $from
     * @param string $privateKey
     * @param float $amount
     * @param string $to
     * @return array
     */
    public function sendBNB(string $from, string $privateKey, float $amount, string $to): array
    {
        $this->pathUrl = '/sendBinancecoin';
        $sendInfo = $this->sendRequestBSC([
            'from' => $from,
            'to' => $to,
            'password' => $privateKey,
            'amount' => number_format($amount, 8, '.', '')
        ]);
        Log::channel($this->channelLogSended)->info($sendInfo);
        return $sendInfo;
    }

    /**
     * Subscribe to hooks
     *
     * @param string $address
     * @param string $hex
     * @return array
     */
    public function subscribeBNBAddress(string $address, string $hex): array
    {
        $this->pathUrl = '/subscribeAddress';
        return $this->sendRequestBSC([
            'binancecoinaddress' => $address,
            'url' => route('internal.chain_gateway.bnb', $hex)
        ]);
    }

    /**
     * Subscribe to hooks
     *
     * @param string $address
     * @param string $hex
     * @return array
     */
    public function subscribeBEP20Address(string $address, string $hex): array
    {
        $this->pathUrl = '/subscribeAddress';
        return $this->sendRequestBSC([
            'binancecoinaddress' => $address,
            'contractaddress' => $this->tokenBEP20,
            'url' => route('internal.chain_gateway.bep', $hex)
        ]);
    }

    /**
     * Delete to hooks
     *
     * @param string $address
     * @param string $url
     * @return array
     */
    public function unSubscribeBNBAddress(string $address, string $url): array
    {
        $this->pathUrl = '/unsubscribeAddress';
        return $this->sendRequestBSC([
            'binancecoinaddress' => $address,
            'url' => $url
        ]);
    }

    /**
     * Delete to hooks
     *
     * @param string $address
     * @param string $url
     * @return array
     */
    public function unSubscribeBEP20Address(string $address, string $url): array
    {
        $this->pathUrl = '/unsubscribeAddress';
        return $this->sendRequestBSC([
            'binancecoinaddress' => $address,
            'contractaddress' => $this->tokenBEP20,
            'url' => $url
        ]);
    }

    /**
     * List hooks
     *
     * @return array
     */
    public function subscribeBNBList(): array
    {
        $this->pathUrl = '/listSubscribedAddresses';
        return $this->sendRequestBSC();
    }

    /**
     * Transaction Information
     *
     * @param string $txid
     * @return array
     */
    public function getBNBTransaction(string $txid): array
    {
        $this->pathUrl = '/getTransaction';
        return $this->sendRequestBSC([
            'txid' => $txid
        ]);
    }


    /**
     * General Dispatch
     *
     * @param $type
     * @param string $from
     * @param string $privateKey
     * @param float $amount
     * @param string $to
     * @return array
     */
    public function send($type, string $from, string $privateKey, float $amount, string $to): array
    {
        return match (mb_strtolower($type)) {
            'trx' => $this->sendTron($from, $privateKey, $amount, $to),
            'bnb' => $this->sendBNB($from, $privateKey, $amount, $to),
            'trc20', 'trc20_usdt', 'trc_20' => $this->sendTRC20($from, $privateKey, $amount, $to),
            'bep20', 'bep20_usdt', 'bep_20' => $this->sendBEP20($from, $privateKey, $amount, $to),
        };
    }

    /**
     * Total receipt of balance
     *
     * @param $type
     * @param string $address
     * @return array|float|int
     */
    public function balance($type, string $address): float|int|array
    {
        return match (mb_strtolower($type)) {
            'trx' => $this->getTronBalance($address),
            'bnb' => $this->getBNBBalance($address),
            'trc20', 'trc20_usdt', 'trc_20' => $this->getTRC20Balance($address),
            'bep20', 'bep20_usdt', 'bep_20' => $this->getBEP20Balance($address),
        };
    }
}
