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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloud Vendor
    |--------------------------------------------------------------------------
    */

    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'folder' => env('AWS_FOLDER'),
        'storage' => env('AWS_KEY_STORAGE_TYPE'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Vendors for OAuth Login
    |--------------------------------------------------------------------------
    */

    'twitter' => [
        'enable' => env('CONFIG_ENABLE_LOGIN_TWITTER'),
        'client_id' => env('TWITTER_API_KEY'),
        'client_secret' => env('TWITTER_API_SECRET'),
        'redirect' => env('TWITTER_REDIRECT'),
    ],

    'linkedin' => [
        'enable' => env('CONFIG_ENABLE_LOGIN_LINKEDIN'),
        'client_id' => env('LINKEDIN_API_KEY'),
        'client_secret' => env('LINKEDIN_API_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT'),
    ],

    'google' => [
        'enable' => env('CONFIG_ENABLE_LOGIN_GOOGLE'),
        'client_id' => env('GOOGLE_API_KEY'),
        'client_secret' => env('GOOGLE_API_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
        /* Google reCaptcha Keys */
        'recaptcha' => [
            'enable' => env('GOOGLE_RECAPTCHA_ENABLE'),
            'site_key' => env('GOOGLE_RECAPTCHA_SITE_KEY'),
            'secret_key' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
        ],  
        /* Google Maps API Key */
        'maps' => [
            'enable' => env('GOOGLE_MAPS_ENABLE'),
            'key' => env('GOOGLE_MAPS_KEY'),   
        ],   
        /* Google Analytics Tracking ID */
        'analytics' => [
            'enable' => env('GOOGLE_ANALYTICS_ENABLE'),
            'id' => env('GOOGLE_ANALYTICS_ID'),   
        ],
    ],

    'facebook' => [
        'enable' => env('CONFIG_ENABLE_LOGIN_FACEBOOK'),
        'client_id' => env('FACEBOOK_API_KEY'),
        'client_secret' => env('FACEBOOK_API_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    */

    'stripe' => [
        'subscription_enable' => env('STRIPE_SUBSCRIPTION_ENABLED'),
        'base_uri' => env('STRIPE_BASE_URI'),
        'webhook_uri' => env('STRIPE_WEBHOOK_URI'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'api_key' => env('STRIPE_KEY'),
        'api_secret' => env('STRIPE_SECRET'),
        'class' => App\Services\StripeService::class,
    ],

    'paypal' => [
        'subscription_enable' => env('PAYPAL_SUBSCRIPTION_ENABLED'),
        'base_uri' => env('PAYPAL_BASE_URI'),
        'webhook_uri' => env('PAYPAL_WEBHOOK_URI'),
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'class' => App\Services\PayPalService::class,
    ],

    'paystack' => [
        'subscription_enable' => env('PAYSTACK_SUBSCRIPTION_ENABLED'),
        'base_uri' => env('PAYSTACK_BASE_URI'),
        'webhook_uri' => env('PAYSTACK_WEBHOOK_URI'),
        'api_key' => env('PAYSTACK_PUBLIC_KEY'),
        'api_secret' => env('PAYSTACK_SECRET_KEY'),
        'class' => App\Services\PaystackService::class,
    ],

    'razorpay' => [
        'subscription_enable' => env('RAZORPAY_SUBSCRIPTION_ENABLED'),
        'base_uri' => env('RAZORPAY_BASE_URI'),
        'webhook_uri' => env('RAZORPAY_WEBHOOK_URI'),
        'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
        'key_id' => env('RAZORPAY_KEY_ID'),
        'key_secret' => env('RAZORPAY_KEY_SECRET'),
        'class' => App\Services\RazorpayService::class,
    ],

    'banktransfer' => [
        'subscription' => env('BANK_TRANSFER_SUBSCRIPTION'),
        'class' => App\Services\BankTransferService::class,
    ],

];
