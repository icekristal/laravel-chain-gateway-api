<?php

namespace Icekristal\LaravelChainGatewayApi;

use Icekristal\LaravelChainGatewayApi\Services\ChainGatewayApi;
use Icekristal\LaravelChainGatewayApi\Services\ChainGatewayApiV2;
use Illuminate\Support\ServiceProvider;

class ChainGatewayApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ice.chain.gateway.api',
            config('services.chain_gateway.version_api', 'v2') == 'v1' ? ChainGatewayApi::class : ChainGatewayApiV2::class);
    }
}
