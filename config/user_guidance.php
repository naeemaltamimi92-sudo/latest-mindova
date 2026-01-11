<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Volunteer Guidance - COMPLETE PLATFORM COVERAGE
    |--------------------------------------------------------------------------
    */

    'volunteer' => [
        // ============ AUTHENTICATION & ONBOARDING ============

        'login' => [
            'welcome' => [
                'text' => 'Sign in to access your dashboard and start working on meaningful challenges.',
                'element' => '#login-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'dashboard.welcome',
            ],
        ],

        'register' => [
            'start' => [
                'text' => 'Create your volunteer account to join challenges and contribute your skills.',
                'element' => '#register-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'complete-profile.start',
            ],
        ],

        'complete-profile' => [
            'start' => [
                'text' => 'Complete your profile with skills and experience so we can match you with the right challenges.',
                'element' => '#profile-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'nda.general.review',
            ],
        ],

        'password.request' => [
            'reset' => [
                'text' => 'Enter your email to receive a password reset link.',
                'element' => '#forgot-password-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ DASHBOARD ============

        'dashboard' => [
            'welcome' => [
                'text' => 'Welcome! This is your central hub for all activities, tasks, and updates.',
                'element' => '#dashboard-main',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'profile.edit.complete',
            ],
            'profile_prompt' => [
                'text' => 'Complete your profile to start receiving challenge invitations.',
                'element' => '#complete-profile-card',
                'position' => 'bottom',
                'trigger' => 'profile_incomplete',
                'next_step' => 'complete-profile.start',
            ],
        ],

        // ============ NDA ============

        'nda.general' => [
            'review' => [
                'text' => 'Review and sign the NDA to access tasks and collaborate on challenges.',
                'element' => '#nda-agreement',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'challenges.index.browse',
            ],
        ],

        'nda.challenge' => [
            'specific' => [
                'text' => 'This challenge requires an additional confidentiality agreement. Review and accept to proceed.',
                'element' => '#challenge-nda',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'challenges.show.review',
            ],
        ],

        // ============ CHALLENGES ============

        'challenges.index' => [
            'browse' => [
                'text' => 'Browse challenges that match your skills and interests. Click on any challenge to view details.',
                'element' => '#challenges-list',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'challenges.show.review',
            ],
        ],

        'challenges.show' => [
            'review' => [
                'text' => 'Review the challenge details, required skills, and timeline. Join if it matches your expertise!',
                'element' => '#challenge-details',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'teams.show.invited',
            ],
        ],

        // ============ TASKS ============

        'tasks.available' => [
            'browse' => [
                'text' => 'These are tasks available based on your skills. Accept tasks you\'re confident you can complete.',
                'element' => '#available-tasks-list',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'tasks.show.focus',
            ],
        ],

        'tasks.show' => [
            'focus' => [
                'text' => 'Review the task requirements carefully. Use comments to collaborate and ask questions.',
                'element' => '#task-details',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'assignments.my.manage',
            ],
        ],

        // ============ ASSIGNMENTS ============

        'assignments.my' => [
            'manage' => [
                'text' => 'Manage all your assignments here. Complete tasks at your own pace - quality matters more than speed.',
                'element' => '#assignments-list',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
            'submit' => [
                'text' => 'When ready, submit your solution for company review. Make sure you\'ve met all requirements.',
                'element' => '#submit-solution-button',
                'position' => 'left',
                'trigger' => 'has_active_assignments',
                'next_step' => null,
            ],
        ],

        // ============ TEAMS ============

        'teams.show' => [
            'invited' => [
                'text' => 'You\'ve been invited because your skills match this challenge. Review team details and accept to join.',
                'element' => '#team-details',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'tasks.show.focus',
            ],
        ],

        'teams.my' => [
            'overview' => [
                'text' => 'View all teams you\'re part of and their current status. Click on any team to see details.',
                'element' => '#teams-list',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ COMMUNITY ============

        'community.index' => [
            'explore' => [
                'text' => 'Engage with public challenges and join discussions with other volunteers.',
                'element' => '#community-challenges',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'community.challenge' => [
            'participate' => [
                'text' => 'Read the discussion, vote on helpful comments, and share your thoughts.',
                'element' => '#comments-section',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ PROFILE & SETTINGS ============

        'profile.edit' => [
            'complete' => [
                'text' => 'Keep your profile updated with latest skills and experience for better challenge matching.',
                'element' => '#profile-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'settings.notifications' => [
            'customize' => [
                'text' => 'Choose how and when you receive notifications about tasks, teams, and challenges.',
                'element' => '#notification-settings',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ LEADERBOARD ============

        'leaderboard' => [
            'compete' => [
                'text' => 'See top performers and track your ranking. Complete quality work to earn points and climb up!',
                'element' => '#leaderboard-table',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ IDEAS ============

        'ideas.show' => [
            'view' => [
                'text' => 'Review this idea and provide feedback or suggestions to help refine it.',
                'element' => '#idea-details',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'ideas.create' => [
            'submit' => [
                'text' => 'Share your idea for solving this challenge. Your creative input helps shape the solution.',
                'element' => '#idea-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ PUBLIC PROFILES ============

        'volunteers.show' => [
            'view' => [
                'text' => 'View this volunteer\'s profile, skills, and contribution history.',
                'element' => '#volunteer-profile',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'companies.show' => [
            'view' => [
                'text' => 'View this company\'s profile and the challenges they\'ve posted.',
                'element' => '#company-profile',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ STATIC PAGES ============

        'how-it-works' => [
            'learn' => [
                'text' => 'Learn how Mindova connects volunteers with meaningful challenges step by step.',
                'element' => '#content-main',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'success-stories' => [
            'inspire' => [
                'text' => 'Read success stories from volunteers and companies who achieved great results.',
                'element' => '#stories-list',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'help' => [
            'support' => [
                'text' => 'Find answers to common questions or contact support if you need assistance.',
                'element' => '#help-center',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'guidelines' => [
            'review' => [
                'text' => 'Review community guidelines for best practices and professional conduct.',
                'element' => '#guidelines-content',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'api-docs' => [
            'integrate' => [
                'text' => 'Technical documentation for integrating with Mindova platform APIs.',
                'element' => '#api-documentation',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'blog' => [
            'read' => [
                'text' => 'Stay updated with latest news, tips, and insights from the Mindova community.',
                'element' => '#blog-posts',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'about' => [
            'mission' => [
                'text' => 'Learn about Mindova\'s mission to connect skilled volunteers with impactful challenges.',
                'element' => '#about-content',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'contact' => [
            'reach' => [
                'text' => 'Get in touch with us for questions, feedback, or partnership opportunities.',
                'element' => '#contact-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'privacy' => [
            'understand' => [
                'text' => 'Understand how we protect your data and respect your privacy.',
                'element' => '#privacy-policy',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'terms' => [
            'agree' => [
                'text' => 'Review the terms of service for using the Mindova platform.',
                'element' => '#terms-content',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Company Guidance - COMPLETE PLATFORM COVERAGE
    |--------------------------------------------------------------------------
    */

    'company' => [
        // ============ AUTHENTICATION & ONBOARDING ============

        'login' => [
            'welcome' => [
                'text' => 'Sign in to manage your challenges and collaborate with talented volunteers.',
                'element' => '#login-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'dashboard.welcome',
            ],
        ],

        'register' => [
            'start' => [
                'text' => 'Create your company account to post challenges and access skilled volunteers.',
                'element' => '#register-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'complete-profile.start',
            ],
        ],

        'complete-profile' => [
            'start' => [
                'text' => 'Complete your company profile to build trust and attract top volunteers.',
                'element' => '#company-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'dashboard.welcome',
            ],
        ],

        // ============ DASHBOARD ============

        'dashboard' => [
            'welcome' => [
                'text' => 'Welcome! Post a challenge to let our platform form the perfect team for you.',
                'element' => '#create-challenge-button',
                'position' => 'bottom',
                'trigger' => 'first_visit',
                'next_step' => 'challenges.create.start',
            ],
            'overview' => [
                'text' => 'Monitor all your active challenges, team progress, and submitted solutions from here.',
                'element' => '#dashboard-main',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ CHALLENGES ============

        'challenges.index' => [
            'manage' => [
                'text' => 'View and manage all your posted challenges. Track their status and progress.',
                'element' => '#challenges-list',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'challenges.create' => [
            'start' => [
                'text' => 'Describe your challenge clearly. Our AI will decompose it into tasks and match the right volunteers.',
                'element' => '#challenge-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => 'challenges.show.review_tasks',
            ],
        ],

        'challenges.show' => [
            'review_tasks' => [
                'text' => 'Review the AI-generated tasks and assigned volunteers. Request changes if needed before confirming.',
                'element' => '#tasks-section',
                'position' => 'top',
                'trigger' => 'challenge_created',
                'next_step' => 'challenges.analytics.monitor',
            ],
            'overview' => [
                'text' => 'View complete challenge details, team composition, and current progress.',
                'element' => '#challenge-overview',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'challenges.analytics' => [
            'monitor' => [
                'text' => 'Track team progress, review submitted work, and monitor overall challenge health.',
                'element' => '#progress-dashboard',
                'position' => 'right',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ TEAMS ============

        'teams.show' => [
            'review' => [
                'text' => 'View your team members, their roles, and contribution to the challenge.',
                'element' => '#team-members',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ COMMUNITY ============

        'community.index' => [
            'engage' => [
                'text' => 'Explore community challenges and engage with volunteer discussions.',
                'element' => '#community-challenges',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ PROFILE & SETTINGS ============

        'profile.edit' => [
            'update' => [
                'text' => 'Keep your company information updated to attract quality volunteers.',
                'element' => '#company-profile-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'settings.notifications' => [
            'customize' => [
                'text' => 'Configure how you receive updates about challenge progress and team activities.',
                'element' => '#notification-settings',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ LEADERBOARD ============

        'leaderboard' => [
            'discover' => [
                'text' => 'Discover top-performing volunteers for potential collaboration on future challenges.',
                'element' => '#leaderboard-table',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ PUBLIC PROFILES ============

        'volunteers.show' => [
            'evaluate' => [
                'text' => 'Review volunteer skills, experience, and past contributions before inviting to challenges.',
                'element' => '#volunteer-profile',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'companies.show' => [
            'view' => [
                'text' => 'View other companies and their posted challenges for inspiration.',
                'element' => '#company-profile',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        // ============ STATIC PAGES ============

        'how-it-works' => [
            'learn' => [
                'text' => 'Learn how Mindova helps companies solve challenges through skilled volunteer teams.',
                'element' => '#content-main',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'success-stories' => [
            'inspire' => [
                'text' => 'Read success stories from companies who achieved breakthrough results.',
                'element' => '#stories-list',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'help' => [
            'support' => [
                'text' => 'Find answers or contact our support team for assistance with your challenges.',
                'element' => '#help-center',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'contact' => [
            'reach' => [
                'text' => 'Contact us for enterprise solutions, partnerships, or general inquiries.',
                'element' => '#contact-form',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'about' => [
            'mission' => [
                'text' => 'Learn about Mindova\'s mission to democratize access to skilled talent.',
                'element' => '#about-content',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'privacy' => [
            'understand' => [
                'text' => 'Understand our commitment to data security and privacy protection.',
                'element' => '#privacy-policy',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],

        'terms' => [
            'review' => [
                'text' => 'Review the terms of service for posting challenges and working with volunteers.',
                'element' => '#terms-content',
                'position' => 'top',
                'trigger' => 'first_visit',
                'next_step' => null,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Settings
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'enabled' => env('GUIDED_TOUR_ENABLED', true),
        'dismissible' => true,
        'auto_progress' => true,
        'tooltip_delay' => 500,
        'animation' => 'fade',
        'max_tooltips_per_page' => 1, // Show 1 tooltip per page for non-intrusive experience
        'show_on_all_pages' => true, // Enable guidance on ALL pages
    ],
];
