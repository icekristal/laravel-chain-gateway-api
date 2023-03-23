<?php

namespace Icekristal\LaravelChainGatewayApi\Services;

use Icekristal\LaravelChainGatewayApi\Services\Crypto\v2\BSC;
use Icekristal\LaravelChainGatewayApi\Services\Crypto\v2\Ethereum;
use Icekristal\LaravelChainGatewayApi\Services\Crypto\v2\Tron;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChainGatewayApiV2
{
    use Tron, BSC, Ethereum;

    const TYPE_TRON = 'tron';
    const TYPE_BSC = 'bsc';
    const TYPE_ETHEREUM = 'ethereum';

    /**
     * Key authorize to API
     * @var string|null
     */
    public ?string $key = '';

    public string|null $pathUrl = null;

    public string $channelLogWallets = 'single';
    public string $channelLogSended = 'single';
    public string $channelLogReceived = 'single';

    /**
     *
     */
    public function __construct()
    {
        $this->key = config('services.chain_gateway.api');

        $this->tronUrl = "https://api.chaingateway.io/v2/tron";
        $this->tokenTRC20 = config('services.chain_gateway.token_trc20');
        $this->bscUrl = "https://api.chaingateway.io/v2/bsc";
        $this->tokenBEP20 = config('services.chain_gateway.token_bep20');
        $this->ethereumUrl = "https://api.chaingateway.io/v2/ethereum";
        $this->tokenERC20 = config('services.chain_gateway.token_erc20');

        $this->channelLogWallets = config('services.chain_gateway.logging.channel_wallet') ?? 'single';
        $this->channelLogSended = config('services.chain_gateway.logging.channel_sended') ?? 'single';
        $this->channelLogReceived = config('services.chain_gateway.logging.channel_received') ?? 'single';
    }


    /**
     * @param $typeCrypto
     * @param $typeSend
     * @param array $param
     * @return array|null
     */
    private function sendRequest($typeCrypto, $typeSend, array $param = []): array|null
    {
        $http = Http::withHeaders(
            [
                'Authorization' => $this->key
            ]
        );

        $url = match ($typeCrypto) {
            self::TYPE_TRON => $this->tronUrl,
            self::TYPE_BSC => $this->bscUrl,
            default => null
        };

        $resultUrl = $url . $this->pathUrl;

        $request = match ($typeSend) {
            'delete' => $http->delete($resultUrl, $param)->json(),
            'get' => $http->get($resultUrl, $param)->json(),
            'post' => $http->post($resultUrl, $param)->json(),
            default => []
        };

        $this->saveLogs([
            'type_crypto' => $typeCrypto,
            'url' => $resultUrl,
            'type_send' => $typeSend,
            'params' => $param,
        ], $request);

        return $request;
    }

    private function saveLogs($sendInfo, $receivedInfo): void
    {
        try {
            Log::channel($this->channelLogSended)->info($sendInfo);
            Log::channel($this->channelLogReceived)->info($receivedInfo);
        } catch (\Exception $exception) {

        }
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
