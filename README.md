```php
composer require icekristal/laravel-chain-gateway-api
```

Api documentation:

Tron: https://chaingateway.io/docs-tron

BNB: https://chaingateway.io/docs-binance-smart-chain

Add to config services.php !! version 1
```php
    'chain_gateway' => [
        'api' => env('CHAIN_GATEWAY_API', null), //API key chain gateway
        'tron_url' => env('CHAIN_GATEWAY_TRON_URL', "https://eu.trx.chaingateway.io/v1"),
        'token_trc20' => env('CHAIN_GATEWAY_TOKEN_TRC20', "TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t"),
        'bsc_url' => env('CHAIN_GATEWAY_BSC_URL', "https://eu.bsc.chaingateway.io/v1"),
        'token_bep20' => env('CHAIN_GATEWAY_TOKEN_BEP20', "0x55d398326f99059ff775485246999027b3197955"),
        'logging' => [
            'channel_wallet' => env('CHAIN_GATEWAY_LOGGING_WALLET', null),
            'channel_sended' => env('CHAIN_GATEWAY_LOGGING_SENDED', null),
        ],
    ],
```

Add to config services.php !! version 2
```php
    'chain_gateway' => [
        'version_api' => env('CHAIN_GATEWAY_API', 'v2'),
        'api' => env('CHAIN_GATEWAY_API', null),
        'token_trc20' => env('CHAIN_GATEWAY_TOKEN_TRC20', null),
        'token_bep20' => env('CHAIN_GATEWAY_TOKEN_BEP20', null),
        'logging' => [
            'is_enable' => env('CHAIN_GATEWAY_LOGGING_IS_ENABLE', false),
            'channel_wallet' => env('CHAIN_GATEWAY_LOGGING_WALLET', 'single'),
            'channel_sended' => env('CHAIN_GATEWAY_LOGGING_SENDED', 'single'),
            'channel_received' => env('CHAIN_GATEWAY_LOGGING_RECEIVED', 'single'),
        ],
    ],
```

Methods:

Create addresses
```php
$result = ChainGateway::createNewWalletTron(); // Tron address
$result = ChainGateway::createNewWalletBNB(); // BNB address
```

Subscribe addresses
```php
$result = ChainGateway::subscribeTronAddress(string $addressFrom, string $url); // For trx transactions
$result = ChainGateway::subscribeTRC20Address(string $addressFrom, string $hex); // For trc20 transactions - only version 1
$result = ChainGateway::subscribeBNBAddress(string $addressFrom, string $url); // For bnb transactions
$result = ChainGateway::subscribeBEP20Address(string $addressFrom, string $hex); // For bnb transactions - only version 1
```

Subscribe list
```php
$result = ChainGateway::subscribeTronList(); // Tron
$result = ChainGateway::subscribeBNBList(); // Bnb
```

unsubscribe addresses !! version 1
```php
$result = ChainGateway::unSubscribeTronAddress(string $addressFrom, string $url); // For trx transactions
$result = ChainGateway::unSubscribeTRC20Address(string $addressFrom, string $url); // For trc20 transactions
$result = ChainGateway::unSubscribeBNBAddress(string $addressFrom, string $url); // For bnb transactions 
$result = ChainGateway::unSubscribeBEP20Address(string $addressFrom, string $url); // For bnb transactions
```

unsubscribe addresses !! version 2
```php
$result = ChainGateway::unSubscribeTronAddress(string $webhookId); // For trx transactions
$result = ChainGateway::unSubscribeBNBAddress(string $webhookId); // For bnb transactions 
```

list transactions
```php
$result = ChainGateway::getTronTransaction(string $tXId); // get info tron transaction
$result = ChainGateway::getBNBTransaction(string $tXId); //  get info bnb transaction
```

get balance
```php
$result = ChainGateway::getBNBBalance(string $address); // get BNB balance
$result = ChainGateway::getBEP20Balance(string $address); // get bep20 balance
$result = ChainGateway::getTronBalance(string $address); // get TRX balance
$result = ChainGateway::getTRC20Balance(string $address); // get trc20 balance

$type = 'trc20'; // variables: trx,bnb,bep20,trc20,trc_20,bep_20
$result = ChainGateway::balance($type, string $address); // get balance for type
```

send crypto
```php
$result = ChainGateway::sendTron(string $addressFrom, string $privateKey, float $amount, string $addressTo); // send TRX
$result = ChainGateway::sendTRC20(string $addressFrom, string $privateKey, float $amount, string $addressTo); // get trc20 balance
$result = ChainGateway::sendBNB(string $addressFrom, string $privateKey, float $amount, string $addressTo); // get BNB balance
$result = ChainGateway::sendBEP20(string $addressFrom, string $privateKey, float $amount, string $addressTo); // get bep20 balance

$type = 'trc20'; // variables: trx,bnb,bep20,trc20,trc_20,bep_20
$result = ChainGateway::send($type, string $from, string $privateKey, float $amount, string $addressTo); // get balance for type
```

