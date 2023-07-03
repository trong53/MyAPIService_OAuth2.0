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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),    // '136839999382636',           // Your facebook Client ID
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),  // 'f19236998b0227749d821cc668c46539',   // Your facebook Client Secret
        'redirect'      => env('FACEBOOK_URL_REDIRECT')
    ],
    
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),             // Your google Client ID
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),         // Your google Client Secret
        'redirect'      => env('GOOGLE_URL_REDIRECT')
    ],

    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),             // Your github Client ID
        'client_secret' => env('GITHUB_CLIENT_SECRET'),         // Your github Client Secret
        'redirect'      => env('GITHUB_URL_REDIRECT')
    ],

    'myApiService' => [
        'client_id'     => env('MYAPISERVICE_CLIENT_ID'),       // myApiService Client ID
        'client_secret' => env('MYAPISERVICE_CLIENT_SECRET'),   //myApiService Client Secret
        'redirect'      => env('MYAPISERVICE_URL_REDIRECT')     //myApiService redirect URL
    ],
];
