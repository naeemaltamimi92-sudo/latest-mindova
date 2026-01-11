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

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT_URI'),
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'), // Format: +14155238886 (sandbox or production)
        'whatsapp_production_enabled' => env('TWILIO_WHATSAPP_PRODUCTION_ENABLED', false),
        'sms_from' => env('TWILIO_SMS_FROM'), // Format: +1234567890
        'sms_enabled' => env('TWILIO_SMS_ENABLED', true),
    ],

    'meta' => [
        'whatsapp_access_token' => env('META_WHATSAPP_ACCESS_TOKEN'),
        'whatsapp_phone_number_id' => env('META_WHATSAPP_PHONE_NUMBER_ID'),
        'whatsapp_business_account_id' => env('META_WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'whatsapp_cloud_enabled' => env('META_WHATSAPP_CLOUD_ENABLED', false),
    ],

    // Notification channel priority (try in this order)
    'notification_channel_priority' => [
        'whatsapp_production', // Twilio WhatsApp Business API (when approved)
        'sms',                 // SMS fallback (works immediately)
        'whatsapp_sandbox',    // Sandbox (requires recipient join - testing only)
    ],

];
