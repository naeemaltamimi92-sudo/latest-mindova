<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SiteSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind site settings to the container for easy access
        $this->app->singleton('site.settings', function () {
            if (!Schema::hasTable('site_settings')) {
                return [];
            }
            return SiteSetting::getAllSettings();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only share settings if database is available
        if (!Schema::hasTable('site_settings')) {
            return;
        }

        try {
            // Share all settings with all views
            View::composer('*', function ($view) {
                $settings = app('site.settings');

                // Share individual commonly used settings
                $view->with('siteSettings', $settings);

                // Feature flags
                $view->with('darkModeEnabled', $settings['feature_dark_mode'] ?? false);
                $view->with('maintenanceModeEnabled', $settings['maintenance_mode'] ?? false);
                $view->with('cookieConsentEnabled', $settings['cookie_consent_enabled'] ?? false);
                $view->with('registrationEnabled', $settings['registration_enabled'] ?? true);

                // Branding
                $view->with('brandPrimaryColor', $settings['brand_primary_color'] ?? '#6366f1');
                $view->with('brandSecondaryColor', $settings['brand_secondary_color'] ?? '#8b5cf6');
                $view->with('brandLogoUrl', $settings['brand_logo_url'] ?? '');
                $view->with('brandFaviconUrl', $settings['brand_favicon_url'] ?? '');
                $view->with('brandFooterText', $settings['brand_footer_text'] ?? '');

                // Site info
                $view->with('siteName', $settings['site_name'] ?? config('app.name', 'Mindova'));
                $view->with('siteTagline', $settings['site_tagline'] ?? '');

                // SEO
                $view->with('seoMetaTitle', $settings['seo_meta_title'] ?? '');
                $view->with('seoMetaDescription', $settings['seo_meta_description'] ?? '');
                $view->with('seoMetaKeywords', $settings['seo_meta_keywords'] ?? '');

                // Analytics
                $view->with('googleAnalyticsId', $settings['analytics_google_id'] ?? '');
                $view->with('facebookPixelId', $settings['analytics_facebook_pixel'] ?? '');
                $view->with('hotjarId', $settings['analytics_hotjar_id'] ?? '');
                $view->with('customHeadScripts', $settings['analytics_custom_head_scripts'] ?? '');
                $view->with('customBodyScripts', $settings['analytics_custom_body_scripts'] ?? '');

                // Custom code
                $view->with('customCss', $settings['custom_css'] ?? '');
                $view->with('customJs', $settings['custom_js'] ?? '');

                // Cookie consent
                $view->with('cookieConsentText', $settings['cookie_consent_text'] ?? 'We use cookies to enhance your experience.');

                // Social media
                $view->with('socialFacebookUrl', $settings['social_facebook_url'] ?? '');
                $view->with('socialTwitterUrl', $settings['social_twitter_url'] ?? '');
                $view->with('socialLinkedinUrl', $settings['social_linkedin_url'] ?? '');
                $view->with('socialInstagramUrl', $settings['social_instagram_url'] ?? '');
                $view->with('socialYoutubeUrl', $settings['social_youtube_url'] ?? '');
            });
        } catch (\Exception $e) {
            // Silently fail if database is not ready
        }
    }
}
