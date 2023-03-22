<?php
return [
    'chain_gateway' => [
        'version_api' => env('CHAIN_GATEWAY_API_VERSION', 'v2'),
        'api' => env('CHAIN_GATEWAY_API', null),
        'tron_url' => env('CHAIN_GATEWAY_TRON_URL', null), //only api version 1
        'token_trc20' => env('CHAIN_GATEWAY_TOKEN_TRC20', null),
        'bsc_url' => env('CHAIN_GATEWAY_BSC_URL', null), //only api version 1
        'token_bep20' => env('CHAIN_GATEWAY_TOKEN_BEP20', null),
        'logging' => [
            'is_enable' => env('CHAIN_GATEWAY_LOGGING_IS_ENABLE', false),
            'channel_wallet' => env('CHAIN_GATEWAY_LOGGING_WALLET', 'single'),
            'channel_sended' => env('CHAIN_GATEWAY_LOGGING_SENDED', 'single'),
            'channel_received' => env('CHAIN_GATEWAY_LOGGING_RECEIVED', 'single'), //only api version 2
        ],
    ],
];
