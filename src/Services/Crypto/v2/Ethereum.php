<?php

namespace Icekristal\LaravelChainGatewayApi\Services\Crypto\v2;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait Ethereum
{
    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $ethereumUrl = null;

    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $tokenERC20 = null;

    /**
     * Creates a new wallet
     *
     * @param null $password
     * @return array
     */
    public function createNewWalletEthereum($password = null): array
    {
        $this->pathUrl = '/addresses';
        $newWallet = $this->sendRequest(self::TYPE_ETHEREUM, 'post',['password' => is_null($password) ? Str::random(13) : $password]);
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
    public function deleteWalletEthereum(string $address, string $password): array
    {
        $this->pathUrl = "/addresses/{$address}";
        $deleteWallet = $this->sendRequest(self::TYPE_ETHEREUM, 'delete',['password' => $password]);
        Log::channel($this->channelLogWallets)->info($deleteWallet);
        return $deleteWallet;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getEthereumBalance(string $address): float|int
    {
        $this->pathUrl = "/balances/{$address}";
        return (float)$this->sendRequest(self::TYPE_ETHEREUM, 'get')['data']['balance'] ?? 0;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getERC20Balance(string $address): float|int
    {
        $this->pathUrl = "/balances/{$address}/erc20/{$this->tokenBEP20}";
        return (float)$this->sendRequest(self::TYPE_ETHEREUM, 'get')['data']['balance'] ?? 0;
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
    public function sendERC20(string $from, string $privateKey, float $amount, string $to, float $gas = 0): array
    {
        $this->pathUrl = '/transactions/erc20';
        return $this->sendRequest(self::TYPE_ETHEREUM, 'post', [
            'from' => $from,
            'contractaddress' => $this->tokenBEP20,
            'to' => $to,
            'password' => $privateKey,
            'amount' => number_format($amount, 8, '.', ''),
            'gas' => $gas ?? 0
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
    public function sendEthereum(string $from, string $privateKey, float $amount, string $to): array
    {
        $this->pathUrl = '/transactions';
        return $this->sendRequest(self::TYPE_ETHEREUM, 'post', [
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
    public function subscribeEthereumAddress(string $address, string $url): array
    {
        $this->pathUrl = '/webhooks';
        return $this->sendRequest(self::TYPE_ETHEREUM, 'post', [
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
    public function unSubscribeEthereumAddress(string $webhookId): array
    {
        $this->pathUrl = "/webhooks/{$webhookId}";
        return $this->sendRequest(self::TYPE_ETHEREUM, 'delete');
    }

    /**
     * List hooks
     *
     * @return array
     */
    public function subscribeEthereumList(): array
    {
        $this->pathUrl = '/webhooks';
        return $this->sendRequest(self::TYPE_ETHEREUM, 'get');
    }

    /**
     * Transaction Information
     *
     * @param string $txid
     * @return array
     */
    public function getEthereumTransaction(string $txid): array
    {
        $this->pathUrl = "/transactions/{$txid}/decoded";
        return $this->sendRequest(self::TYPE_ETHEREUM, 'get');
    }

}
