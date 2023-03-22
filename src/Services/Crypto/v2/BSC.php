<?php

namespace Icekristal\LaravelChainGatewayApi\Services\Crypto\v2;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait BSC
{
    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $bscUrl = null;

    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $tokenBEP20 = null;

    public function __construct()
    {
        $this->bscUrl = "https://api.chaingateway.io/v2/bsc";
        $this->tokenBEP20 = config('services.chain_gateway.token_bep20');
    }

    /**
     * Creates a new wallet
     *
     * @param null $password
     * @return array
     */
    public function createNewWalletBNB($password = null): array
    {
        $this->pathUrl = '/addresses';
        $newWallet = $this->sendRequest(self::TYPE_BSC, 'post',['password' => is_null($password) ? Str::random(13) : $password]);
        Log::channel($this->channelLogWallets)->info($newWallet);
        return $newWallet;
    }

    /**
     * delete wallet
     *
     * @param string $address
     * @param string $password
     * @return array
     */
    public function deleteWalletBNB(string $address, string $password): array
    {
        $this->pathUrl = "/addresses/{$address}";
        $deleteWallet = $this->sendRequest(self::TYPE_BSC, 'delete',['password' => $password]);
        Log::channel($this->channelLogWallets)->info($deleteWallet);
        return $deleteWallet;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getBNBBalance(string $address): float|int
    {
        $this->pathUrl = "/balances/{$address}";
        return (float)$this->sendRequest(self::TYPE_BSC, 'get')['data']['balance'] ?? 0;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getBEP20Balance(string $address): float|int
    {
        $this->pathUrl = "/balances/{$address}/bep20/{$this->tokenBEP20}";
        return (float)$this->sendRequest(self::TYPE_BSC, 'get')['data']['balance'] ?? 0;
    }

    /**
     * Sended BEP20
     *
     * @param string $from
     * @param string $privateKey
     * @param float $amount
     * @param string $to
     * @param float $gas
     * @return array
     */
    public function sendBEP20(string $from, string $privateKey, float $amount, string $to, float $gas): array
    {
        $this->pathUrl = '/transactions/bep20';
        return $this->sendRequest(self::TYPE_BSC, 'post', [
            'from' => $from,
            'contractaddress' => $this->tokenBEP20,
            'to' => $to,
            'password' => $privateKey,
            'amount' => number_format($amount, 8, '.', ''),
            'gas' => $gas
        ]);
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
        $this->pathUrl = '/bsc/transactions';
        return $this->sendRequest(self::TYPE_BSC, 'post', [
            'from' => $from,
            'to' => $to,
            'password' => $privateKey,
            'amount' => number_format($amount, 8, '.', '')
        ]);
    }

    /**
     * Subscribe to hooks
     *
     * @param string $address
     * @param string $url
     * @return array
     */
    public function subscribeBNBAddress(string $address, string $url): array
    {
        $this->pathUrl = '/bsc/webhooks';
        return $this->sendRequest(self::TYPE_BSC, 'post', [
            'binancecoinaddress' => $address,
            'url' => $url
        ]);
    }


    /**
     * Delete to hooks
     *
     * @param string $webhookId
     * @return array
     */
    public function unSubscribeBNBAddress(string $webhookId): array
    {
        $this->pathUrl = "/webhooks/{$webhookId}";
        return $this->sendRequest(self::TYPE_BSC, 'delete');
    }

    /**
     * List hooks
     *
     * @return array
     */
    public function subscribeBNBList(): array
    {
        $this->pathUrl = '/bsc/webhooks';
        return $this->sendRequest(self::TYPE_BSC, 'get');
    }

    /**
     * Transaction Information
     *
     * @param string $txid
     * @return array
     */
    public function getBNBTransaction(string $txid): array
    {
        $this->pathUrl = "/transactions/{$txid}/decoded";
        return $this->sendRequest(self::TYPE_BSC, 'get');
    }

}
