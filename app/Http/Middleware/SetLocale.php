<?php

namespace App\Http\Middleware;

use App\Helpers\LanguageHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * Language Priority:
     * 1. URL Query Parameter (?lang=ar)
     * 2. Session
     * 3. User preference (if authenticated)
     * 4. Cookie
     * 5. Browser Accept-Language header
     * 6. Default (en)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->determineLocale($request);

        // Set the application locale
        App::setLocale($locale);

        // Store in session for subsequent requests
        Session::put('locale', $locale);

        // Share locale info with all views
        view()->share('currentLocale', $locale);
        view()->share('isRTL', LanguageHelper::isRTL($locale));
        view()->share('textDirection', LanguageHelper::getDirection($locale));
        view()->share('availableLocales', LanguageHelper::getLanguageNames());

        return $next($request);
    }

    /**
     * Determine the locale to use.
     */
    protected function determineLocale(Request $request): string
    {
        $supportedLocales = LanguageHelper::getSupportedLocales();
        $defaultLocale = LanguageHelper::getDefaultLocale();

        // 1. Check URL query parameter
        if ($request->has('lang')) {
            $locale = $request->query('lang');
            if (in_array($locale, $supportedLocales)) {
                // Store in session when changed via URL
                Session::put('locale', $locale);
                return $locale;
            }
        }

        // 2. Check session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if (in_array($locale, $supportedLocales)) {
                return $locale;
            }
        }

        // 3. Check authenticated user preference
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
            if (in_array($locale, $supportedLocales)) {
                return $locale;
            }
        }

        // 4. Check cookie
        $cookieName = config('languages.cookie.name', 'mindova_locale');
        if ($cookieLocale = Cookie::get($cookieName)) {
            if (in_array($cookieLocale, $supportedLocales)) {
                return $cookieLocale;
            }
        }

        // 5. Try browser language detection
        if (config('languages.browser_detection', true)) {
            $browserLocale = LanguageHelper::detectBrowserLocale($request->header('Accept-Language'));
            if ($browserLocale && in_array($browserLocale, $supportedLocales)) {
                return $browserLocale;
            }
        }

        // 6. Fall back to default
        return $defaultLocale;
    }
}
