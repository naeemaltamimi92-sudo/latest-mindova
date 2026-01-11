<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use NumberFormatter;

/**
 * Locale-Aware Formatters
 *
 * Provides locale-aware formatting for numbers, currency, dates, times, and more.
 * Supports both English and Arabic formatting.
 *
 * @package App\Helpers
 * @author Mindova Team
 * @version 1.0.0
 */
class LocaleFormatters
{
    /**
     * Arabic numerals mapping
     */
    private const ARABIC_NUMERALS = [
        '0' => '٠',
        '1' => '١',
        '2' => '٢',
        '3' => '٣',
        '4' => '٤',
        '5' => '٥',
        '6' => '٦',
        '7' => '٧',
        '8' => '٨',
        '9' => '٩',
    ];

    /**
     * Format number according to locale
     *
     * @param int|float $number Number to format
     * @param int $decimals Number of decimal places
     * @param string|null $locale Locale (null = current)
     * @return string Formatted number
     */
    public static function formatNumber(int|float $number, int $decimals = 0, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        if ($locale === 'ar') {
            // Arabic formatting with Arabic numerals
            $formatted = number_format($number, $decimals, '٫', '٬');
            return self::convertToArabicNumerals($formatted);
        }

        // English formatting
        return number_format($number, $decimals, '.', ',');
    }

    /**
     * Format currency amount
     *
     * @param float $amount Amount to format
     * @param string $currency Currency code (USD, SAR, etc.)
     * @param string|null $locale Locale (null = current)
     * @return string Formatted currency
     */
    public static function formatCurrency(float $amount, string $currency = 'USD', ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        $localeCode = $locale === 'ar' ? 'ar_SA' : 'en_US';

        try {
            $formatter = new NumberFormatter($localeCode, NumberFormatter::CURRENCY);
            $formatted = $formatter->formatCurrency($amount, $currency);

            if ($locale === 'ar') {
                return self::convertToArabicNumerals($formatted);
            }

            return $formatted;
        } catch (\Exception $e) {
            // Fallback to manual formatting
            $symbol = self::getCurrencySymbol($currency);
            $formatted = self::formatNumber($amount, 2, $locale);

            return $locale === 'ar' ? "{$formatted} {$symbol}" : "{$symbol}{$formatted}";
        }
    }

    /**
     * Format date according to locale
     *
     * @param Carbon|string $date Date to format
     * @param string $format Format (short, medium, long, full)
     * @param string|null $locale Locale (null = current)
     * @return string Formatted date
     */
    public static function formatDate(Carbon|string $date, string $format = 'medium', ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        // Set locale for Carbon
        $date->locale($locale);

        $formats = [
            'short' => $locale === 'ar' ? 'd/m/Y' : 'm/d/Y',
            'medium' => $locale === 'ar' ? 'd M Y' : 'M d, Y',
            'long' => $locale === 'ar' ? 'd F Y' : 'F d, Y',
            'full' => $locale === 'ar' ? 'l، d F Y' : 'l, F d, Y',
        ];

        $formatString = $formats[$format] ?? $formats['medium'];
        $formatted = $date->translatedFormat($formatString);

        if ($locale === 'ar') {
            return self::convertToArabicNumerals($formatted);
        }

        return $formatted;
    }

    /**
     * Format time according to locale
     *
     * @param Carbon|string $time Time to format
     * @param string $format Format (short, medium, long)
     * @param string|null $locale Locale (null = current)
     * @return string Formatted time
     */
    public static function formatTime(Carbon|string $time, string $format = 'short', ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        if (!$time instanceof Carbon) {
            $time = Carbon::parse($time);
        }

        $time->locale($locale);

        $formats = [
            'short' => 'H:i',
            'medium' => 'H:i:s',
            'long' => 'H:i:s T',
        ];

        $formatString = $formats[$format] ?? $formats['short'];
        $formatted = $time->format($formatString);

        if ($locale === 'ar') {
            return self::convertToArabicNumerals($formatted);
        }

        return $formatted;
    }

    /**
     * Format datetime according to locale
     *
     * @param Carbon|string $datetime Datetime to format
     * @param string|null $locale Locale (null = current)
     * @return string Formatted datetime
     */
    public static function formatDateTime(Carbon|string $datetime, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        if (!$datetime instanceof Carbon) {
            $datetime = Carbon::parse($datetime);
        }

        $datetime->locale($locale);

        if ($locale === 'ar') {
            $formatted = $datetime->translatedFormat('d F Y - H:i');
            return self::convertToArabicNumerals($formatted);
        }

        return $datetime->format('F d, Y - H:i');
    }

