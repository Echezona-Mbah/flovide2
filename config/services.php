<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ibanq' => [
        'api_key' => env('IBANQ_API_KEY'),
        'base_url' => env('IBANQ_BASE_URL'),
    ],

    'sumsub' => [
        'app_token' => env('SUMSUB_APP_TOKEN'),
        'secret_key' => env('SUMSUB_SECRET_KEY'),
    ],
    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'client_email' => env('FIREBASE_CLIENT_EMAIL'),
        'private_key' => str_replace('\n', "\n", env('FIREBASE_PRIVATE_KEY')),
    ],

    'blaaiz' => [
        'base_url' => env('BLAAIZ_BASE_URL', env('BLAAIZ_API_URL', 'https://api-dev.blaaiz.com')),
        'client_id' => env('BLAAIZ_CLIENT_ID'),
        'client_secret' => env('BLAAIZ_CLIENT_SECRET'),
        'oauth_scope' => env('BLAAIZ_OAUTH_SCOPE', env('BLAAIZ_SCOPES', '')),
        'webhook_secret' => env('BLAAIZ_WEBHOOK_SECRET'),
        'timeout' => env('BLAAIZ_TIMEOUT', 30),
    ],

    
    'latest_version' => env('APP_LATEST_VERSION'),
    'force_update'   => env('APP_FORCE_UPDATE', false),
    
    'fidelity' => [
        'base_url' => env('FIDELITY_BASE_URL'),
        'client_id' => env('FIDELITY_CLIENT_ID'),
        'client_secret' => env('FIDELITY_CLIENT_SECRET'),
        'static_va_path' => env('FIDELITY_STATIC_VA_PATH', '/virtual-account/generate-static-virtual-account'),
        'dynamic_va_path' => env('FIDELITY_DYNAMIC_VA_PATH', '/virtual-account/generate-dynamic-virtual-account'),
    ],

    'ohentpay' => [
        'enabled'    => env('OHENTPAY_ENABLED'),
        'api_key'    => env('OHENTPAY_API_KEY'),
        'base_url'   => env('OHENTPAY_BASE_URL', 'https://api.ohentpay.com'),
        'currencies' => array_filter(array_map('trim', explode(',', env('OHENTPAY_CURRENCIES', '')))),
    ],

    //     'pivot' => [
    //     'base_url' => env('PIVOT_BASE_URL', 'https://merchant-api.dev.atlassnomad.com'),
    //     'username' => env('PIVOT_USERNAME', 'FLOVIDE'),
    //     'password' => env('PIVOT_PASSWORD', 'd9Dv5jy4I9qQpDefyd22Yln7AfiTMQt-C-HStI9g5zM'),
    //     'ugx_bank_service' => env('PIVOT_UGX_BANK_SERVICE', 'PS347884'),
    //     'ugx_mobile_service' => env('PIVOT_UGX_MOBILE_SERVICE', 'PS347884'),
    //     'ugx_mobile_service_validation' => env('PIVOT_UGX_MOBILE_SERVICE_VALIDATION', 'PS628030'),
    // ],

];
