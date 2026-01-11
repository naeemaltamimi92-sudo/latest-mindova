<?php

namespace App\Http\Controllers;

use App\Helpers\LanguageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function switch(Request $request)
    {
        try {
            // Get supported locales dynamically from config
            $supportedLocales = LanguageHelper::getSupportedLocales();

            // Validate request
            $validated = $request->validate([
                'locale' => [
                    'required',
                    'string',
                    Rule::in($supportedLocales),
                ],
            ]);

            $locale = $validated['locale'];

            // Double-check locale is supported
            if (!LanguageHelper::isSupported($locale)) {
                return response()->json([
                    'success' => false,
                    'message' => __('The selected language is not supported.'),
                    'locale' => App::getLocale(),
                ], 422);
            }

            // Store in session (always)
            Session::put('locale', $locale);
            Session::save(); // Force session save

            // Set cookie for persistence (especially for guests)
            $cookieName = config('languages.cookie.name', 'mindova_locale');
            $cookieLifetime = config('languages.cookie.lifetime', 525600); // 1 year default
            Cookie::queue($cookieName, $locale, $cookieLifetime);

            // Update user preference if authenticated
            if (auth()->check()) {
                try {
                    auth()->user()->update(['locale' => $locale]);
                    Log::info('User language preference updated', [
                        'user_id' => auth()->id(),
                        'locale' => $locale,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to update user locale in database', [
                        'user_id' => auth()->id(),
                        'locale' => $locale,
                        'error' => $e->getMessage(),
                    ]);
                    // Don't fail the request, session/cookie still work
                }
            }

            // Set app locale immediately for current request
            App::setLocale($locale);

            // Log successful language switch
            Log::info('Language switched successfully', [
                'locale' => $locale,
                'user_id' => auth()->id(),
                'is_rtl' => LanguageHelper::isRTL($locale),
            ]);

            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => __('Language changed successfully.'),
                'language_name' => LanguageHelper::getLanguageName($locale),
                'is_rtl' => LanguageHelper::isRTL($locale),
                'direction' => LanguageHelper::getDirection($locale),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Language switch validation failed', [
                'errors' => $e->errors(),
                'request_locale' => $request->input('locale'),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('Invalid language selection.'),
                'errors' => $e->errors(),
                'locale' => App::getLocale(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Language switch failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_locale' => $request->input('locale'),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('Failed to change language. Please try again.'),
                'locale' => App::getLocale(),
            ], 500);
        }
    }

    /**
     * Get current language information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function current()
    {
        $locale = App::getLocale();

        return response()->json([
            'locale' => $locale,
            'name' => LanguageHelper::getLanguageName($locale),
            'is_rtl' => LanguageHelper::isRTL($locale),
            'direction' => LanguageHelper::getDirection($locale),
            'supported_locales' => LanguageHelper::getSupportedLocales(),
            'language_names' => LanguageHelper::getLanguageNames(),
        ]);
    }
}
