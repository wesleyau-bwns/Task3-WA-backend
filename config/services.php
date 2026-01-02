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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'passport' => [
        'user-api_client_id' => env('USER_API_CLIENT_ID'),
        'user-api_client_secret' => env('USER_API_CLIENT_SECRET'),

        'merchant-api_client_id' => env('MERCHANT_API_CLIENT_ID'),
        'merchant-api_client_secret' => env('MERCHANT_API_CLIENT_SECRET'),

        'admin-api_client_id' => env('ADMIN_API_CLIENT_ID'),
        'admin-api_client_secret' => env('ADMIN_API_CLIENT_SECRET'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