    /**
     * Format relative time (e.g., "5 minutes ago")
     *
     * @param Carbon|string|int $timestamp Timestamp
     * @param string|null $locale Locale (null = current)
     * @return string Formatted relative time
     */
    public static function formatRelativeTime(Carbon|string|int $timestamp, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        if (is_int($timestamp)) {
            $carbon = Carbon::createFromTimestamp($timestamp);
        } elseif (is_string($timestamp)) {
            $carbon = Carbon::parse($timestamp);
        } else {
            $carbon = $timestamp;
        }

        $carbon->locale($locale);

        $relative = $carbon->diffForHumans();

        if ($locale === 'ar') {
            return self::convertToArabicNumerals($relative);
        }

        return $relative;
    }

    /**
     * Format file size (bytes to human-readable)
     *
     * @param int $bytes File size in bytes
     * @param int $precision Decimal precision
     * @param string|null $locale Locale (null = current)
     * @return string Formatted file size
     */
    public static function formatFileSize(int $bytes, int $precision = 2, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        $units = $locale === 'ar'
            ? ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت', 'تيرابايت']
            : ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        $formatted = self::formatNumber($bytes, $precision, $locale);

        return $formatted . ' ' . $units[$pow];
    }

    /**
     * Format percentage
     *
     * @param float $decimal Decimal value (0.5 = 50%)
     * @param int $decimals Decimal places
     * @param string|null $locale Locale (null = current)
     * @return string Formatted percentage
     */
    public static function formatPercentage(float $decimal, int $decimals = 0, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        $percentage = $decimal * 100;
        $formatted = self::formatNumber($percentage, $decimals, $locale);

        return $locale === 'ar' ? "%{$formatted}" : "{$formatted}%";
    }

    /**
     * Format phone number according to locale
     *
     * @param string $phone Phone number
     * @param string|null $locale Locale (null = current)
     * @return string Formatted phone number
     */
    public static function formatPhone(string $phone, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if ($locale === 'ar') {
            return self::convertToArabicNumerals($phone);
        }

        return $phone;
    }

    /**
     * Format list of items according to locale
     *
     * @param array<string> $items Items to format
     * @param string|null $locale Locale (null = current)
     * @return string Formatted list
     */
    public static function formatList(array $items, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        if (empty($items)) {
            return '';
        }

        if (count($items) === 1) {
            return $items[0];
        }

        if ($locale === 'ar') {
            $last = array_pop($items);
            return implode('، ', $items) . ' و ' . $last;
        }

        // English
        $last = array_pop($items);
        return implode(', ', $items) . ' and ' . $last;
    }

    /**
     * Convert Western numerals to Arabic numerals
     *
     * @param string $text Text containing numbers
     * @return string Text with Arabic numerals
     */
    private static function convertToArabicNumerals(string $text): string
    {
        return strtr($text, self::ARABIC_NUMERALS);
    }

    /**
     * Get currency symbol
     *
     * @param string $currency Currency code
     * @return string Currency symbol
     */
    private static function getCurrencySymbol(string $currency): string
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'SAR' => 'ر.س',
            'AED' => 'د.إ',
            'EGP' => 'ج.م',
            'JOD' => 'د.ا',
        ];

        return $symbols[$currency] ?? $currency;
    }

    /**
     * Format duration (seconds to human-readable)
     *
     * @param int $seconds Duration in seconds
     * @param string|null $locale Locale (null = current)
     * @return string Formatted duration
     */
    public static function formatDuration(int $seconds, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($locale === 'ar') {
            $parts = [];
            if ($hours > 0) {
                $parts[] = self::convertToArabicNumerals((string)$hours) . ' ساعة';
            }
            if ($minutes > 0) {
                $parts[] = self::convertToArabicNumerals((string)$minutes) . ' دقيقة';
            }
            if ($secs > 0 || empty($parts)) {
                $parts[] = self::convertToArabicNumerals((string)$secs) . ' ثانية';
            }
            return implode(' و ', $parts);
        }

        // English
        $parts = [];
        if ($hours > 0) {
            $parts[] = "{$hours}h";
        }
        if ($minutes > 0) {
            $parts[] = "{$minutes}m";
        }
        if ($secs > 0 || empty($parts)) {
            $parts[] = "{$secs}s";
        }
        return implode(' ', $parts);
    }

    /**
     * Format ordinal number (1st, 2nd, 3rd, etc.)
     *
     * @param int $number Number
     * @param string|null $locale Locale (null = current)
     * @return string Formatted ordinal
     */
    public static function formatOrdinal(int $number, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        if ($locale === 'ar') {
            return 'الـ ' . self::convertToArabicNumerals((string)$number);
        }

        // English
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        }

        return $number . $ends[$number % 10];
    }
}
