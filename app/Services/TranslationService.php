<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

/**
 * Advanced Translation Service
 *
 * Provides comprehensive translation management with intelligent fallback,
 * missing translation logging, caching, pluralization, and RTL detection.
 *
 * @package App\Services
 * @author Mindova Team
 * @version 1.0.0
 */
class TranslationService
{
    /**
     * Supported locales
     */
    private const SUPPORTED_LOCALES = ['en', 'ar'];

    /**
     * RTL (Right-to-Left) locales
     */
    private const RTL_LOCALES = ['ar', 'he', 'ur', 'fa'];

    /**
     * Cache key prefix for translations
     */
    private const CACHE_PREFIX = 'translations';

    /**
     * Cache duration in seconds (24 hours)
     */
    private const CACHE_DURATION = 86400;

    /**
     * Missing translations log file
     */
    private const MISSING_LOG = 'missing-translations.log';

    /**
     * Translation cache service instance
     */
    private ?TranslationCacheService $cacheService = null;

    /**
     * Constructor
     */
    public function __construct(?TranslationCacheService $cacheService = null)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Enhanced translation with smart fallback and parameter replacement
     *
     * @param string $key Translation key
     * @param array<string, mixed> $params Parameters for replacement
     * @param string|null $locale Target locale (null = current)
     * @return string Translated text
     */
    public function translate(string $key, array $params = [], ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        // Validate locale
        if (!$this->isValidLocale($locale)) {
            Log::warning("Invalid locale requested: {$locale}");
            $locale = config('app.fallback_locale', 'en');
        }

        // Try to get translation
        $translation = $this->getTranslation($key, $locale);

        // If not found, try fallback locale
        if ($translation === $key && $locale !== config('app.fallback_locale', 'en')) {
            $fallbackLocale = config('app.fallback_locale', 'en');
            $translation = $this->getTranslation($key, $fallbackLocale);

            // Log missing translation
            if ($translation === $key) {
                $this->logMissing($key, $locale);
            }
        }

        // If still not found, log it
        if ($translation === $key) {
            $this->logMissing($key, $locale);
        }

        // Replace parameters
        if (!empty($params)) {
            $translation = $this->replaceParameters($translation, $params);
        }

        return $translation;
    }

    /**
     * Check if a translation key exists
     *
     * @param string $key Translation key
     * @param string|null $locale Target locale (null = current)
     * @return bool True if translation exists
     */
    public function has(string $key, ?string $locale = null): bool
    {
        $locale = $locale ?? App::getLocale();
        $translation = $this->getTranslation($key, $locale);

        return $translation !== $key;
    }

    /**
     * Get missing translations for a locale
     *
     * @param string|null $locale Target locale (null = current)
     * @return array<string> Array of missing translation keys
     */
    public function missing(?string $locale = null): array
    {
        $locale = $locale ?? App::getLocale();
        $logFile = storage_path('logs/' . self::MISSING_LOG);

        if (!File::exists($logFile)) {
            return [];
        }

        $missing = [];
        $content = File::get($logFile);
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            if (str_contains($line, "[$locale]")) {
                // Extract key from log line
                preg_match('/Key: (.+?) \|/', $line, $matches);
                if (isset($matches[1])) {
                    $missing[] = $matches[1];
                }
            }
        }

