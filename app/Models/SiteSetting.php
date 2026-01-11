<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Cache duration in seconds (1 hour)
     */
    private const CACHE_DURATION = 3600;

    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "site_setting_{$key}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value): bool
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return false;
        }

        // Convert value based on type
        if ($setting->type === 'boolean') {
            $value = $value ? '1' : '0';
        } elseif ($setting->type === 'json') {
            $value = json_encode($value);
        }

        $setting->value = (string) $value;
        $result = $setting->save();

        // Clear cache
        Cache::forget("site_setting_{$key}");
        Cache::forget('site_settings_all');

        return $result;
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('site_settings_all', self::CACHE_DURATION, function () {
            $settings = [];

            foreach (self::all() as $setting) {
                $settings[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $settings;
        });
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        $cacheKey = "site_settings_group_{$group}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($group) {
            return self::where('group', $group)->get()->toArray();
        });
    }

    /**
     * Get all public settings
     */
    public static function getPublicSettings(): array
    {
        return Cache::remember('site_settings_public', self::CACHE_DURATION, function () {
            $settings = [];

            foreach (self::where('is_public', true)->get() as $setting) {
                $settings[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $settings;
        });
    }

    /**
     * Check if a feature is enabled
     */
    public static function isEnabled(string $key): bool
    {
        return (bool) self::get($key, false);
    }

    /**
     * Cast value based on type
     */
    private static function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => $value === '1' || $value === 'true',
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings_all');
        Cache::forget('site_settings_public');

        foreach (self::all() as $setting) {
            Cache::forget("site_setting_{$setting->key}");
            Cache::forget("site_settings_group_{$setting->group}");
        }
    }

    /**
     * Helper: Check if language switcher is enabled
     */
    public static function isLanguageSwitcherEnabled(): bool
    {
        return self::isEnabled('language_switcher_enabled');
    }

    /**
     * Helper: Check if WhatsApp notifications are enabled
     */
    public static function isWhatsAppEnabled(): bool
    {
        return self::isEnabled('whatsapp_notifications_enabled');
    }

    /**
     * Helper: Check if registration is enabled
     */
    public static function isRegistrationEnabled(): bool
    {
        return self::isEnabled('registration_enabled');
    }

    /**
     * Helper: Get default language
     */
    public static function getDefaultLanguage(): string
    {
        return self::get('default_language', 'en');
    }

    /**
     * Helper: Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode(): bool
    {
        return self::isEnabled('maintenance_mode');
    }
}
