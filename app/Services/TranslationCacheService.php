<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Translation Cache Service
 *
 * Manages caching for translation files to improve performance.
 * Provides cache warming, invalidation, and statistics.
 *
 * @package App\Services
 * @author Mindova Team
 * @version 1.0.0
 */
class TranslationCacheService
{
    /**
     * Cache key prefix
     */
    private const CACHE_PREFIX = 'translations';

    /**
     * Cache duration in seconds (24 hours)
     */
    private const CACHE_DURATION = 86400;

    /**
     * Supported locales
     */
    private const SUPPORTED_LOCALES = ['en', 'ar'];

    /**
     * Warm translation cache for all locales
     *
     * Loads all translation files into cache for faster access.
     *
     * @return array{success: bool, cached: array<string>, failed: array<string>, duration: float}
     */
    public function warm(): array
    {
        $startTime = microtime(true);
        $cached = [];
        $failed = [];

        Log::info('Starting translation cache warming...');

        foreach (self::SUPPORTED_LOCALES as $locale) {
            try {
                $translations = $this->loadTranslationsFromFile($locale);

                if ($translations !== null) {
                    $this->put($locale, $translations);
                    $cached[] = $locale;
                    Log::info("Cached {$locale} translations: " . count($translations) . " keys");
                } else {
                    $failed[] = $locale;
                    Log::warning("Failed to load translations for locale: {$locale}");
                }
            } catch (\Exception $e) {
                $failed[] = $locale;
                Log::error("Error caching translations for {$locale}: " . $e->getMessage());
            }
        }

        $duration = round(microtime(true) - $startTime, 3);
        Log::info("Translation cache warming completed in {$duration}s");

        return [
            'success' => empty($failed),
            'cached' => $cached,
            'failed' => $failed,
            'duration' => $duration,
        ];
    }

    /**
     * Clear translation cache
     *
     * Removes all cached translations.
     *
     * @param string|null $locale Specific locale to clear (null = all)
     * @return bool Success status
     */
    public function clear(?string $locale = null): bool
    {
        try {
            if ($locale === null) {
                // Clear all locales
                foreach (self::SUPPORTED_LOCALES as $loc) {
                    Cache::forget($this->getCacheKey($loc));
                }
                Log::info('Cleared all translation caches');
            } else {
                // Clear specific locale
                Cache::forget($this->getCacheKey($locale));
                Log::info("Cleared translation cache for locale: {$locale}");
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to clear translation cache: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cached translations for a locale
     *
     * @param string $locale Locale code
     * @return array<string, string>|null Cached translations or null if not cached
     */
    public function get(string $locale): ?array
    {
        try {
            $cacheKey = $this->getCacheKey($locale);
            $cached = Cache::get($cacheKey);

            if (is_array($cached)) {
                Log::debug("Cache hit for locale: {$locale}");
                return $cached;
            }

            Log::debug("Cache miss for locale: {$locale}");
            return null;
        } catch (\Exception $e) {
            Log::error("Error getting cached translations for {$locale}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Put translations into cache
     *
     * @param string $locale Locale code
     * @param array<string, string> $translations Translations to cache
     * @return bool Success status
     */
    public function put(string $locale, array $translations): bool
    {
        try {
            $cacheKey = $this->getCacheKey($locale);
            Cache::put($cacheKey, $translations, self::CACHE_DURATION);

            Log::debug("Cached {$locale} translations: " . count($translations) . " keys");
            return true;
        } catch (\Exception $e) {
            Log::error("Error caching translations for {$locale}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cache statistics
     *
     * @return array{cached_locales: array<string>, total_keys: int, cache_size_mb: float, hit_rate: float}
     */
    public function stats(): array
    {
        $cachedLocales = [];
        $totalKeys = 0;
        $cacheSize = 0;

        foreach (self::SUPPORTED_LOCALES as $locale) {
            $translations = $this->get($locale);

            if ($translations !== null) {
                $cachedLocales[] = $locale;
                $totalKeys += count($translations);
                $cacheSize += strlen(json_encode($translations));
            }
        }

        return [
            'cached_locales' => $cachedLocales,
            'total_keys' => $totalKeys,
            'cache_size_mb' => round($cacheSize / 1024 / 1024, 2),
            'hit_rate' => count($cachedLocales) / count(self::SUPPORTED_LOCALES) * 100,
        ];
    }

    /**
     * Check if translations are cached for a locale
     *
     * @param string $locale Locale code
     * @return bool True if cached
     */
    public function isCached(string $locale): bool
    {
        return Cache::has($this->getCacheKey($locale));
    }

    /**
     * Invalidate cache and reload from file
     *
     * @param string $locale Locale code
     * @return bool Success status
     */
    public function refresh(string $locale): bool
    {
        try {
            // Clear cache
            $this->clear($locale);

            // Reload from file
            $translations = $this->loadTranslationsFromFile($locale);

            if ($translations !== null) {
                $this->put($locale, $translations);
                Log::info("Refreshed translation cache for locale: {$locale}");
                return true;
            }

            Log::warning("Failed to refresh translations for locale: {$locale}");
            return false;
        } catch (\Exception $e) {
            Log::error("Error refreshing translation cache for {$locale}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get remaining cache time in seconds
     *
     * @param string $locale Locale code
     * @return int|null Seconds until expiration or null if not cached
     */
    public function getTtl(string $locale): ?int
    {
        $cacheKey = $this->getCacheKey($locale);

        // Laravel doesn't provide direct TTL access, so we estimate
        if ($this->isCached($locale)) {
            // Return default duration as we can't get exact TTL
            return self::CACHE_DURATION;
        }

        return null;
    }

    /**
     * Get cache key for locale
     *
     * @param string $locale Locale code
     * @return string Cache key
     */
    private function getCacheKey(string $locale): string
    {
        return self::CACHE_PREFIX . '.' . $locale;
    }

    /**
     * Load translations from file
     *
     * @param string $locale Locale code
     * @return array<string, string>|null Translations or null if failed
     */
    private function loadTranslationsFromFile(string $locale): ?array
    {
        $path = lang_path("{$locale}.json");

        if (!File::exists($path)) {
            Log::warning("Translation file not found: {$path}");
            return null;
        }

        try {
            $content = File::get($path);
            $translations = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            return is_array($translations) ? $translations : null;
        } catch (\JsonException $e) {
            Log::error("Failed to decode translation file for {$locale}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Warm cache for specific locale
     *
     * @param string $locale Locale code
     * @return bool Success status
     */
    public function warmLocale(string $locale): bool
    {
        try {
            $translations = $this->loadTranslationsFromFile($locale);

            if ($translations !== null) {
                return $this->put($locale, $translations);
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Error warming cache for {$locale}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cache memory usage in bytes
     *
     * @return int Total cache size in bytes
     */
    public function getCacheSize(): int
    {
        $totalSize = 0;

        foreach (self::SUPPORTED_LOCALES as $locale) {
            $translations = $this->get($locale);

            if ($translations !== null) {
                $totalSize += strlen(json_encode($translations));
            }
        }

        return $totalSize;
    }
}
