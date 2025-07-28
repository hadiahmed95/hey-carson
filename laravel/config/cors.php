<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // FIXED: Cannot use '*' with credentials, must specify exact origins
    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:5173',  // Vite default port
        'http://127.0.0.1:3000',
        'http://127.0.0.1:5173',  // Vite default port with 127.0.0.1
        'http://127.0.0.1:8000',  // Laravel default
        'https://app.shopexperts.com',
        'https://app.staging.shopexperts.com'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];