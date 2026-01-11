<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Meta WhatsApp Business API credentials here.
    | Get these from: https://developers.facebook.com
    |
    */

    // Meta App Credentials
    'app_id' => env('META_APP_ID'),
    'app_secret' => env('META_APP_SECRET'),

    // WhatsApp Business Account
    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),

    // Access Token (Permanent token from Meta Business Suite)
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),

    // Webhook Configuration
    'verify_token' => env('WHATSAPP_VERIFY_TOKEN', 'mindova_whatsapp_verify_2024'),
    'webhook_url' => env('WHATSAPP_WEBHOOK_URL'),

    // API Configuration
    'api_version' => env('WHATSAPP_API_VERSION', 'v18.0'),
    'api_base_url' => 'https://graph.facebook.com',

    // Message Settings
    'default_language' => env('WHATSAPP_DEFAULT_LANGUAGE', 'en'),
    'supported_languages' => ['en', 'ar'],

    // Rate Limiting
    'rate_limit' => [
        'messages_per_second' => 80,
        'messages_per_day' => 1000,
    ],

    // Retry Configuration
    'retry' => [
        'max_attempts' => 3,
        'delay_seconds' => 5,
    ],

    // Message Templates (Pre-approved by Meta)
    'templates' => [
        'welcome' => [
            'name' => 'welcome_message',
            'language' => 'en',
        ],
        'welcome_ar' => [
            'name' => 'welcome_message_ar',
            'language' => 'ar',
        ],
        'challenge_notification' => [
            'name' => 'new_challenge_notification',
            'language' => 'en',
        ],
        'task_assignment' => [
            'name' => 'task_assignment_notification',
            'language' => 'en',
        ],
        'team_invitation' => [
            'name' => 'team_invitation',
            'language' => 'en',
        ],
        'submission_received' => [
            'name' => 'submission_received',
            'language' => 'en',
        ],
        'submission_approved' => [
            'name' => 'submission_approved',
            'language' => 'en',
        ],
        'submission_rejected' => [
            'name' => 'submission_rejected',
            'language' => 'en',
        ],
    ],

    // Logging
    'logging' => [
        'enabled' => env('WHATSAPP_LOGGING_ENABLED', true),
        'channel' => env('WHATSAPP_LOG_CHANNEL', 'whatsapp'),
    ],

    // Queue Configuration
    'queue' => [
        'connection' => env('WHATSAPP_QUEUE_CONNECTION', 'database'),
        'queue_name' => env('WHATSAPP_QUEUE_NAME', 'whatsapp'),
    ],

    // Media Settings
    'media' => [
        'max_file_size' => 16 * 1024 * 1024, // 16MB
        'allowed_image_types' => ['image/jpeg', 'image/png'],
        'allowed_document_types' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'allowed_audio_types' => ['audio/aac', 'audio/mp4', 'audio/mpeg', 'audio/amr', 'audio/ogg'],
        'allowed_video_types' => ['video/mp4', 'video/3gp'],
    ],
];
