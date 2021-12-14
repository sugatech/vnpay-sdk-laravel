<?php

return [
    'api_url' => env('VNPAY_API_URL'),
    'oauth' => [
        'url' => env('VNPAY_OAUTH_URL', env('VNPAY_API_URL').'/oauth/token'),
        'client_id' => env('VNPAY_OAUTH_CLIENT_ID'),
        'client_secret' => env('VNPAY_OAUTH_CLIENT_SECRET'),
    ],
];
