<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            // ============================================
            // BRANDING SETTINGS
            // ============================================
            [
                'key' => 'brand_primary_color',
                'value' => '#6366f1',
                'type' => 'color',
                'group' => 'branding',
                'label' => 'Primary Brand Color',
                'description' => 'Main color used throughout the platform',
                'is_public' => true,
            ],
            [
                'key' => 'brand_secondary_color',
                'value' => '#8b5cf6',
                'type' => 'color',
                'group' => 'branding',
                'label' => 'Secondary Brand Color',
                'description' => 'Secondary accent color',
                'is_public' => true,
            ],
            [
                'key' => 'brand_logo_url',
                'value' => '',
                'type' => 'string',
                'group' => 'branding',
                'label' => 'Logo URL',
                'description' => 'URL to your platform logo image',
                'is_public' => true,
            ],
            [
                'key' => 'brand_favicon_url',
                'value' => '',
                'type' => 'string',
                'group' => 'branding',
                'label' => 'Favicon URL',
                'description' => 'URL to your platform favicon',
                'is_public' => true,
            ],
            [
                'key' => 'brand_footer_text',
                'value' => '© 2025 Mindova. All rights reserved.',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Footer Text',
                'description' => 'Copyright text shown in footer',
                'is_public' => true,
            ],

            // ============================================
            // EMAIL / SMTP SETTINGS
            // ============================================
            [
                'key' => 'email_from_name',
                'value' => 'Mindova',
                'type' => 'string',
                'group' => 'email',
                'label' => 'From Name',
                'description' => 'Name that appears in sent emails',
                'is_public' => false,
            ],
            [
                'key' => 'email_from_address',
                'value' => 'noreply@mindova.com',
                'type' => 'string',
                'group' => 'email',
                'label' => 'From Email Address',
                'description' => 'Email address used as sender',
                'is_public' => false,
            ],
            [
                'key' => 'email_reply_to',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Reply-To Address',
                'description' => 'Email address for replies (optional)',
                'is_public' => false,
            ],
            [
                'key' => 'email_digest_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'label' => 'Email Digests',
                'description' => 'Send daily/weekly email digest summaries',
                'is_public' => false,
            ],
            [
                'key' => 'email_digest_frequency',
                'value' => 'weekly',
                'type' => 'select',
                'group' => 'email',
                'label' => 'Digest Frequency',
                'description' => 'How often to send email digests',
                'is_public' => false,
                'options' => 'daily,weekly,monthly',
            ],

            // ============================================
            // SOCIAL MEDIA SETTINGS
            // ============================================
            [
                'key' => 'social_facebook_url',
                'value' => '',
                'type' => 'string',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Link to your Facebook page',
                'is_public' => true,
            ],
            [
                'key' => 'social_twitter_url',
                'value' => '',
                'type' => 'string',
                'group' => 'social',
                'label' => 'Twitter/X URL',
                'description' => 'Link to your Twitter/X profile',
                'is_public' => true,
            ],
            [
                'key' => 'social_linkedin_url',
                'value' => '',
                'type' => 'string',
                'group' => 'social',
                'label' => 'LinkedIn URL',
                'description' => 'Link to your LinkedIn company page',
                'is_public' => true,
            ],
            [
                'key' => 'social_instagram_url',
                'value' => '',
                'type' => 'string',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Link to your Instagram profile',
                'is_public' => true,
            ],
            [
                'key' => 'social_youtube_url',
                'value' => '',
                'type' => 'string',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'Link to your YouTube channel',
                'is_public' => true,
            ],
            [
                'key' => 'social_sharing_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'social',
                'label' => 'Social Sharing',
                'description' => 'Allow sharing challenges and ideas on social media',
                'is_public' => true,
            ],

            // ============================================
            // SEO SETTINGS
            // ============================================
            [
                'key' => 'seo_meta_title',
                'value' => 'Mindova - AI-Powered Collaboration Platform',
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Meta Title',
                'description' => 'Default meta title for SEO',
                'is_public' => true,
            ],
            [
                'key' => 'seo_meta_description',
                'value' => 'Connect, collaborate, and innovate with Mindova - the AI-powered platform that matches talents with challenges.',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Meta Description',
                'description' => 'Default meta description for search engines',
                'is_public' => true,
            ],
            [
                'key' => 'seo_meta_keywords',
                'value' => 'collaboration, innovation, AI, volunteers, challenges, teamwork',
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Meta Keywords',
                'description' => 'Comma-separated keywords for SEO',
                'is_public' => true,
            ],
            [
                'key' => 'seo_og_image_url',
                'value' => '',
                'type' => 'string',
                'group' => 'seo',
                'label' => 'Social Share Image',
                'description' => 'Default image when sharing on social media',
                'is_public' => true,
            ],
            [
                'key' => 'seo_robots_txt',
                'value' => 'User-agent: *\nAllow: /',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Robots.txt Content',
                'description' => 'Content for robots.txt file',
                'is_public' => false,
            ],
            [
                'key' => 'seo_sitemap_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'seo',
                'label' => 'Sitemap Generation',
                'description' => 'Automatically generate XML sitemap',
                'is_public' => false,
            ],

            // ============================================
            // ANALYTICS SETTINGS
            // ============================================
            [
                'key' => 'analytics_google_id',
                'value' => '',
                'type' => 'string',
                'group' => 'analytics',
                'label' => 'Google Analytics ID',
                'description' => 'Google Analytics tracking ID (GA4)',
                'is_public' => false,
            ],
            [
                'key' => 'analytics_facebook_pixel',
                'value' => '',
                'type' => 'string',
                'group' => 'analytics',
                'label' => 'Facebook Pixel ID',
                'description' => 'Facebook Pixel tracking ID',
                'is_public' => false,
            ],
            [
                'key' => 'analytics_hotjar_id',
                'value' => '',
                'type' => 'string',
                'group' => 'analytics',
                'label' => 'Hotjar Site ID',
                'description' => 'Hotjar site tracking ID',
                'is_public' => false,
            ],
            [
                'key' => 'analytics_custom_head_scripts',
                'value' => '',
                'type' => 'text',
                'group' => 'analytics',
                'label' => 'Custom Head Scripts',
                'description' => 'Custom scripts to inject in the head section',
                'is_public' => false,
            ],
            [
                'key' => 'analytics_custom_body_scripts',
                'value' => '',
                'type' => 'text',
                'group' => 'analytics',
                'label' => 'Custom Body Scripts',
                'description' => 'Custom scripts to inject before body close',
                'is_public' => false,
            ],

            // ============================================
            // GAMIFICATION SETTINGS
            // ============================================
            [
                'key' => 'gamification_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'gamification',
                'label' => 'Gamification',
                'description' => 'Enable gamification features (points, badges, levels)',
                'is_public' => false,
            ],
            [
                'key' => 'points_per_task_completion',
                'value' => '100',
                'type' => 'integer',
                'group' => 'gamification',
                'label' => 'Points Per Task',
                'description' => 'Points awarded for completing a task',
                'is_public' => false,
            ],
            [
                'key' => 'points_per_challenge_completion',
                'value' => '500',
                'type' => 'integer',
                'group' => 'gamification',
                'label' => 'Points Per Challenge',
                'description' => 'Bonus points for completing an entire challenge',
                'is_public' => false,
            ],
            [
                'key' => 'points_per_idea_approved',
                'value' => '50',
                'type' => 'integer',
                'group' => 'gamification',
                'label' => 'Points Per Idea',
                'description' => 'Points for having an idea approved',
                'is_public' => false,
            ],
            [
                'key' => 'points_per_vote_received',
                'value' => '5',
                'type' => 'integer',
                'group' => 'gamification',
                'label' => 'Points Per Vote',
                'description' => 'Points for receiving a vote on idea/comment',
                'is_public' => false,
            ],
            [
                'key' => 'badges_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'gamification',
                'label' => 'Badges',
                'description' => 'Enable achievement badges for users',
                'is_public' => false,
            ],
            [
                'key' => 'levels_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'gamification',
                'label' => 'User Levels',
                'description' => 'Enable leveling system based on points',
                'is_public' => false,
            ],
            [
                'key' => 'streaks_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'gamification',
                'label' => 'Activity Streaks',
                'description' => 'Track daily activity streaks',
                'is_public' => false,
            ],

            // ============================================
            // LOCALIZATION SETTINGS
            // ============================================
            [
                'key' => 'default_language',
                'value' => 'en',
                'type' => 'select',
                'group' => 'localization',
                'label' => 'Default Language',
                'description' => 'Default platform language',
                'is_public' => true,
                'options' => 'en,ar',
            ],
            [
                'key' => 'available_languages',
                'value' => 'en,ar',
                'type' => 'string',
                'group' => 'localization',
                'label' => 'Available Languages',
                'description' => 'Comma-separated list of available languages',
                'is_public' => true,
            ],
            [
                'key' => 'default_timezone',
                'value' => 'UTC',
                'type' => 'string',
                'group' => 'localization',
                'label' => 'Default Timezone',
                'description' => 'Default timezone for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'select',
                'group' => 'localization',
                'label' => 'Date Format',
                'description' => 'Default date display format',
                'is_public' => true,
                'options' => 'Y-m-d,d/m/Y,m/d/Y,d-m-Y',
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'select',
                'group' => 'localization',
                'label' => 'Time Format',
                'description' => 'Default time display format',
                'is_public' => true,
                'options' => 'H:i,h:i A,H:i:s',
            ],
            [
                'key' => 'rtl_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'localization',
                'label' => 'RTL Support',
                'description' => 'Enable right-to-left layout for Arabic',
                'is_public' => true,
            ],

            // ============================================
            // PERFORMANCE SETTINGS
            // ============================================
            [
                'key' => 'cache_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'performance',
                'label' => 'Page Caching',
                'description' => 'Enable page-level caching for performance',
                'is_public' => false,
            ],
            [
                'key' => 'cache_ttl_minutes',
                'value' => '60',
                'type' => 'integer',
                'group' => 'performance',
                'label' => 'Cache TTL (Minutes)',
                'description' => 'How long to cache pages in minutes',
                'is_public' => false,
            ],
            [
                'key' => 'image_optimization_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'performance',
                'label' => 'Image Optimization',
                'description' => 'Automatically optimize uploaded images',
                'is_public' => false,
            ],
            [
                'key' => 'lazy_loading_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'performance',
                'label' => 'Lazy Loading',
                'description' => 'Enable lazy loading for images',
                'is_public' => false,
            ],
            [
                'key' => 'minify_assets',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'performance',
                'label' => 'Minify Assets',
                'description' => 'Minify CSS and JavaScript files',
                'is_public' => false,
            ],
            [
                'key' => 'cdn_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'performance',
                'label' => 'CDN',
                'description' => 'Use CDN for static assets',
                'is_public' => false,
            ],
            [
                'key' => 'cdn_url',
                'value' => '',
                'type' => 'string',
                'group' => 'performance',
                'label' => 'CDN URL',
                'description' => 'CDN base URL for assets',
                'is_public' => false,
            ],

            // ============================================
            // LEGAL / COMPLIANCE SETTINGS
            // ============================================
            [
                'key' => 'gdpr_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'legal',
                'label' => 'GDPR Compliance',
                'description' => 'Enable GDPR compliance features',
                'is_public' => false,
            ],
            [
                'key' => 'cookie_consent_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'legal',
                'label' => 'Cookie Consent',
                'description' => 'Show cookie consent banner',
                'is_public' => true,
            ],
            [
                'key' => 'cookie_consent_text',
                'value' => 'We use cookies to enhance your experience. By continuing, you agree to our cookie policy.',
                'type' => 'text',
                'group' => 'legal',
                'label' => 'Cookie Consent Text',
                'description' => 'Text shown in cookie consent banner',
                'is_public' => true,
            ],
            [
                'key' => 'terms_updated_at',
                'value' => '',
                'type' => 'string',
                'group' => 'legal',
                'label' => 'Terms Last Updated',
                'description' => 'Date when terms were last updated',
                'is_public' => true,
            ],
            [
                'key' => 'privacy_updated_at',
                'value' => '',
                'type' => 'string',
                'group' => 'legal',
                'label' => 'Privacy Last Updated',
                'description' => 'Date when privacy policy was last updated',
                'is_public' => true,
            ],
            [
                'key' => 'data_retention_days',
                'value' => '365',
                'type' => 'integer',
                'group' => 'legal',
                'label' => 'Data Retention (Days)',
                'description' => 'How long to retain user data after deletion',
                'is_public' => false,
            ],

            // ============================================
            // ADVANCED / DEVELOPER SETTINGS
            // ============================================
            [
                'key' => 'debug_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'developer',
                'label' => 'Debug Mode',
                'description' => 'Enable debug mode (not recommended for production)',
                'is_public' => false,
            ],
            [
                'key' => 'log_level',
                'value' => 'error',
                'type' => 'select',
                'group' => 'developer',
                'label' => 'Log Level',
                'description' => 'Minimum log level to record',
                'is_public' => false,
                'options' => 'debug,info,notice,warning,error,critical',
            ],
            [
                'key' => 'api_debug_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'developer',
                'label' => 'API Debug Mode',
                'description' => 'Include debug information in API responses',
                'is_public' => false,
            ],
            [
                'key' => 'webhook_url',
                'value' => '',
                'type' => 'string',
                'group' => 'developer',
                'label' => 'Webhook URL',
                'description' => 'URL to send webhook notifications',
                'is_public' => false,
            ],
            [
                'key' => 'webhook_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'developer',
                'label' => 'Webhook Secret',
                'description' => 'Secret key for webhook signature verification',
                'is_public' => false,
            ],
            [
                'key' => 'custom_css',
                'value' => '',
                'type' => 'text',
                'group' => 'developer',
                'label' => 'Custom CSS',
                'description' => 'Custom CSS styles to apply globally',
                'is_public' => false,
            ],
            [
                'key' => 'custom_js',
                'value' => '',
                'type' => 'text',
                'group' => 'developer',
                'label' => 'Custom JavaScript',
                'description' => 'Custom JavaScript to execute globally',
                'is_public' => false,
            ],

            // ============================================
            // ONBOARDING SETTINGS
            // ============================================
            [
                'key' => 'onboarding_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'onboarding',
                'label' => 'User Onboarding',
                'description' => 'Show onboarding tour for new users',
                'is_public' => false,
            ],
            [
                'key' => 'welcome_email_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'onboarding',
                'label' => 'Welcome Email',
                'description' => 'Send welcome email to new users',
                'is_public' => false,
            ],
            [
                'key' => 'welcome_message',
                'value' => 'Welcome to Mindova! We are excited to have you join our community of innovators.',
                'type' => 'text',
                'group' => 'onboarding',
                'label' => 'Welcome Message',
                'description' => 'Welcome message shown to new users',
                'is_public' => true,
            ],
            [
                'key' => 'getting_started_video_url',
                'value' => '',
                'type' => 'string',
                'group' => 'onboarding',
                'label' => 'Getting Started Video',
                'description' => 'URL to getting started video (YouTube/Vimeo)',
                'is_public' => true,
            ],
            [
                'key' => 'show_tips_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'onboarding',
                'label' => 'Contextual Tips',
                'description' => 'Show helpful tips throughout the platform',
                'is_public' => false,
            ],

            // ============================================
            // FEATURE FLAGS
            // ============================================
            [
                'key' => 'feature_teams',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Teams Feature',
                'description' => 'Enable team creation and management',
                'is_public' => false,
            ],
            [
                'key' => 'feature_ideas',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Ideas Feature',
                'description' => 'Enable idea submission for challenges',
                'is_public' => false,
            ],
            [
                'key' => 'feature_nda',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'NDA Feature',
                'description' => 'Enable NDA signing requirements',
                'is_public' => false,
            ],
            [
                'key' => 'feature_cv_upload',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'CV Upload',
                'description' => 'Allow volunteers to upload CVs',
                'is_public' => false,
            ],
            [
                'key' => 'feature_analytics_dashboard',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Analytics Dashboard',
                'description' => 'Show analytics dashboard to companies',
                'is_public' => false,
            ],
            [
                'key' => 'feature_dark_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Dark Mode',
                'description' => 'Enable dark mode option for users',
                'is_public' => true,
            ],
            [
                'key' => 'feature_export_data',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Data Export',
                'description' => 'Allow users to export their data',
                'is_public' => false,
            ],
        ];

        // Add options column if it doesn't exist
        if (!Schema::hasColumn('site_settings', 'options')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->text('options')->nullable()->after('description');
            });
        }

        foreach ($settings as $setting) {
            $exists = DB::table('site_settings')->where('key', $setting['key'])->exists();

            if (!$exists) {
                DB::table('site_settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            // Branding
            'brand_primary_color', 'brand_secondary_color', 'brand_logo_url', 'brand_favicon_url', 'brand_footer_text',
            // Email
            'email_from_name', 'email_from_address', 'email_reply_to', 'email_digest_enabled', 'email_digest_frequency',
            // Social
            'social_facebook_url', 'social_twitter_url', 'social_linkedin_url', 'social_instagram_url', 'social_youtube_url', 'social_sharing_enabled',
            // SEO
            'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_og_image_url', 'seo_robots_txt', 'seo_sitemap_enabled',
            // Analytics
            'analytics_google_id', 'analytics_facebook_pixel', 'analytics_hotjar_id', 'analytics_custom_head_scripts', 'analytics_custom_body_scripts',
            // Gamification
            'gamification_enabled', 'points_per_task_completion', 'points_per_challenge_completion', 'points_per_idea_approved', 'points_per_vote_received', 'badges_enabled', 'levels_enabled', 'streaks_enabled',
            // Localization
            'default_language', 'available_languages', 'default_timezone', 'date_format', 'time_format', 'rtl_enabled',
            // Performance
            'cache_enabled', 'cache_ttl_minutes', 'image_optimization_enabled', 'lazy_loading_enabled', 'minify_assets', 'cdn_enabled', 'cdn_url',
            // Legal
            'gdpr_enabled', 'cookie_consent_enabled', 'cookie_consent_text', 'terms_updated_at', 'privacy_updated_at', 'data_retention_days',
            // Developer
            'debug_mode', 'log_level', 'api_debug_enabled', 'webhook_url', 'webhook_secret', 'custom_css', 'custom_js',
            // Onboarding
            'onboarding_enabled', 'welcome_email_enabled', 'welcome_message', 'getting_started_video_url', 'show_tips_enabled',
            // Features
            'feature_teams', 'feature_ideas', 'feature_nda', 'feature_cv_upload', 'feature_analytics_dashboard', 'feature_dark_mode', 'feature_export_data',
        ];

        DB::table('site_settings')->whereIn('key', $keys)->delete();

        // Note: Keeping options column as it might be used by other settings
    }
};
