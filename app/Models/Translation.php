<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

/**
 * Translation Model
 *
 * Database-backed translation management for dynamic translation updates.
 *
 * @property int $id
 * @property string $locale
 * @property string|null $group
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @package App\Models
 * @author Mindova Team
 * @version 1.0.0
 */
class Translation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'locale',
        'group',
        'key',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // Clear cache when translation is saved or deleted
        static::saved(function (Translation $translation) {
            $translation->clearCache();
        });

        static::deleted(function (Translation $translation) {
            $translation->clearCache();
        });
    }

    /**
     * Scope to filter by locale
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $locale
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope to filter by group
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $group
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroup($query, ?string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to search translations
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('key', 'like', "%{$search}%")
              ->orWhere('value', 'like', "%{$search}%");
        });
    }

    /**
     * Sync translations from database to JSON file
     *
     * @param string $locale
     * @return bool
     */
    public static function syncToFile(string $locale): bool
    {
        try {
            // Get all translations for locale (only JSON translations, group = null)
            $translations = self::locale($locale)
                ->whereNull('group')
                ->pluck('value', 'key')
                ->toArray();

            // Sort by key
            ksort($translations);

            // Write to JSON file
            $path = lang_path("{$locale}.json");
            File::put(
                $path,
                json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

            // Clear cache
            Cache::forget("translations.{$locale}");

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to sync translations to file for {$locale}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sync translations from JSON file to database
     *
     * @param string $locale
     * @param bool $overwrite Overwrite existing translations
     * @return int Number of translations synced
     */
    public static function syncFromFile(string $locale, bool $overwrite = false): int
    {
        try {
            $path = lang_path("{$locale}.json");

            if (!File::exists($path)) {
                \Log::warning("Translation file not found: {$path}");
                return 0;
            }

            $translations = json_decode(File::get($path), true);

            if (!is_array($translations)) {
                \Log::error("Invalid JSON in translation file: {$path}");
                return 0;
            }

            $count = 0;

            foreach ($translations as $key => $value) {
                $exists = self::locale($locale)
                    ->whereNull('group')
                    ->where('key', $key)
                    ->exists();

                if (!$exists || $overwrite) {
                    self::updateOrCreate(
                        [
                            'locale' => $locale,
                            'group' => null,
                            'key' => $key,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                    $count++;
                }
            }

            return $count;
        } catch (\Exception $e) {
            \Log::error("Failed to sync translations from file for {$locale}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Search translations across all locales
     *
     * @param string $query Search query
     * @param string|null $locale Specific locale (null = all)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function searchAll(string $query, ?string $locale = null)
    {
        $queryBuilder = self::query();

        if ($locale) {
            $queryBuilder->locale($locale);
        }

        return $queryBuilder->search($query)->get();
    }

    /**
     * Get all translations for a locale as key-value array
     *
     * @param string $locale
     * @return array<string, string>
     */
    public static function getAllForLocale(string $locale): array
    {
        return self::locale($locale)
            ->whereNull('group')
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Bulk insert translations
     *
     * @param string $locale
     * @param array<string, string> $translations
     * @return int Number of translations inserted
     */
    public static function bulkInsert(string $locale, array $translations): int
    {
        $count = 0;

        foreach ($translations as $key => $value) {
            self::updateOrCreate(
                [
                    'locale' => $locale,
                    'group' => null,
                    'key' => $key,
                ],
                [
                    'value' => $value,
                ]
            );
            $count++;
        }

        return $count;
    }

    /**
     * Delete translations by keys
     *
     * @param string $locale
     * @param array<string> $keys
     * @return int Number of translations deleted
     */
    public static function bulkDelete(string $locale, array $keys): int
    {
        return self::locale($locale)
            ->whereNull('group')
            ->whereIn('key', $keys)
            ->delete();
    }

    /**
     * Clear translation cache
     *
     * @return void
     */
    private function clearCache(): void
    {
        Cache::forget("translations.{$this->locale}");

        // Also clear TranslationCacheService cache
        try {
            $cacheService = app(\App\Services\TranslationCacheService::class);
            $cacheService->clear($this->locale);
        } catch (\Exception $e) {
            // Silent fail if service not available
        }
    }

    /**
     * Get translation statistics
     *
     * @return array<string, array{total: int, by_locale: array<string, int>}>
     */
    public static function statistics(): array
    {
        $total = self::count();
        $byLocale = self::query()
            ->selectRaw('locale, count(*) as count')
            ->groupBy('locale')
            ->pluck('count', 'locale')
            ->toArray();

        return [
            'total' => $total,
            'by_locale' => $byLocale,
        ];
    }
}
