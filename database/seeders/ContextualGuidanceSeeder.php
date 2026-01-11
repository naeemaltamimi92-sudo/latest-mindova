<?php

namespace Database\Seeders;

use App\Models\ContextualGuidance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContextualGuidanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guidances = [
            // Challenges List
            [
                'page_identifier' => 'challenges.index',
                'page_title' => 'Challenges List',
                'guidance_text' => 'Here you can explore challenges that match your expertise.',
                'icon' => '💡',
            ],

            // Submit Challenge
            [
                'page_identifier' => 'challenges.create',
                'page_title' => 'Submit Challenge',
                'guidance_text' => 'Here, you describe the challenge. No sensitive data is required — everything is protected by NDA.',
                'icon' => '💡',
            ],

            // Challenge Details
            [
                'page_identifier' => 'challenges.show',
                'page_title' => 'Challenge Details',
                'guidance_text' => 'Review the challenge details and join if it matches your skills.',
                'icon' => '💡',
            ],

            // My Tasks
            [
                'page_identifier' => 'tasks.index',
                'page_title' => 'Tasks',
                'guidance_text' => 'Focus on your assigned task only. You don\'t need to solve the entire challenge.',
                'icon' => '💡',
            ],

            // Task Details
            [
                'page_identifier' => 'tasks.show',
                'page_title' => 'Task Details',
                'guidance_text' => 'Review your assigned task and submit your contribution when ready.',
                'icon' => '💡',
            ],

            // Teams
            [
                'page_identifier' => 'teams.index',
                'page_title' => 'Teams',
                'guidance_text' => 'Each member has a defined role. Collaboration happens through tasks and comments.',
                'icon' => '💡',
            ],

            // Team Details
            [
                'page_identifier' => 'teams.show',
                'page_title' => 'Team',
                'guidance_text' => 'Each member has a defined role. Collaboration happens through tasks and comments.',
                'icon' => '💡',
            ],

            // Dashboard
            [
                'page_identifier' => 'dashboard',
                'page_title' => 'Dashboard',
                'guidance_text' => 'Your personalized overview of active challenges, tasks, and team updates.',
                'icon' => '👋',
            ],

            // Profile
            [
                'page_identifier' => 'profile.show',
                'page_title' => 'Profile',
                'guidance_text' => 'Keep your profile updated to receive relevant challenge recommendations.',
                'icon' => '👤',
            ],

            // Ideas/Discussion
            [
                'page_identifier' => 'ideas.index',
                'page_title' => 'Ideas',
                'guidance_text' => 'Share your thoughts and collaborate with others on potential solutions.',
                'icon' => '💭',
            ],

            // Notifications
            [
                'page_identifier' => 'notifications.index',
                'page_title' => 'Notifications',
                'guidance_text' => 'Stay updated on task assignments, team invitations, and challenge updates.',
                'icon' => '🔔',
            ],
        ];

        foreach ($guidances as $guidance) {
            ContextualGuidance::updateOrCreate(
                ['page_identifier' => $guidance['page_identifier']],
                $guidance
            );
        }

        $this->command->info('Contextual guidance seeded successfully!');
    }
}
