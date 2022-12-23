```php
composer require icekristal/laravel-chain-gateway-api
```

Add to config services.php
```php
    'chain_gateway' => [
        'api' => env('CHAIN_GATEWAY_API', null),
        'tron_url' => env('CHAIN_GATEWAY_TRON_URL', null),
        'token_trc20' => env('CHAIN_GATEWAY_TOKEN_TRC20', null),
        'bsc_url' => env('CHAIN_GATEWAY_BSC_URL', null),
        'token_bep20' => env('CHAIN_GATEWAY_TOKEN_BEP20', null),
        'logging' => [
            'channel_wallet' => env('CHAIN_GATEWAY_LOGGING_WALLET', null),
            'channel_sended' => env('CHAIN_GATEWAY_LOGGING_SENDED', null),
        ],
    ],
```
