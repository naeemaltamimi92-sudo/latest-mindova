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
            // GENERAL SETTINGS
            // ============================================
            [
                'key' => 'site_name',
                'value' => 'Mindova',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'The name of your platform',
                'is_public' => true,
            ],
            [
                'key' => 'site_tagline',
                'value' => 'AI-Powered Collaboration Platform',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Site Tagline',
                'description' => 'A short description or slogan for your platform',
                'is_public' => true,
            ],
            [
                'key' => 'contact_email',
                'value' => 'mindova.ai@gmail.com',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Contact Email',
                'description' => 'Primary contact email for the platform',
                'is_public' => true,
            ],
            [
                'key' => 'support_phone',
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Support Phone',
                'description' => 'Support phone number (optional)',
                'is_public' => true,
            ],

            // ============================================
            // USER & REGISTRATION SETTINGS
            // ============================================
            [
                'key' => 'email_verification_required',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'users',
                'label' => 'Email Verification Required',
                'description' => 'Require users to verify their email before accessing the platform',
                'is_public' => false,
            ],
            [
                'key' => 'profile_completion_required',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'users',
                'label' => 'Profile Completion Required',
                'description' => 'Require users to complete their profile before accessing features',
                'is_public' => false,
            ],
            [
                'key' => 'linkedin_auth_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'users',
                'label' => 'LinkedIn Authentication',
                'description' => 'Allow users to sign in with LinkedIn',
                'is_public' => true,
            ],
            [
                'key' => 'volunteer_registration_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'users',
                'label' => 'Contributor Registration',
                'description' => 'Allow new contributors to register',
                'is_public' => true,
            ],
            [
                'key' => 'company_registration_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'users',
                'label' => 'Company Registration',
                'description' => 'Allow new companies to register',
                'is_public' => true,
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'group' => 'users',
                'label' => 'Max Login Attempts',
                'description' => 'Maximum failed login attempts before lockout',
                'is_public' => false,
            ],
            [
                'key' => 'login_lockout_minutes',
                'value' => '15',
                'type' => 'integer',
                'group' => 'users',
                'label' => 'Login Lockout Duration',
                'description' => 'Minutes to lock user after max failed attempts',
                'is_public' => false,
            ],

            // ============================================
            // CHALLENGE SETTINGS
            // ============================================
            [
                'key' => 'challenges_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'challenges',
                'label' => 'Challenges Enabled',
                'description' => 'Enable the challenges feature on the platform',
                'is_public' => false,
            ],
            [
                'key' => 'challenge_approval_required',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'challenges',
                'label' => 'Challenge Approval Required',
                'description' => 'Require admin approval before challenges go live',
                'is_public' => false,
            ],
            [
                'key' => 'volunteer_challenges_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'challenges',
                'label' => 'Contributor Challenges',
                'description' => 'Allow contributors to submit their own challenges',
                'is_public' => false,
            ],
            [
                'key' => 'max_challenges_per_company',
                'value' => '10',
                'type' => 'integer',
                'group' => 'challenges',
                'label' => 'Max Challenges Per Company',
                'description' => 'Maximum active challenges a company can have (0 = unlimited)',
                'is_public' => false,
            ],
            [
                'key' => 'max_team_size',
                'value' => '10',
                'type' => 'integer',
                'group' => 'challenges',
                'label' => 'Max Team Size',
                'description' => 'Maximum number of contributors per team',
                'is_public' => false,
            ],
            [
                'key' => 'auto_decompose_tasks',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'challenges',
                'label' => 'Auto-Decompose Tasks',
                'description' => 'Automatically use AI to decompose challenges into tasks',
                'is_public' => false,
            ],

            // ============================================
            // AI FEATURES SETTINGS
            // ============================================
            [
                'key' => 'ai_features_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'ai',
                'label' => 'AI Features',
                'description' => 'Enable all AI-powered features on the platform',
                'is_public' => false,
            ],
            [
                'key' => 'ai_cv_parsing_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'ai',
                'label' => 'AI CV Parsing',
                'description' => 'Use AI to extract skills and experience from uploaded CVs',
                'is_public' => false,
            ],
            [
                'key' => 'ai_team_matching_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'ai',
                'label' => 'AI Team Matching',
                'description' => 'Use AI to match contributors to challenges and form teams',
                'is_public' => false,
            ],
            [
                'key' => 'ai_task_decomposition_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'ai',
                'label' => 'AI Task Decomposition',
                'description' => 'Use AI to break down challenges into manageable tasks',
                'is_public' => false,
            ],
            [
                'key' => 'ai_challenge_analysis_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'ai',
                'label' => 'AI Challenge Analysis',
                'description' => 'Use AI to analyze and categorize challenges',
                'is_public' => false,
            ],
            [
                'key' => 'openai_daily_limit',
                'value' => '1000',
                'type' => 'integer',
                'group' => 'ai',
                'label' => 'Daily AI Request Limit',
                'description' => 'Maximum AI API requests per day (0 = unlimited)',
                'is_public' => false,
            ],

            // ============================================
            // NOTIFICATION SETTINGS
            // ============================================
            [
                'key' => 'email_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Email Notifications',
                'description' => 'Enable email notifications for users',
                'is_public' => false,
            ],
            [
                'key' => 'in_app_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'In-App Notifications',
                'description' => 'Enable in-app notification system',
                'is_public' => false,
            ],
            [
                'key' => 'admin_notification_email',
                'value' => '',
                'type' => 'string',
                'group' => 'notifications',
                'label' => 'Admin Notification Email',
                'description' => 'Email to receive admin notifications (leave empty to use contact email)',
                'is_public' => false,
            ],
            [
                'key' => 'notify_admin_new_user',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'New User Notifications',
                'description' => 'Notify admin when new users register',
                'is_public' => false,
            ],
            [
                'key' => 'notify_admin_new_challenge',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'New Challenge Notifications',
                'description' => 'Notify admin when new challenges are submitted',
                'is_public' => false,
            ],
            [
                'key' => 'notify_admin_new_company',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'New Company Notifications',
                'description' => 'Notify admin when new companies register',
                'is_public' => false,
            ],

            // ============================================
            // COMMUNITY SETTINGS
            // ============================================
            [
                'key' => 'community_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'community',
                'label' => 'Community Features',
                'description' => 'Enable community features (comments, voting, ideas)',
                'is_public' => false,
            ],
            [
                'key' => 'leaderboard_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'community',
                'label' => 'Leaderboard',
                'description' => 'Show contributor leaderboard',
                'is_public' => false,
            ],
            [
                'key' => 'public_profiles_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'community',
                'label' => 'Public Profiles',
                'description' => 'Allow public viewing of user profiles',
                'is_public' => false,
            ],
            [
                'key' => 'commenting_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'community',
                'label' => 'Comments',
                'description' => 'Allow users to comment on challenges and ideas',
                'is_public' => false,
            ],
            [
                'key' => 'voting_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'community',
                'label' => 'Voting',
                'description' => 'Allow users to vote on ideas and comments',
                'is_public' => false,
            ],

            // ============================================
            // CERTIFICATE SETTINGS
            // ============================================
            [
                'key' => 'certificates_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'certificates',
                'label' => 'Certificates',
                'description' => 'Enable certificate generation for completed challenges',
                'is_public' => false,
            ],
            [
                'key' => 'certificate_auto_issue',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'certificates',
                'label' => 'Auto-Issue Certificates',
                'description' => 'Automatically issue certificates when challenges are completed',
                'is_public' => false,
            ],
            [
                'key' => 'certificate_expiry_days',
                'value' => '0',
                'type' => 'integer',
                'group' => 'certificates',
                'label' => 'Certificate Expiry (Days)',
                'description' => 'Days until certificates expire (0 = never)',
                'is_public' => false,
            ],

            // ============================================
            // API SETTINGS
            // ============================================
            [
                'key' => 'api_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'api',
                'label' => 'API Access',
                'description' => 'Enable API access for external integrations',
                'is_public' => false,
            ],
            [
                'key' => 'api_rate_limit',
                'value' => '60',
                'type' => 'integer',
                'group' => 'api',
                'label' => 'API Rate Limit',
                'description' => 'Maximum API requests per minute per user',
                'is_public' => false,
            ],
            [
                'key' => 'api_documentation_public',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'api',
                'label' => 'Public API Documentation',
                'description' => 'Make API documentation publicly accessible',
                'is_public' => true,
            ],

            // ============================================
            // SECURITY SETTINGS
            // ============================================
            [
                'key' => 'force_https',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Force HTTPS',
                'description' => 'Redirect all HTTP requests to HTTPS',
                'is_public' => false,
            ],
            [
                'key' => 'session_lifetime_minutes',
                'value' => '120',
                'type' => 'integer',
                'group' => 'security',
                'label' => 'Session Lifetime (Minutes)',
                'description' => 'How long user sessions remain active',
                'is_public' => false,
            ],
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'integer',
                'group' => 'security',
                'label' => 'Minimum Password Length',
                'description' => 'Minimum required password length',
                'is_public' => false,
            ],
            [
                'key' => 'nda_required_general',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'General NDA Required',
                'description' => 'Require contributors to sign general NDA',
                'is_public' => false,
            ],
            [
                'key' => 'nda_required_challenge',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'security',
                'label' => 'Challenge NDA Required',
                'description' => 'Require challenge-specific NDA for sensitive challenges',
                'is_public' => false,
            ],

            // ============================================
            // FILE UPLOAD SETTINGS
            // ============================================
            [
                'key' => 'max_file_upload_size_mb',
                'value' => '10',
                'type' => 'integer',
                'group' => 'uploads',
                'label' => 'Max File Upload Size (MB)',
                'description' => 'Maximum file upload size in megabytes',
                'is_public' => false,
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip',
                'type' => 'string',
                'group' => 'uploads',
                'label' => 'Allowed File Types',
                'description' => 'Comma-separated list of allowed file extensions',
                'is_public' => false,
            ],
            [
                'key' => 'max_cv_size_mb',
                'value' => '5',
                'type' => 'integer',
                'group' => 'uploads',
                'label' => 'Max CV Size (MB)',
                'description' => 'Maximum CV/resume file size in megabytes',
                'is_public' => false,
            ],
            [
                'key' => 'max_attachments_per_challenge',
                'value' => '10',
                'type' => 'integer',
                'group' => 'uploads',
                'label' => 'Max Attachments Per Challenge',
                'description' => 'Maximum number of attachments per challenge',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            // Check if setting already exists
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
            'site_name', 'site_tagline', 'contact_email', 'support_phone',
            'email_verification_required', 'profile_completion_required', 'linkedin_auth_enabled',
            'volunteer_registration_enabled', 'company_registration_enabled', 'max_login_attempts', 'login_lockout_minutes',
            'challenges_enabled', 'challenge_approval_required', 'volunteer_challenges_enabled',
            'max_challenges_per_company', 'max_team_size', 'auto_decompose_tasks',
            'ai_features_enabled', 'ai_cv_parsing_enabled', 'ai_team_matching_enabled',
            'ai_task_decomposition_enabled', 'ai_challenge_analysis_enabled', 'openai_daily_limit',
            'email_notifications_enabled', 'in_app_notifications_enabled', 'admin_notification_email',
            'notify_admin_new_user', 'notify_admin_new_challenge', 'notify_admin_new_company',
            'community_enabled', 'leaderboard_enabled', 'public_profiles_enabled', 'commenting_enabled', 'voting_enabled',
            'certificates_enabled', 'certificate_auto_issue', 'certificate_expiry_days',
            'api_enabled', 'api_rate_limit', 'api_documentation_public',
            'force_https', 'session_lifetime_minutes', 'password_min_length', 'nda_required_general', 'nda_required_challenge',
            'max_file_upload_size_mb', 'allowed_file_types', 'max_cv_size_mb', 'max_attachments_per_challenge',
        ];

        DB::table('site_settings')->whereIn('key', $keys)->delete();
    }
};
