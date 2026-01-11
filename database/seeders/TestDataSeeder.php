<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Volunteer;
use App\Models\Company;
use App\Models\Challenge;
use App\Models\ChallengeComment;
use App\Models\VolunteerSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test data...');

        $this->command->info('Setting up test data...');

        // Create Company Users and Companies
        $this->command->info('Creating companies...');

        $company1User = User::create([
            'name' => 'Tech Innovations Inc',
            'email' => 'company1@mindova.test',
            'password' => Hash::make('password'),
            'user_type' => 'company',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $company1 = Company::create([
            'user_id' => $company1User->id,
            'company_name' => 'Tech Innovations Inc',
            'commercial_register' => 'CR-2024-001',
            'industry' => 'Technology',
            'website' => 'https://techinnovations.com',
            'description' => 'Leading technology company focused on AI and machine learning solutions.',
        ]);

        $company2User = User::create([
            'name' => 'HealthCare Solutions',
            'email' => 'company2@mindova.test',
            'password' => Hash::make('password'),
            'user_type' => 'company',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $company2 = Company::create([
            'user_id' => $company2User->id,
            'company_name' => 'HealthCare Solutions',
            'commercial_register' => 'CR-2024-002',
            'industry' => 'Healthcare',
            'website' => 'https://healthcaresolutions.com',
            'description' => 'Healthcare technology company improving patient outcomes.',
        ]);

        // Create Volunteer Users and Volunteers
        $this->command->info('Creating volunteers...');

        $volunteer1User = User::create([
            'name' => 'John Smith',
            'email' => 'john@mindova.test',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $volunteer1 = Volunteer::create([
            'user_id' => $volunteer1User->id,
            'availability_hours_per_week' => 20,
            'bio' => 'Full-stack developer with 5 years of experience in web development.',
            'field' => 'Technology',
            'reputation_score' => 75,
        ]);
        // Add skills
        VolunteerSkill::create(['volunteer_id' => $volunteer1->id, 'skill_name' => 'JavaScript', 'proficiency_level' => 'expert', 'category' => 'technical']);
        VolunteerSkill::create(['volunteer_id' => $volunteer1->id, 'skill_name' => 'React', 'proficiency_level' => 'expert', 'category' => 'technical']);
        VolunteerSkill::create(['volunteer_id' => $volunteer1->id, 'skill_name' => 'Node.js', 'proficiency_level' => 'advanced', 'category' => 'technical']);

        $volunteer2User = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@mindova.test',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $volunteer2 = Volunteer::create([
            'user_id' => $volunteer2User->id,
            'availability_hours_per_week' => 15,
            'bio' => 'Healthcare data analyst passionate about improving patient care.',
            'field' => 'Healthcare',
            'reputation_score' => 80,
        ]);
        // Add skills
        VolunteerSkill::create(['volunteer_id' => $volunteer2->id, 'skill_name' => 'Python', 'proficiency_level' => 'expert', 'category' => 'technical']);
        VolunteerSkill::create(['volunteer_id' => $volunteer2->id, 'skill_name' => 'Data Analysis', 'proficiency_level' => 'expert', 'category' => 'technical']);
        VolunteerSkill::create(['volunteer_id' => $volunteer2->id, 'skill_name' => 'Machine Learning', 'proficiency_level' => 'advanced', 'category' => 'technical']);

        $volunteer3User = User::create([
            'name' => 'Mike Chen',
            'email' => 'mike@mindova.test',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $volunteer3 = Volunteer::create([
            'user_id' => $volunteer3User->id,
            'availability_hours_per_week' => 25,
            'bio' => 'UI/UX Designer with a focus on user-centered design.',
            'field' => 'Technology',
            'reputation_score' => 70,
        ]);
        // Add skills
        VolunteerSkill::create(['volunteer_id' => $volunteer3->id, 'skill_name' => 'UI/UX Design', 'proficiency_level' => 'expert', 'category' => 'design']);
        VolunteerSkill::create(['volunteer_id' => $volunteer3->id, 'skill_name' => 'JavaScript', 'proficiency_level' => 'intermediate', 'category' => 'technical']);

        // Create Challenges with Different Scores
        $this->command->info('Creating challenges...');

        // Low score challenge (1-2) - Community Discussion
        $communityChallenge = Challenge::create([
            'company_id' => $company1->id,
            'title' => 'Exploring AI Ethics in Healthcare',
            'original_description' => 'We need to explore the ethical implications of using AI in healthcare decision-making. This is a complex topic that requires diverse perspectives and community input to properly frame the challenge.',
            'refined_brief' => 'This challenge explores the ethical considerations of AI implementation in healthcare settings, requiring community input to identify key concerns and frameworks.',
            'complexity_level' => 2,
            'score' => 2,
            'field' => 'Healthcare',
            'challenge_type' => 'community_discussion',
            'status' => 'active',
            'ai_analysis_status' => 'completed',
            'ai_analyzed_at' => now(),
        ]);

        $communityChallenge2 = Challenge::create([
            'company_id' => $company2->id,
            'title' => 'Defining Future of Telemedicine',
            'original_description' => 'What should telemedicine look like in 2030? We have many ideas but need community discussion to refine our vision and identify potential challenges.',
            'refined_brief' => 'This challenge seeks community input on shaping the future of telemedicine, identifying emerging trends, challenges, and opportunities.',
            'complexity_level' => 1,
            'score' => 1,
            'field' => 'Healthcare',
            'challenge_type' => 'community_discussion',
            'status' => 'active',
            'ai_analysis_status' => 'completed',
            'ai_analyzed_at' => now(),
        ]);

        // Medium score challenge (3-6) - Task Execution
        $taskChallenge = Challenge::create([
            'company_id' => $company1->id,
            'title' => 'Build Patient Portal Dashboard',
            'original_description' => 'We need a web-based patient portal that allows patients to view their medical records, schedule appointments, and communicate with healthcare providers. The system should be secure, user-friendly, and mobile-responsive.',
            'refined_brief' => 'Develop a comprehensive patient portal with medical record access, appointment scheduling, and secure messaging capabilities.',
            'complexity_level' => 5,
            'score' => 5,
            'field' => 'Healthcare',
            'challenge_type' => 'team_execution',
            'status' => 'analyzing',
            'ai_analysis_status' => 'completed',
            'ai_analyzed_at' => now(),
        ]);

        // High score challenge (7-10) - Task Execution
        $highScoreChallenge = Challenge::create([
            'company_id' => $company2->id,
            'title' => 'Implement Real-time Analytics Dashboard',
            'original_description' => 'Create a real-time analytics dashboard for monitoring website traffic, user behavior, and conversion metrics. The dashboard should update live and provide actionable insights.',
            'refined_brief' => 'Build a real-time analytics platform with live data visualization, customizable metrics, and actionable insights for business intelligence.',
            'complexity_level' => 7,
            'score' => 8,
            'field' => 'Technology',
            'challenge_type' => 'team_execution',
            'status' => 'analyzing',
            'ai_analysis_status' => 'completed',
            'ai_analyzed_at' => now(),
        ]);

        // Create Comments on Community Challenges
        $this->command->info('Creating comments...');

        $comment1 = ChallengeComment::create([
            'challenge_id' => $communityChallenge->id,
            'user_id' => $volunteer2User->id,
            'content' => 'This is a critical topic. From my experience in healthcare data analysis, I think we need to focus on three key areas: data privacy, algorithmic bias, and accountability frameworks. AI systems must be transparent in their decision-making processes, especially when patient lives are at stake.',
            'ai_score' => 9,
            'ai_score_status' => 'completed',
            'ai_analysis' => 'Highly relevant and constructive comment providing specific, actionable insights based on professional experience.',
            'ai_scored_at' => now(),
        ]);

        $comment2 = ChallengeComment::create([
            'challenge_id' => $communityChallenge->id,
            'user_id' => $volunteer1User->id,
            'content' => 'Great challenge! Have you considered implementing a human-in-the-loop approach where AI provides recommendations but doctors make final decisions? This could balance efficiency with accountability.',
            'ai_score' => 8,
            'ai_score_status' => 'completed',
            'ai_analysis' => 'Provides a concrete solution approach with clear benefits, demonstrating understanding of both technical and ethical aspects.',
            'ai_scored_at' => now(),
        ]);

        $comment3 = ChallengeComment::create([
            'challenge_id' => $communityChallenge2->id,
            'user_id' => $volunteer1User->id,
            'content' => 'Telemedicine should prioritize accessibility. We need to ensure rural areas and elderly populations can easily use these systems. Consider voice-activated interfaces and simplified UI.',
            'ai_score' => 7,
            'ai_score_status' => 'completed',
            'ai_analysis' => 'Identifies important accessibility considerations with practical suggestions for inclusive design.',
            'ai_scored_at' => now(),
        ]);

        $comment4 = ChallengeComment::create([
            'challenge_id' => $communityChallenge2->id,
            'user_id' => $volunteer2User->id,
            'content' => 'Integration with wearable devices will be crucial. Real-time health monitoring data could revolutionize preventive care.',
            'ai_score' => 6,
            'ai_score_status' => 'completed',
            'ai_analysis' => 'Relevant suggestion but lacks depth and implementation details.',
            'ai_scored_at' => now(),
        ]);

        $this->command->info('✅ Test data created successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Companies:');
        $this->command->info('  📧 company1@mindova.test / password');
        $this->command->info('  📧 company2@mindova.test / password');
        $this->command->info('');
        $this->command->info('Volunteers:');
        $this->command->info('  📧 john@mindova.test / password (Technology)');
        $this->command->info('  📧 sarah@mindova.test / password (Healthcare)');
        $this->command->info('  📧 mike@mindova.test / password (Technology)');
        $this->command->info('');
        $this->command->info('Test Data:');
        $this->command->info('  ✓ 2 Community Challenges (score 1-2)');
        $this->command->info('  ✓ 2 Task Execution Challenges (score 5-8)');
        $this->command->info('  ✓ 4 High-quality Comments (scores 6-9)');
        $this->command->info('  ✓ 8 Skills assigned to volunteers');
    }
}
