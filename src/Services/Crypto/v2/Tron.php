<?php

namespace Icekristal\LaravelChainGatewayApi\Services\Crypto\v2;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;

trait Tron
{
    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $tronUrl = null;

    /**
     * @var Repository|Application|mixed|null
     */
    public mixed $tokenTRC20 = null;

    /**
     * Creates a new wallet
     *
     * @return array
     */
    public function createNewWalletTron(): array
    {
        $this->pathUrl = '/addresses';
        $newWallet = $this->sendRequest(self::TYPE_TRON, 'post');
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
        $this->pathUrl = "/balances/{$address}";
        return (float)$this->sendRequest(self::TYPE_TRON, 'get')['data']['balance'] ?? 0;
    }

    /**
     * Checks wallet balance
     *
     * @param string $address
     * @return float|int
     */
    public function getTRC20Balance(string $address): float|int
    {
        $this->pathUrl = "/balances/{$address}/trc20/{$this->tokenTRC20}";
        return (float)$this->sendRequest(self::TYPE_TRON, 'get')['data']['balance'] ?? 0;
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
        $this->pathUrl = '/transactions/trc20';
        return $this->sendRequest(self::TYPE_TRON, 'post', [
            'from' => $from,
            'contractaddress' => $this->tokenTRC20,
            'to' => $to,
            'privatekey' => $privateKey,
            'amount' => number_format($amount, 8, '.', '')
        ]);
    }

    /**
     * Sended TRX
     *
     * @param string $from
     * @param string $privateKey
     * @param float $amount
     * @param string $to
     * @return array
     */
    public function sendTron(string $from, string $privateKey, float $amount, string $to): array
    {
        $this->pathUrl = '/transactions';
        return $this->sendRequest(self::TYPE_TRON, 'post', [
            'from' => $from,
            'to' => $to,
            'privatekey' => $privateKey,
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
    public function subscribeTronAddress(string $address, string $url): array
    {
        $this->pathUrl = '/webhooks';
        return $this->sendRequest(self::TYPE_TRON, 'post', [
            'from' => $address,
            'url' => $url
        ]);
    }

    /**
     * Delete to hooks
     *
     * @param string $webhookId
     * @return array
     */
    public function unSubscribeTronAddress(string $webhookId): array
    {
        $this->pathUrl = "/webhooks/{$webhookId}";
        return $this->sendRequest(self::TYPE_TRON, 'delete');
    }

    /**
     * List hooks
     *
     * @return array
     */
    public function subscribeTronList(): array
    {
        $this->pathUrl = '/webhooks';
        return $this->sendRequest(self::TYPE_TRON, 'get');
    }

    /**
     * Transaction Information
     *
     * @param string $txid
     * @return array
     */
    public function getTronTransaction(string $txid): array
    {
        $this->pathUrl = "/transactions/{$txid}/decoded";
        return $this->sendRequest(self::TYPE_TRON, 'get');
    }
}
