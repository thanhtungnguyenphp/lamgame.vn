<?php

return [
    'paypal' => [
        'client_id'     => env('PAYPAL_SUBSCRIPTION_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_SUBSCRIPTION_CLIENT_SECRET', ''),
        'base_url'      => env('PAYPAL_SUBSCRIPTION_SANDBOX', true)
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com',
        'webhook_id'    => env('PAYPAL_WEBHOOK_ID', ''),
        'return_url'    => env('APP_URL', 'https://lamgame.vn') . '/api/v1/subscription/paypal/return',
        'cancel_url'    => env('APP_URL', 'https://lamgame.vn') . '/api/v1/subscription/paypal/cancel',
    ],
];
