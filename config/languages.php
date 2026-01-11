<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported Languages
    |--------------------------------------------------------------------------
    |
    | This array contains all the languages that are supported by the application.
    | Add new language codes here when you want to support additional languages.
    |
    */
    'supported' => ['en', 'ar'],

    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    |
    | This is the default language that will be used when no other language
    | preference is found (session, user, or browser).
    |
    */
    'default' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Language Names
    |--------------------------------------------------------------------------
    |
    | Human-readable names for each supported language.
    | These are used in the language switcher dropdown.
    |
    */
    'names' => [
        'en' => 'English',
        'ar' => 'العربية'
    ],

    /*
    |--------------------------------------------------------------------------
    | RTL Languages
    |--------------------------------------------------------------------------
    |
    | Languages that use right-to-left text direction.
    | This is used to set the 'dir' attribute on the HTML element.
    |
    */
    'rtl' => ['ar'],

    /*
    |--------------------------------------------------------------------------
    | Browser Language Detection
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic browser language detection for guest users.
    | When enabled, the application will try to detect the user's preferred
    | language from their browser settings.
    |
    */
    'browser_detection' => true,

    /*
    |--------------------------------------------------------------------------
    | Language Cookie Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the language preference cookie.
    | This cookie is used to persist language preference for guest users.
    |
    */
    'cookie' => [
        'name' => 'mindova_locale',
        'lifetime' => 525600, // 1 year in minutes
    ],
];
