<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Contextual Guide Content (MVP - Simple & Lightweight)
    |--------------------------------------------------------------------------
    |
    | This file contains short, contextual guidance text for each page.
    | Keep text to 1-2 sentences max. Non-technical, reassuring, explanatory.
    |
    */

    'guides' => [
        // Authentication & Onboarding
        'login' => [
            'text' => 'Sign in to access your dashboard and start working on challenges.',
        ],

        'register' => [
            'text' => 'Create your account to join Mindova as a volunteer or company.',
        ],

        'complete-profile' => [
            'text' => 'Complete your profile so we can match you with the right opportunities.',
        ],

        // Dashboard
        'dashboard' => [
            'text' => 'Your central hub for all activities, tasks, and updates.',
        ],

        // NDA
        'nda.general' => [
            'text' => 'Please review and sign the NDA to access tasks and challenges.',
        ],

        'nda.challenge' => [
            'text' => 'This challenge requires an additional confidentiality agreement.',
        ],

        // Challenges
        'challenges.index' => [
            'text' => 'Here you explore challenges that match your expertise.',
        ],

        'challenges.create' => [
            'text' => 'Fill in the details to submit a new challenge for volunteers to solve.',
        ],

        'challenges.show' => [
            'text' => 'Review the challenge details and join if it matches your skills.',
        ],

        'challenges.analytics' => [
            'text' => 'Monitor your challenge progress and team performance here.',
        ],

        // Tasks
        'tasks.available' => [
            'text' => 'Browse available tasks that match your skills and interests.',
        ],

        'tasks.show' => [
            'text' => 'Complete tasks at your pace. Quality matters more than speed.',
        ],

        // Assignments
        'assignments.my' => [
            'text' => 'Manage your current assignments and track your progress.',
        ],

        // Teams
        'teams.show' => [
            'text' => 'You\'ve been selected because your skills match this challenge.',
        ],

        'teams.my' => [
            'text' => 'View all teams you\'re part of and their current status.',
        ],

        // Community
        'community.index' => [
            'text' => 'Engage with public challenges and join the discussion.',
        ],

        // Profile & Settings
        'profile.edit' => [
            'text' => 'Update your professional information and skills here.',
        ],

        'settings.notifications' => [
            'text' => 'Customize how and when you receive platform notifications.',
        ],

        // Leaderboard
        'leaderboard' => [
            'text' => 'See top performers and track your ranking on the platform.',
        ],

        // Static Pages
        'how-it-works' => [
            'text' => 'Learn how Mindova connects volunteers with meaningful challenges.',
        ],

        'help' => [
            'text' => 'Find answers to common questions and contact support if needed.',
        ],

        'about' => [
            'text' => 'Learn more about Mindova\'s mission and vision.',
        ],

        'contact' => [
            'text' => 'Get in touch with us for questions, feedback, or support.',
        ],

        'guidelines' => [
            'text' => 'Review the community guidelines for best practices.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Display Settings
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'position' => 'bottom-right',
        'icon' => '❓',
        'show_on_first_visit' => true,
        'dismissible' => true,
    ],
];
