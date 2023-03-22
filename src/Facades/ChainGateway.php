<?php

namespace Icekristal\LaravelChainGatewayApi\Facades;


use Icekristal\LaravelChainGatewayApi\Services\ChainGatewayApi;
use Icekristal\LaravelChainGatewayApi\Services\ChainGatewayApiV2;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ChainGatewayApi|ChainGatewayApiV2 createNewWalletTron()
 * @method static ChainGatewayApi|ChainGatewayApiV2 getTronBalance(string $address)
 * @method static ChainGatewayApi|ChainGatewayApiV2 getTRC20Balance(string $address)
 * @method static ChainGatewayApi|ChainGatewayApiV2 sendTRC20(string $addressFrom, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi|ChainGatewayApiV2 sendTron(string $addressFrom, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi|ChainGatewayApiV2 subscribeTronAddress(string $addressFrom, string $url)
 * @method static ChainGatewayApi subscribeTRC20Address(string $addressFrom, string $hex)
 * @method static ChainGatewayApi|ChainGatewayApiV2 unSubscribeTronAddress(string $webhookId)
 * @method static ChainGatewayApi unSubscribeTRC20Address(string $addressFrom, string $url)
 * @method static ChainGatewayApi|ChainGatewayApiV2 subscribeTronList()
 * @method static ChainGatewayApi|ChainGatewayApiV2 getTronTransaction(string $tXId)
 * @method static ChainGatewayApi|ChainGatewayApiV2 createNewWalletBNB()
 * @method static ChainGatewayApiV2 deleteWalletBNB(string $address, string $password)
 * @method static ChainGatewayApi|ChainGatewayApiV2 getBNBBalance(string $address)
 * @method static ChainGatewayApi|ChainGatewayApiV2 getBEP20Balance(string $address)
 * @method static ChainGatewayApi|ChainGatewayApiV2 sendBEP20(string $addressFrom, string $privateKey, float $amount, string $addressTo, float $gas)
 * @method static ChainGatewayApi|ChainGatewayApiV2 sendBNB(string $addressFrom, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi|ChainGatewayApiV2 subscribeBNBAddress(string $addressFrom, string $url)
 * @method static ChainGatewayApi subscribeBEP20Address(string $addressFrom, string $hex)
 * @method static ChainGatewayApi|ChainGatewayApiV2 unSubscribeBNBAddress(string $webhookId)
 * @method static ChainGatewayApi unSubscribeBEP20Address(string $addressFrom, string $url)
 * @method static ChainGatewayApi|ChainGatewayApiV2 subscribeBNBList()
 * @method static ChainGatewayApi|ChainGatewayApiV2 getBNBTransaction(string $tXId)
 * @method static ChainGatewayApi|ChainGatewayApiV2 send($type, string $from, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi|ChainGatewayApiV2 balance($type, string $address)
 */
class ChainGateway extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ice.chain.gateway.api';
    }
}
