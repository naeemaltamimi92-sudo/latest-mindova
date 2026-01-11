<?php

namespace App\Services;

use App\Models\InAppGuide;
use App\Models\UserGuidePreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class InAppGuideService
{
    /**
     * Get guide for current page.
     *
     * @param string|null $pageIdentifier
     * @return array|null
     */
    public static function getCurrentGuide(?string $pageIdentifier = null): ?array
    {
        $pageId = $pageIdentifier ?? Route::currentRouteName();

        if (!$pageId) {
            return null;
        }

        $guide = InAppGuide::getForPage($pageId);

        if (!$guide) {
            return null;
        }

        // Check if user has dismissed this guide
        if (Auth::check()) {
            $dismissed = UserGuidePreference::isDismissed(Auth::id(), $pageId);
            if ($dismissed) {
                return null; // Don't show if dismissed
            }
        }

        return [
            'page_title' => $guide->page_title,
            'what_is_this' => $guide->what_is_this,
            'what_to_do' => $guide->what_to_do, // Array of bullet points
            'next_step' => $guide->next_step,
            'ui_highlights' => $guide->ui_highlights,
            'video_url' => $guide->video_url,
            'page_identifier' => $guide->page_identifier,
        ];
    }

    /**
     * Dismiss guide for current user.
     *
     * @param string $pageIdentifier
     * @return void
     */
    public static function dismiss(string $pageIdentifier): void
    {
        if (Auth::check()) {
            UserGuidePreference::dismiss(Auth::id(), $pageIdentifier);
        }
    }

    /**
     * Reset guide (show again).
     *
     * @param string $pageIdentifier
     * @return void
     */
    public static function reset(string $pageIdentifier): void
    {
        if (Auth::check()) {
            UserGuidePreference::reset(Auth::id(), $pageIdentifier);
        }
    }
}
