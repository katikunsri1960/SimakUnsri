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
    // config/services.php
    'api_pd_unsri' => [
        'base_url' => env('API_PD_UNSRI_URL'),
        'username' => env('API_PD_UNSRI_USERNAME'),
        'password' => env('API_PD_UNSRI_PASSWORD'),
        'timeout' => env('API_PD_UNSRI_TIMEOUT', 5),
        'connect_timeout' => env('API_PD_UNSRI_CONNECT_TIMEOUT', 3),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'moodle' => [
    'ws_url' => env('MOODLE_WS_URL'),
    'ws_token' => env('MOODLE_WS_TOKEN'),
],

];
