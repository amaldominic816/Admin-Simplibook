<?php

return [
 

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // 'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    // 'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,
    'allowed_headers' => ['Content-Type', 'X-Localization'],
    'allowed_origins' => ['*'],


    'supports_credentials' => false,

];
