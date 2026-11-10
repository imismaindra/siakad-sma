<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Notification Channels & Adapters
    |--------------------------------------------------------------------------
    |
    | Supported WhatsApp Adapters: 'log', 'fonnte'
    |
    */
    'whatsapp' => [
        'adapter' => env('WHATSAPP_ADAPTER', 'log'),
        'fonnte' => [
            'token' => env('FONNTE_TOKEN', ''),
            'endpoint' => env('FONNTE_ENDPOINT', 'https://api.fonnte.com/send'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Credentials
    |--------------------------------------------------------------------------
    */
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN', ''),
    ],
];
