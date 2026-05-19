<?php
return [
    'env' => env('INTERAC_ENV', 'staging'),

    'base_url' => env('INTERAC_ENV', 'staging') === 'production'
        ? env('INTERAC_BASE_URL_PROD')
        : env('INTERAC_BASE_URL_STAGING'),

    'token_url_staging' => env('INTERAC_TOKEN_URL_STAGING'),
    'token_url_prod' => env('INTERAC_TOKEN_URL_PROD'),

    'assertion_aud_staging' => env('INTERAC_ASSERTION_AUD_STAGING'),
    'assertion_aud_prod' => env('INTERAC_ASSERTION_AUD_PROD'),

    'api_audience_staging' => env('INTERAC_API_AUDIENCE_STAGING'),
    'api_audience_prod' => env('INTERAC_API_AUDIENCE_PROD'),

    'client_id' => env('INTERAC_CLIENT_ID'),
    'scope' => env('INTERAC_SCOPE', ''),
    'private_key_path' => env('INTERAC_PRIVATE_KEY_PATH'),
    'private_key_id' => env('INTERAC_PRIVATE_KEY_ID'),
    'token_ttl_buffer' => (int) env('INTERAC_TOKEN_TTL_BUFFER', 60),

    'service_account' => env('INTERAC_SERVICE_ACCOUNT'),
    'timeout' => (int) env('INTERAC_TIMEOUT', 30),

    'inbound_api_key' => env('INTERAC_INBOUND_API_KEY'),
    'outbound_api_key' => env('INTERAC_OUTBOUND_API_KEY'),
];
