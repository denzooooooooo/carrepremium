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

    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    */
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Orange Money Configuration (Côte d'Ivoire)
    |--------------------------------------------------------------------------
    */
    'orange_money' => [
        'merchant_key' => env('ORANGE_MONEY_MERCHANT_KEY'),
        'api_url' => env('ORANGE_MONEY_API_URL', 'https://api.orange.com/orange-money-webpay/dev/v1'),
        'auth_url' => env('ORANGE_MONEY_AUTH_URL', 'https://api.orange.com/oauth/v3/token'),
        'client_id' => env('ORANGE_MONEY_CLIENT_ID'),
        'client_secret' => env('ORANGE_MONEY_CLIENT_SECRET'),
        'return_url' => env('ORANGE_MONEY_RETURN_URL', 'http://localhost:3000/payment/callback'),
    ],

    /*
    |--------------------------------------------------------------------------
    | MTN Mobile Money Configuration
    |--------------------------------------------------------------------------
    */
    'mtn_momo' => [
        'api_key' => env('MTN_MOMO_API_KEY'),
        'api_secret' => env('MTN_MOMO_API_SECRET'),
        'subscription_key' => env('MTN_MOMO_SUBSCRIPTION_KEY'),
        'environment' => env('MTN_MOMO_ENVIRONMENT', 'sandbox'), // sandbox or production
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend URL
    |--------------------------------------------------------------------------
    */
    'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),

];
