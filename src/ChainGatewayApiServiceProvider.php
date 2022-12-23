<?php

namespace Icekristal\LaravelChainGatewayApi;

use Icekristal\LaravelChainGatewayApi\Services\ChainGatewayApi;
use Illuminate\Support\ServiceProvider;

class ChainGatewayApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ice.chain.gateway.api', ChainGatewayApi::class);
    }
}
