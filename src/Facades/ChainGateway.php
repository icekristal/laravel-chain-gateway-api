<?php

namespace Icekristal\LaravelChainGatewayApi\Facades;


use Icekristal\LaravelChainGatewayApi\Services\ChainGatewayApi;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ChainGatewayApi createNewWalletTron()
 * @method static ChainGatewayApi getTronBalance(string $address)
 * @method static ChainGatewayApi getTRC20Balance(string $address)
 * @method static ChainGatewayApi sendTRC20(string $addressFrom, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi sendTron(string $addressFrom, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi subscribeTronAddress(string $addressFrom, string $hex)
 * @method static ChainGatewayApi subscribeTRC20Address(string $addressFrom, string $hex)
 * @method static ChainGatewayApi unSubscribeTronAddress(string $addressFrom, string $url)
 * @method static ChainGatewayApi unSubscribeTRC20Address(string $addressFrom, string $url)
 * @method static ChainGatewayApi subscribeTronList()
 * @method static ChainGatewayApi getTronTransaction(string $tXId)
 * @method static ChainGatewayApi createNewWalletBNB()
 * @method static ChainGatewayApi getBNBBalance(string $address)
 * @method static ChainGatewayApi getBEP20Balance(string $address)
 * @method static ChainGatewayApi sendBEP20(string $addressFrom, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi sendBNB(string $addressFrom, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi subscribeBNBAddress(string $addressFrom, string $hex)
 * @method static ChainGatewayApi subscribeBEP20Address(string $addressFrom, string $hex)
 * @method static ChainGatewayApi unSubscribeBNBAddress(string $addressFrom, string $url)
 * @method static ChainGatewayApi unSubscribeBEP20Address(string $addressFrom, string $url)
 * @method static ChainGatewayApi subscribeBNBList()
 * @method static ChainGatewayApi getBNBTransaction(string $tXId)
 * @method static ChainGatewayApi send($type, string $from, string $privateKey, float $amount, string $addressTo)
 * @method static ChainGatewayApi balance($type, string $address)
 */
class ChainGateway extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ice.chain.gateway.api';
    }
}
