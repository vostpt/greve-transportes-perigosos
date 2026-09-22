<?php

declare(strict_types=1);

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'api_v1' => [
        'csv_password' => env('API_V1_DOWNLOAD_API_CSV'),
    ],

    'recaptcha' => [
        'secret' => env('GOOGLE_RECAPTCHA_V3_SECRET'),
        'site_key' => env('GOOGLE_RECAPTCHA_V3_SITE_KEY'),
        'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
    ],

    'errors_webhook' => [
        'url' => env('ERRORS_SPREADSHEET_LINK'),
    ],

    'cloudflare' => [
        'token' => env('CLOUDFLARE_API_TOKEN'),
        'zone' => env('CLOUDFLARE_ZONE_ID'),
    ],

    'mapbox' => [
        'token' => env('MAPBOX_TOKEN'),
        'style' => env('MAPBOX_STYLE', 'mapbox://styles/mapbox/streets-v12'),
    ],

];