        return array_unique($missing);
    }

    /**
     * Get all translations for a locale
     *
     * @param string|null $locale Target locale (null = current)
     * @return array<string, string> All translations
     */
    public function all(?string $locale = null): array
    {
        $locale = $locale ?? App::getLocale();

        if (!$this->isValidLocale($locale)) {
            return [];
        }

        // Try cache first
        if ($this->cacheService) {
            $cached = $this->cacheService->get($locale);
            if ($cached !== null) {
                return $cached;
            }
        }

        // Load from file
        $path = lang_path("{$locale}.json");

        if (!File::exists($path)) {
            Log::warning("Translation file not found: {$path}");
            return [];
        }

        try {
            $translations = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

            // Cache the translations
            if ($this->cacheService && is_array($translations)) {
                $this->cacheService->put($locale, $translations);
            }

            return is_array($translations) ? $translations : [];
        } catch (\JsonException $e) {
            Log::error("Failed to decode translation file for locale {$locale}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count translations for a locale
     *
     * @param string|null $locale Target locale (null = current)
     * @return int Number of translations
     */
    public function count(?string $locale = null): int
    {
        return count($this->all($locale));
    }

    /**
     * Get translation coverage percentage
     *
     * @param string|null $locale Target locale (null = current)
     * @return float Coverage percentage (0-100)
     */
    public function coverage(?string $locale = null): float
    {
        $locale = $locale ?? App::getLocale();
        $baseLocale = config('app.fallback_locale', 'en');

        if ($locale === $baseLocale) {
            return 100.0;
        }

        $baseCount = $this->count($baseLocale);
        $localeCount = $this->count($locale);

        if ($baseCount === 0) {
            return 0.0;
        }

        return round(($localeCount / $baseCount) * 100, 2);
    }

    /**
     * Log missing translation
     *
     * @param string $key Translation key
     * @param string $locale Locale
     * @return void
     */
    public function logMissing(string $key, string $locale): void
    {
        $logFile = storage_path('logs/' . self::MISSING_LOG);

        // Check if already logged to avoid duplicates
        if (File::exists($logFile)) {
            $content = File::get($logFile);
            if (str_contains($content, "[{$locale}] Key: {$key}")) {
                return; // Already logged
            }
        }

        $timestamp = now()->format('Y-m-d H:i:s');
        $url = request()->fullUrl();
        $logEntry = "[{$timestamp}] [{$locale}] Key: {$key} | URL: {$url}\n";

        try {
            File::append($logFile, $logEntry);
        } catch (\Exception $e) {
            Log::error("Failed to log missing translation: " . $e->getMessage());
        }
    }

    /**
     * Validate all translations exist for a locale
     *
     * @param string|null $locale Target locale (null = current)
     * @return array{valid: bool, missing: array<string>, count: int, total: int} Validation result
     */
    public function validateTranslations(?string $locale = null): array
    {
        $locale = $locale ?? App::getLocale();
        $baseLocale = config('app.fallback_locale', 'en');

        $baseTranslations = $this->all($baseLocale);
        $localeTranslations = $this->all($locale);

        $missing = [];
        foreach (array_keys($baseTranslations) as $key) {
            if (!isset($localeTranslations[$key])) {
                $missing[] = $key;
            }
        }

        return [
            'valid' => empty($missing),
            'missing' => $missing,
            'count' => count($localeTranslations),
            'total' => count($baseTranslations),
        ];
    }

    /**
     * Get translation from cache or file
     *
     * @param string $key Translation key
     * @param string $locale Locale
     * @return string Translation or key if not found
     */
    private function getTranslation(string $key, string $locale): string
    {
        $translations = $this->all($locale);
        return $translations[$key] ?? $key;
    }

    /**
     * Replace parameters in translation
     *
     * @param string $translation Translation text
     * @param array<string, mixed> $params Parameters
     * @return string Translation with replaced parameters
     */
    private function replaceParameters(string $translation, array $params): string
    {
        foreach ($params as $key => $value) {
            $translation = str_replace(
                [':' . $key, ':' . strtoupper($key), ':' . ucfirst($key)],
                [$value, strtoupper((string)$value), ucfirst((string)$value)],
                $translation
            );
        }

        return $translation;
    }

    /**
     * Check if locale is valid
     *
     * @param string $locale Locale code
     * @return bool True if valid
     */
    private function isValidLocale(string $locale): bool
    {
        return in_array($locale, self::SUPPORTED_LOCALES, true);
    }

    /**
     * Check if locale is RTL (Right-to-Left)
     *
     * @param string|null $locale Locale code (null = current)
     * @return bool True if RTL
     */
    public function isRtl(?string $locale = null): bool
    {
        $locale = $locale ?? App::getLocale();
        return in_array($locale, self::RTL_LOCALES, true);
    }

    /**
     * Get direction for locale (ltr or rtl)
     *
     * @param string|null $locale Locale code (null = current)
     * @return string 'rtl' or 'ltr'
     */
    public function direction(?string $locale = null): string
    {
        return $this->isRtl($locale) ? 'rtl' : 'ltr';
    }

    /**
     * Get translation statistics
     *
     * @return array{locales: array<string, array{count: int, coverage: float, missing: int}>} Statistics
     */
    public function statistics(): array
    {
        $stats = ['locales' => []];

        foreach (self::SUPPORTED_LOCALES as $locale) {
            $stats['locales'][$locale] = [
                'count' => $this->count($locale),
                'coverage' => $this->coverage($locale),
                'missing' => count($this->missing($locale)),
            ];
        }

        return $stats;
    }

    /**
     * Handle pluralization
     *
     * @param string $key Translation key
     * @param int|float|array<mixed> $count Count for pluralization
     * @param array<string, mixed> $params Additional parameters
     * @param string|null $locale Target locale
     * @return string Pluralized translation
     */
    public function choice(string $key, int|float|array $count, array $params = [], ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        $number = is_array($count) ? count($count) : $count;

        // Get the translation
        $translation = $this->translate($key, array_merge($params, ['count' => $number]), $locale);

        // If translation contains pipe-separated plurals
        if (str_contains($translation, '|')) {
            $segments = explode('|', $translation);

            // For Arabic, use complex plural rules
            if ($locale === 'ar') {
                return $this->getArabicPlural($segments, $number, $params);
            }

            // For English and others, use simple rules
            return $this->getSimplePlural($segments, $number, $params);
        }

        return $translation;
    }

    /**
     * Get Arabic plural form (6 forms)
     *
     * @param array<string> $segments Plural segments
     * @param int|float $number Count
     * @param array<string, mixed> $params Parameters
     * @return string Selected plural form
     */
    private function getArabicPlural(array $segments, int|float $number, array $params): string
    {
        $number = (int)$number;
        $index = 0;

        // Arabic plural rules: zero, one, two, few (3-10), many (11-99), other (100+)
        if ($number === 0) {
            $index = 0;
        } elseif ($number === 1) {
            $index = 1;
        } elseif ($number === 2) {
            $index = 2;
        } elseif ($number >= 3 && $number <= 10) {
            $index = 3;
        } elseif ($number >= 11 && $number <= 99) {
            $index = 4;
        } else {
            $index = 5;
        }

        $form = $segments[$index] ?? $segments[count($segments) - 1];
        return $this->replaceParameters($form, $params);
    }

    /**
     * Get simple plural form (singular/plural)
     *
     * @param array<string> $segments Plural segments
     * @param int|float $number Count
     * @param array<string, mixed> $params Parameters
     * @return string Selected plural form
     */
    private function getSimplePlural(array $segments, int|float $number, array $params): string
    {
        $index = $number === 1 ? 0 : 1;
        $form = $segments[$index] ?? $segments[count($segments) - 1];
        return $this->replaceParameters($form, $params);
    }
}
