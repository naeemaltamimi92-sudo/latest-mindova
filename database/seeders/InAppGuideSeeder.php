<?php

namespace Database\Seeders;

use App\Models\InAppGuide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InAppGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            [
                'page_identifier' => 'dashboard',
                'page_title' => 'Dashboard',
                'what_is_this' => 'This is your main dashboard where you can see an overview of all your activities, statistics, and quick access to important features.',
                'what_to_do' => [
                    'Review the summary cards at the top for key metrics',
                    'Check recent activities in the timeline',
                    'Use quick action buttons to perform common tasks',
                    'Explore the charts to understand trends'
                ],
                'next_step' => 'Navigate to specific sections using the sidebar menu to dive deeper into features you need.',
                'ui_highlights' => ['.stats-card', '.recent-activities', '.quick-actions'],
                'is_active' => true,
                'display_order' => 1
            ],
            [
                'page_identifier' => 'profile',
                'page_title' => 'Profile Settings',
                'what_is_this' => 'Manage your personal information, preferences, and account settings in one place.',
                'what_to_do' => [
                    'Update your profile picture and basic information',
                    'Set your language and timezone preferences',
                    'Configure notification settings',
                    'Review and update your security settings'
                ],
                'next_step' => 'After updating your profile, changes will be applied across the entire platform immediately.',
                'ui_highlights' => ['.profile-photo', '.personal-info', '.preferences'],
                'is_active' => true,
                'display_order' => 2
            ],
            [
                'page_identifier' => 'users.index',
                'page_title' => 'Users Management',
                'what_is_this' => 'View, manage, and organize all users in the system. You can add new users, edit existing ones, or manage their permissions.',
                'what_to_do' => [
                    'Use the search bar to find specific users',
                    'Filter users by role, status, or department',
                    'Click on a user to view detailed information',
                    'Use the "Add User" button to create new accounts'
                ],
                'next_step' => 'Click on any user to view their full profile and manage their permissions.',
                'ui_highlights' => ['.search-bar', '.filter-buttons', '.add-user-btn'],
                'is_active' => true,
                'display_order' => 3
            ],
            [
                'page_identifier' => 'settings',
                'page_title' => 'System Settings',
                'what_is_this' => 'Configure global system settings, preferences, and integrations that affect the entire application.',
                'what_to_do' => [
                    'Review each settings category on the left sidebar',
                    'Make changes to configurations as needed',
                    'Test integrations before saving',
                    'Click "Save Changes" to apply your settings'
                ],
                'next_step' => 'Some settings may require system restart. You will be notified if any action is needed.',
                'ui_highlights' => ['.settings-sidebar', '.save-button'],
                'is_active' => true,
                'display_order' => 4
            ],
            [
                'page_identifier' => 'reports',
                'page_title' => 'Reports & Analytics',
                'what_is_this' => 'Generate comprehensive reports and view analytics to gain insights into your data.',
                'what_to_do' => [
                    'Select the type of report you want to generate',
                    'Choose date range and filters',
                    'Click "Generate Report" to create the report',
                    'Export reports in PDF, Excel, or CSV format'
                ],
                'next_step' => 'Generated reports can be scheduled for automatic delivery or shared with team members.',
                'ui_highlights' => ['.report-type', '.date-range', '.generate-btn'],
                'is_active' => true,
                'display_order' => 5
            ],
            [
                'page_identifier' => 'notifications',
                'page_title' => 'Notifications Center',
                'what_is_this' => 'View all your system notifications, alerts, and messages in one centralized location.',
                'what_to_do' => [
                    'Review unread notifications at the top',
                    'Click on a notification to view details',
                    'Mark notifications as read or delete them',
                    'Use filters to find specific notification types'
                ],
                'next_step' => 'Configure notification preferences in your profile settings to control what you receive.',
                'ui_highlights' => ['.notification-list', '.filter-tabs'],
                'is_active' => true,
                'display_order' => 6
            ],
            [
                'page_identifier' => 'cv-analysis',
                'page_title' => 'CV Analysis',
                'what_is_this' => 'Upload and analyze CVs/resumes using AI-powered tools to extract key information and insights.',
                'what_to_do' => [
                    'Click "Upload CV" to select a resume file',
                    'Wait for the AI analysis to complete',
                    'Review extracted information and insights',
                    'Export or save the analysis results'
                ],
                'next_step' => 'Analyzed CVs are automatically saved and can be accessed later from the CV library.',
                'ui_highlights' => ['.upload-zone', '.analysis-results'],
                'is_active' => true,
                'display_order' => 7
            ],
            [
                'page_identifier' => 'api-documentation',
                'page_title' => 'API Documentation',
                'what_is_this' => 'Complete reference for the API endpoints, authentication, and integration guides.',
                'what_to_do' => [
                    'Browse available endpoints in the left menu',
                    'Read authentication requirements',
                    'Test endpoints using the interactive console',
                    'Copy code examples in your preferred language'
                ],
                'next_step' => 'Generate API keys from your profile settings to start making API requests.',
                'ui_highlights' => ['.endpoint-list', '.try-it-out'],
                'is_active' => true,
                'display_order' => 8
            ],
            [
                'page_identifier' => 'support',
                'page_title' => 'Support & Help',
                'what_is_this' => 'Get help, submit support tickets, or access the knowledge base to resolve issues.',
                'what_to_do' => [
                    'Search the knowledge base for common questions',
                    'View video tutorials for step-by-step guides',
                    'Submit a support ticket if you need assistance',
                    'Check ticket status in your support history'
                ],
                'next_step' => 'Our support team typically responds within 24 hours. You will receive email notifications.',
                'ui_highlights' => ['.search-kb', '.submit-ticket'],
                'is_active' => true,
                'display_order' => 9
            ],
            [
                'page_identifier' => 'integrations',
                'page_title' => 'Third-Party Integrations',
                'what_is_this' => 'Connect external services and tools to enhance functionality and automate workflows.',
                'what_to_do' => [
                    'Browse available integrations',
                    'Click "Connect" on the integration you want',
                    'Follow authentication steps for each service',
                    'Configure integration settings and permissions'
                ],
                'next_step' => 'Once connected, integrations will sync data automatically. You can disconnect anytime.',
                'ui_highlights' => ['.integration-card', '.connect-btn'],
                'is_active' => true,
                'display_order' => 10
            ],
            [
                'page_identifier' => 'security',
                'page_title' => 'Security Settings',
                'what_is_this' => 'Manage your account security, two-factor authentication, and active sessions.',
                'what_to_do' => [
                    'Enable two-factor authentication for extra security',
                    'Review active sessions and devices',
                    'Update your password regularly',
                    'Review security logs for suspicious activity'
                ],
                'next_step' => 'Download backup codes for two-factor authentication and store them securely.',
                'ui_highlights' => ['.enable-2fa', '.active-sessions'],
                'is_active' => true,
                'display_order' => 11
            ],
            [
                'page_identifier' => 'billing',
                'page_title' => 'Billing & Subscriptions',
                'what_is_this' => 'Manage your subscription, view invoices, and update payment methods.',
                'what_to_do' => [
                    'Review your current subscription plan',
                    'Upgrade or downgrade your plan as needed',
                    'Update payment method information',
                    'Download invoices for your records'
                ],
                'next_step' => 'Plan changes take effect immediately. You will be prorated for any mid-cycle changes.',
                'ui_highlights' => ['.current-plan', '.payment-method'],
                'is_active' => true,
                'display_order' => 12
            ]
        ];

        foreach ($guides as $guide) {
            InAppGuide::updateOrCreate(
                ['page_identifier' => $guide['page_identifier']],
                $guide
            );
        }
    }
}
