<?php

return [
    'basic_config' => [
        'base_uri' => env('REF_INTERNAL_API_URL'),
        'headers'  => [
            'Content-Type' => 'application/json',
            'X-Account-Id' => env('REF_ACCOUNT_ID')
        ],
    ],
    'public'      => [
        'headers' => [
            'X-Api-Key-Id' => env('REF_PUBLIC_KEY'),
        ],
    ],
    // superuser key
    'su-auth'     => [
        env('REF_SU_KEY_ID'),
        env('REF_SU_SECRET_KEY'),
    ],
    // default referral_account  api key
    'account'     => [
        env('REF_API_KEY_ID'),
        env('REF_API_SECRET_KEY'),
    ]
];
