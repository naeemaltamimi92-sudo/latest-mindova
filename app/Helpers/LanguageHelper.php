<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LanguageHelper
{
    /**
     * Check if the current or specified locale is RTL.
     *
     * @param string|null $locale
     * @return bool
     */
    public static function isRTL(?string $locale = null): bool
    {
        $locale = $locale ?? App::getLocale();
        $rtlLanguages = Config::get('languages.rtl', []);

        return in_array($locale, $rtlLanguages);
    }

    /**
     * Get all supported locales.
     *
     * @return array
     */
    public static function getSupportedLocales(): array
    {
        return Config::get('languages.supported', ['en']);
    }

    /**
     * Get the current locale with fallback to default.
     *
     * @return string
     */
    public static function getCurrentLocale(): string
    {
        $locale = App::getLocale();
        $supported = self::getSupportedLocales();

        return in_array($locale, $supported) ? $locale : self::getDefaultLocale();
    }

    /**
     * Get the default locale.
     *
     * @return string
     */
    public static function getDefaultLocale(): string
    {
        return Config::get('languages.default', 'en');
    }

    /**
     * Get language names for all supported locales.
     *
     * @return array
     */
    public static function getLanguageNames(): array
    {
        return Config::get('languages.names', ['en' => 'English']);
    }

    /**
     * Get the name of a specific locale.
     *
     * @param string $locale
     * @return string
     */
    public static function getLanguageName(string $locale): string
    {
        $names = self::getLanguageNames();
        return $names[$locale] ?? $locale;
    }

    /**
     * Check if a locale is supported.
     *
     * @param string $locale
     * @return bool
     */
    public static function isSupported(string $locale): bool
    {
        return in_array($locale, self::getSupportedLocales());
    }

    /**
     * Get the text direction for a locale.
     *
     * @param string|null $locale
     * @return string 'rtl' or 'ltr'
     */
    public static function getDirection(?string $locale = null): string
    {
        return self::isRTL($locale) ? 'rtl' : 'ltr';
    }

    /**
     * Detect locale from browser Accept-Language header.
     *
     * @param string|null $acceptLanguage
     * @return string|null
     */
    public static function detectBrowserLocale(?string $acceptLanguage = null): ?string
    {
        if (!$acceptLanguage) {
            return null;
        }

        $supported = self::getSupportedLocales();

        // Parse Accept-Language header
        preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLanguage, $matches);

        if (!$matches[1]) {
            return null;
        }

        $languages = [];
        foreach ($matches[1] as $index => $lang) {
            $quality = isset($matches[4][$index]) ? (float) $matches[4][$index] : 1.0;
            $languages[$lang] = $quality;
        }

        // Sort by quality
        arsort($languages);

        // Find first supported language
        foreach (array_keys($languages) as $lang) {
            // Extract base language code (e.g., 'en' from 'en-US')
            $baseLang = strtok($lang, '-');

            if (in_array($baseLang, $supported)) {
                return $baseLang;
            }
        }

        return null;
    }

    /**
     * Get language configuration value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function config(string $key, $default = null)
    {
        return Config::get("languages.{$key}", $default);
    }
}
