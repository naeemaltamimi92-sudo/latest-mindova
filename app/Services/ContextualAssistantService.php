<?php

namespace App\Services;

use App\Models\ContextualGuidance;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class ContextualAssistantService
{
    /**
     * Check if contextual assistant is globally enabled.
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return config('app.contextual_assistant_enabled', true);
    }

    /**
     * Get guidance for the current page.
     *
     * @param string|null $routeName Optional route name override
     * @return array|null ['icon' => string, 'text' => string]
     */
    public static function getCurrentGuidance(?string $routeName = null): ?array
    {
        // Check if feature is enabled
        if (!self::isEnabled()) {
            return null;
        }

        // Get current route name
        $pageIdentifier = $routeName ?? Route::currentRouteName();

        if (!$pageIdentifier) {
            return null;
        }

        // Cache guidance for 1 hour to reduce database queries
        $cacheKey = "contextual_guidance_{$pageIdentifier}";

        return Cache::remember($cacheKey, 3600, function () use ($pageIdentifier) {
            $guidance = ContextualGuidance::getForPage($pageIdentifier);

            if (!$guidance) {
                return null;
            }

            return [
                'icon' => $guidance->icon,
                'text' => $guidance->guidance_text,
                'page_title' => $guidance->page_title,
            ];
        });
    }

    /**
     * Clear guidance cache.
     *
     * @param string|null $pageIdentifier Optional specific page to clear
     * @return void
     */
    public static function clearCache(?string $pageIdentifier = null): void
    {
        if ($pageIdentifier) {
            Cache::forget("contextual_guidance_{$pageIdentifier}");
        } else {
            // Clear all guidance cache
            $guidances = ContextualGuidance::all();
            foreach ($guidances as $guidance) {
                Cache::forget("contextual_guidance_{$guidance->page_identifier}");
            }
        }
    }

    /**
     * Check if user has dismissed the assistant (using session).
     *
     * @return bool
     */
    public static function isDismissed(): bool
    {
        return session('contextual_assistant_dismissed', false);
    }

    /**
     * Dismiss the assistant for this session.
     *
     * @return void
     */
    public static function dismiss(): void
    {
        session(['contextual_assistant_dismissed' => true]);
    }

    /**
     * Re-enable the assistant (clear dismissal).
     *
     * @return void
     */
    public static function enable(): void
    {
        session()->forget('contextual_assistant_dismissed');
    }
}
