<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Volunteer;
use App\Models\VolunteerSkill;
use App\Models\Challenge;
use App\Models\Workstream;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\WorkSubmission;
use App\Models\ChallengeNdaSigning;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Demo Data Seeding...');

        // Create Companies
        $this->command->info('Creating companies...');
        $companies = $this->createCompanies();

        // Create Volunteers
        $this->command->info('Creating volunteers...');
        $volunteers = $this->createVolunteers();

        // Create Challenges
        $this->command->info('Creating challenges...');
        $challenges = $this->createChallenges($companies);

        // Create Tasks and Workstreams
        $this->command->info('Creating workstreams and tasks...');
        $this->createWorkstreamsAndTasks($challenges);

        // Create Teams
        $this->command->info('Creating teams...');
        $teams = $this->createTeams($challenges, $volunteers);

        // Create Task Assignments
        $this->command->info('Creating task assignments...');
        $this->createTaskAssignments($challenges, $volunteers);

        // Create Work Submissions
        $this->command->info('Creating work submissions...');
        $this->createWorkSubmissions();

        // Note: Challenge-specific NDA signings skipped for demo simplicity
        // Volunteers have general NDA signed which is sufficient

        $this->command->info('✅ Demo Data Seeding Completed!');
    }

    private function createCompanies(): array
    {
        $companies = [];

        // Tech Startup
        $user1 = User::create([
            'name' => 'TechCorp Solutions',
            'email' => 'company1@techcorp.com',
            'password' => Hash::make('password'),
            'user_type' => 'company',
        ]);
        $companies[] = Company::create([
            'user_id' => $user1->id,
            'company_name' => 'TechCorp Solutions',
            'website' => 'https://techcorp.com',
            'industry' => 'Technology',
            'description' => 'Leading software development company specializing in AI and machine learning solutions.',
            'total_challenges_submitted' => 0,
        ]);

        // Manufacturing Company
        $user2 = User::create([
            'name' => 'Global Manufacturing Inc',
            'email' => 'company2@globalmanuf.com',
            'password' => Hash::make('password'),
            'user_type' => 'company',
        ]);
        $companies[] = Company::create([
            'user_id' => $user2->id,
            'company_name' => 'Global Manufacturing Inc',
            'website' => 'https://globalmanuf.com',
            'industry' => 'Manufacturing',
            'description' => 'Industrial manufacturing company focused on sustainable production processes.',
            'total_challenges_submitted' => 0,
        ]);

        // Healthcare Startup
        $user3 = User::create([
            'name' => 'HealthTech Innovations',
            'email' => 'company3@healthtech.com',
            'password' => Hash::make('password'),
            'user_type' => 'company',
        ]);
        $companies[] = Company::create([
            'user_id' => $user3->id,
            'company_name' => 'HealthTech Innovations',
            'website' => 'https://healthtech.com',
            'industry' => 'Healthcare',
            'description' => 'Digital health platform connecting patients with healthcare providers.',
            'total_challenges_submitted' => 0,
        ]);

        return $companies;
    }

    private function createVolunteers(): array
    {
        $volunteers = [];

        // Software Engineer
        $user1 = User::create([
            'name' => 'John Developer',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
        ]);
        $volunteer1 = Volunteer::create([
            'user_id' => $user1->id,
            'field' => 'Software Engineering',
            'experience_level' => 'Expert',
            'years_of_experience' => 8,
            'availability_hours_per_week' => 15,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 85,
        ]);
        $this->addSkills($volunteer1, [
            ['skill_name' => 'PHP', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
            ['skill_name' => 'Laravel', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'JavaScript', 'proficiency_level' => 'advanced', 'years_of_experience' => 7],
            ['skill_name' => 'React', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'MySQL', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
        ]);
        $volunteers[] = $volunteer1;

        // Chemical Engineer
        $user2 = User::create([
            'name' => 'Sarah Chen',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
        ]);
        $volunteer2 = Volunteer::create([
            'user_id' => $user2->id,
            'field' => 'Chemical Engineering',
            'experience_level' => 'Mid',
            'years_of_experience' => 5,
            'availability_hours_per_week' => 10,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 75,
        ]);
        $this->addSkills($volunteer2, [
            ['skill_name' => 'Process Optimization', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'Chemical Analysis', 'proficiency_level' => 'expert', 'years_of_experience' => 5],
            ['skill_name' => 'Quality Control', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'Safety Compliance', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
        ]);
        $volunteers[] = $volunteer2;

        // Data Scientist
        $user3 = User::create([
            'name' => 'Mike Analytics',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
        ]);
        $volunteer3 = Volunteer::create([
            'user_id' => $user3->id,
            'field' => 'Data Science',
            'experience_level' => 'Expert',
            'years_of_experience' => 7,
            'availability_hours_per_week' => 12,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 90,
        ]);
        $this->addSkills($volunteer3, [
            ['skill_name' => 'Python', 'proficiency_level' => 'expert', 'years_of_experience' => 7],
            ['skill_name' => 'Machine Learning', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'TensorFlow', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'Data Visualization', 'proficiency_level' => 'expert', 'years_of_experience' => 7],
            ['skill_name' => 'SQL', 'proficiency_level' => 'expert', 'years_of_experience' => 7],
        ]);
        $volunteers[] = $volunteer3;

        // UX Designer
        $user4 = User::create([
            'name' => 'Emma Design',
            'email' => 'emma@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
        ]);
        $volunteer4 = Volunteer::create([
            'user_id' => $user4->id,
            'field' => 'UX Design',
            'experience_level' => 'Mid',
            'years_of_experience' => 4,
            'availability_hours_per_week' => 10,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 70,
        ]);
        $this->addSkills($volunteer4, [
            ['skill_name' => 'Figma', 'proficiency_level' => 'expert', 'years_of_experience' => 4],
            ['skill_name' => 'User Research', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'Wireframing', 'proficiency_level' => 'expert', 'years_of_experience' => 4],
            ['skill_name' => 'Prototyping', 'proficiency_level' => 'advanced', 'years_of_experience' => 3],
        ]);
        $volunteers[] = $volunteer4;

        // Marketing Specialist
        $user5 = User::create([
            'name' => 'Lisa Marketing',
            'email' => 'lisa@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
        ]);
        $volunteer5 = Volunteer::create([
            'user_id' => $user5->id,
            'field' => 'Marketing',
            'experience_level' => 'Mid',
            'years_of_experience' => 5,
            'availability_hours_per_week' => 8,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 65,
        ]);
        $this->addSkills($volunteer5, [
            ['skill_name' => 'Digital Marketing', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'SEO', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'Content Marketing', 'proficiency_level' => 'expert', 'years_of_experience' => 5],
            ['skill_name' => 'Social Media', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
        ]);
        $volunteers[] = $volunteer5;

        // Project Manager
        $user6 = User::create([
            'name' => 'David Manager',
            'email' => 'david@example.com',
            'password' => Hash::make('password'),
            'user_type' => 'volunteer',
        ]);
        $volunteer6 = Volunteer::create([
            'user_id' => $user6->id,
            'field' => 'Project Management',
            'experience_level' => 'Manager',
            'years_of_experience' => 10,
            'availability_hours_per_week' => 15,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 95,
        ]);
        $this->addSkills($volunteer6, [
            ['skill_name' => 'Agile', 'proficiency_level' => 'expert', 'years_of_experience' => 10],
            ['skill_name' => 'Scrum', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
            ['skill_name' => 'Risk Management', 'proficiency_level' => 'expert', 'years_of_experience' => 10],
            ['skill_name' => 'Stakeholder Management', 'proficiency_level' => 'expert', 'years_of_experience' => 10],
        ]);
        $volunteers[] = $volunteer6;

        return $volunteers;
    }

    private function addSkills($volunteer, $skills): void
    {
        foreach ($skills as $skill) {
            VolunteerSkill::create([
                'volunteer_id' => $volunteer->id,
                'skill_name' => $skill['skill_name'],
                'proficiency_level' => $skill['proficiency_level'],
                'years_of_experience' => $skill['years_of_experience'],
            ]);
        }
    }

    private function createChallenges($companies): array
    {
        $challenges = [];

        // Challenge 1: Completed AI Platform (TechCorp)
        $challenges[] = Challenge::create([
            'company_id' => $companies[0]->id,
            'title' => 'Build AI-Powered Customer Support Chatbot',
            'original_description' => 'We need a sophisticated AI chatbot that can handle customer inquiries, provide product recommendations, and escalate complex issues to human agents. The system should integrate with our existing CRM and support ticket system.',
            'refined_brief' => 'Develop an AI-powered customer support chatbot with natural language processing capabilities. Key features: 1) Multi-turn conversation handling, 2) Intent recognition and entity extraction, 3) Integration with existing CRM (Salesforce), 4) Seamless handoff to human agents, 5) Analytics dashboard for monitoring performance.',
            'field' => 'Software Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 8,
            'score' => 8,
            'requires_nda' => true,
            'deadline' => now()->addDays(60)->format('Y-m-d'),
            'created_at' => now()->subDays(15),
        ]);

        // Challenge 2: Active Manufacturing Process (Global Manufacturing)
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'title' => 'Optimize Chemical Production Process',
            'original_description' => 'Our current chemical production process has inefficiencies leading to higher costs and waste. We need expert analysis to identify bottlenecks and recommend improvements.',
            'refined_brief' => 'Analyze and optimize the current chemical production workflow to reduce waste by 15% and improve efficiency by 20%. Deliverables: 1) Process flow analysis, 2) Bottleneck identification report, 3) Improvement recommendations with cost-benefit analysis, 4) Implementation roadmap.',
            'field' => 'Chemical Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 7,
            'score' => 7,
            'requires_nda' => true,
            'deadline' => now()->addDays(45)->format('Y-m-d'),
            'created_at' => now()->subDays(10),
        ]);

        // Challenge 3: New Analyzing Healthcare Platform (HealthTech)
        $challenges[] = Challenge::create([
            'company_id' => $companies[2]->id,
            'title' => 'Patient Data Analytics Dashboard',
            'original_description' => 'Create a comprehensive analytics dashboard for healthcare providers to visualize patient outcomes, treatment effectiveness, and operational metrics.',
            'refined_brief' => 'Design and develop a HIPAA-compliant analytics dashboard for healthcare data visualization. Requirements: 1) Real-time patient outcome tracking, 2) Treatment effectiveness metrics, 3) Predictive analytics for patient risk assessment, 4) Customizable reporting, 5) Role-based access control.',
            'field' => 'Data Science',
            'status' => 'analyzing',
            'challenge_type' => 'team_execution',
            'complexity_level' => 9,
            'score' => 9,
            'requires_nda' => true,
            'deadline' => now()->addDays(90)->format('Y-m-d'),
            'created_at' => now()->subDays(2),
        ]);

        // Challenge 4: Community Discussion - UX Improvement (TechCorp)
        $challenges[] = Challenge::create([
            'company_id' => $companies[0]->id,
            'title' => 'Improve Mobile App User Experience',
            'original_description' => 'Our mobile app has a high drop-off rate. We want community insights on how to improve the user experience and increase engagement.',
            'refined_brief' => 'Seeking community feedback on mobile app UX improvements. Current issues: 1) Complex onboarding, 2) Unclear navigation, 3) Performance issues on older devices. Looking for: Design suggestions, usability improvements, and innovative features.',
            'field' => 'UX Design',
            'status' => 'active',
            'challenge_type' => 'community_discussion',
            'complexity_level' => 2,
            'score' => 2,
            'requires_nda' => false,
            'deadline' => now()->addDays(30)->format('Y-m-d'),
            'created_at' => now()->subDays(5),
        ]);

        // Challenge 5: Submitted - Marketing Strategy (HealthTech)
        $challenges[] = Challenge::create([
            'company_id' => $companies[2]->id,
            'title' => 'Digital Marketing Strategy for Healthcare Platform',
            'original_description' => 'We need a comprehensive digital marketing strategy to reach healthcare providers and increase platform adoption.',
            'refined_brief' => null,
            'field' => 'Marketing',
            'status' => 'submitted',
            'challenge_type' => 'team_execution',
            'complexity_level' => null,
            'score' => null,
            'requires_nda' => false,
            'deadline' => now()->addDays(60)->format('Y-m-d'),
            'created_at' => now()->subHours(3),
        ]);

        return $challenges;
    }

    private function createWorkstreamsAndTasks($challenges): void
    {
        // Challenge 1: AI Chatbot - Full Workstreams and Tasks
        $workstream1_1 = Workstream::create([
            'challenge_id' => $challenges[0]->id,
            'title' => 'Backend Development',
            'description' => 'Core backend infrastructure and API development',
            'order' => 1,
        ]);

        Task::create([
            'challenge_id' => $challenges[0]->id,
            'workstream_id' => $workstream1_1->id,
            'title' => 'Design and implement RESTful API',
            'description' => 'Create a RESTful API for chatbot interactions including endpoints for conversation management, intent processing, and CRM integration. Must support authentication, rate limiting, and proper error handling.',
            'estimated_hours' => 40,
            'required_skills' => ['PHP', 'Laravel', 'API Design', 'MySQL'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Fully functional RESTful API with documentation',
            'acceptance_criteria' => ['API endpoints are functional', 'Documentation is complete', 'Authentication works correctly', 'All tests pass'],
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        Task::create([
            'challenge_id' => $challenges[0]->id,
            'workstream_id' => $workstream1_1->id,
            'title' => 'Implement NLP intent recognition',
            'description' => 'Integrate natural language processing engine to recognize user intents and extract entities from messages. Should handle multi-turn conversations and context maintenance.',
            'estimated_hours' => 35,
            'required_skills' => ['Python', 'Machine Learning', 'NLP', 'TensorFlow'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'NLP model integrated with intent recognition capabilities',
            'acceptance_criteria' => ['Intent recognition accuracy > 90%', 'Entity extraction works correctly', 'Multi-turn conversation support', 'Model is optimized for performance'],
            'status' => 'assigned',
            'priority' => 'high',
        ]);

        $workstream1_2 = Workstream::create([
            'challenge_id' => $challenges[0]->id,
            'title' => 'Frontend Development',
            'description' => 'User interface and chat widget development',
            'order' => 2,
        ]);

        Task::create([
            'challenge_id' => $challenges[0]->id,
            'workstream_id' => $workstream1_2->id,
            'title' => 'Build chat widget UI component',
            'description' => 'Create a responsive, embeddable chat widget with typing indicators, message history, and file upload support. Must work across different browsers and devices.',
            'estimated_hours' => 30,
            'required_skills' => ['JavaScript', 'React', 'CSS', 'Responsive Design'],
            'required_experience_level' => 'Mid',
            'expected_output' => 'Responsive chat widget component with full functionality',
            'acceptance_criteria' => ['Works on all major browsers', 'Responsive on mobile devices', 'Typing indicators work', 'File upload is functional'],
            'status' => 'completed',
            'priority' => 'high',
        ]);

        Task::create([
            'challenge_id' => $challenges[0]->id,
            'workstream_id' => $workstream1_2->id,
            'title' => 'Admin dashboard for monitoring',
            'description' => 'Develop an admin dashboard to monitor chatbot conversations, view analytics, and manage responses. Include real-time updates and export functionality.',
            'estimated_hours' => 25,
            'required_skills' => ['React', 'JavaScript', 'Data Visualization', 'WebSockets'],
            'required_experience_level' => 'Mid',
            'expected_output' => 'Admin dashboard with real-time monitoring and analytics',
            'acceptance_criteria' => ['Real-time updates work', 'Analytics are accurate', 'Export functionality works', 'User-friendly interface'],
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $workstream1_3 = Workstream::create([
            'challenge_id' => $challenges[0]->id,
            'title' => 'Integration & Testing',
            'description' => 'Third-party integrations and quality assurance',
            'order' => 3,
        ]);

        Task::create([
            'challenge_id' => $challenges[0]->id,
            'workstream_id' => $workstream1_3->id,
            'title' => 'Salesforce CRM integration',
            'description' => 'Integrate the chatbot with Salesforce CRM to sync customer data, create tickets, and update contact records automatically.',
            'estimated_hours' => 20,
            'required_skills' => ['API Integration', 'Salesforce', 'PHP', 'Laravel'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Salesforce integration with automated data sync',
            'acceptance_criteria' => ['Data syncs automatically', 'Customer records are updated', 'Tickets are created correctly', 'Error handling works'],
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        // Challenge 2: Chemical Process - Workstreams and Tasks
        $workstream2_1 = Workstream::create([
            'challenge_id' => $challenges[1]->id,
            'title' => 'Process Analysis',
            'description' => 'Current process documentation and bottleneck identification',
            'order' => 1,
        ]);

        Task::create([
            'challenge_id' => $challenges[1]->id,
            'workstream_id' => $workstream2_1->id,
            'title' => 'Map current production workflow',
            'description' => 'Create detailed process flow diagrams for the current chemical production workflow, identifying each step, inputs, outputs, and decision points.',
            'estimated_hours' => 15,
            'required_skills' => ['Process Optimization', 'Chemical Engineering', 'Technical Documentation'],
            'required_experience_level' => 'Mid',
            'expected_output' => 'Complete process flow diagrams with documentation',
            'acceptance_criteria' => ['All process steps documented', 'Flow diagrams are clear', 'Inputs/outputs identified', 'Decision points marked'],
            'status' => 'assigned',
            'priority' => 'high',
        ]);

        Task::create([
            'challenge_id' => $challenges[1]->id,
            'workstream_id' => $workstream2_1->id,
            'title' => 'Identify process bottlenecks',
            'description' => 'Analyze the production workflow to identify bottlenecks, inefficiencies, and waste generation points. Provide quantitative analysis with metrics.',
            'estimated_hours' => 20,
            'required_skills' => ['Chemical Analysis', 'Process Optimization', 'Data Analysis'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Bottleneck analysis report with quantitative metrics',
            'acceptance_criteria' => ['Bottlenecks identified', 'Metrics are quantified', 'Root causes analyzed', 'Recommendations provided'],
            'status' => 'pending',
            'priority' => 'high',
        ]);

        $workstream2_2 = Workstream::create([
            'challenge_id' => $challenges[1]->id,
            'title' => 'Optimization Recommendations',
            'description' => 'Develop improvement strategies and implementation plans',
            'order' => 2,
        ]);

        Task::create([
            'challenge_id' => $challenges[1]->id,
            'workstream_id' => $workstream2_2->id,
            'title' => 'Design process improvements',
            'description' => 'Develop detailed recommendations for process improvements including equipment modifications, parameter adjustments, and workflow changes.',
            'estimated_hours' => 25,
            'required_skills' => ['Process Optimization', 'Chemical Engineering', 'Cost-Benefit Analysis'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Process improvement recommendations with implementation plan',
            'acceptance_criteria' => ['Recommendations are detailed', 'Cost-benefit analysis included', 'Implementation plan provided', 'ROI estimates included'],
            'status' => 'pending',
            'priority' => 'medium',
        ]);
    }

    private function createTeams($challenges, $volunteers): array
    {
        $teams = [];

        // Team 1: AI Chatbot Development Team
        $team1 = Team::create([
            'challenge_id' => $challenges[0]->id,
            'name' => 'AI Chatbot Squad',
            'description' => 'Full-stack team focused on building the AI-powered customer support chatbot',
            'status' => 'active',
            'leader_id' => $volunteers[0]->id, // John Developer
            'team_match_score' => 92.5,
            'estimated_total_hours' => 150,
            'formation_completed_at' => now()->subDays(10),
        ]);

        // Add team members
        TeamMember::create([
            'team_id' => $team1->id,
            'volunteer_id' => $volunteers[0]->id,
            'role' => 'leader',
            'status' => 'accepted',
            'role_description' => 'Team Leader & Backend Developer - Responsible for API development and team coordination',
            'assigned_skills' => ['PHP', 'Laravel', 'API Design'],
            'invited_at' => now()->subDays(12),
            'accepted_at' => now()->subDays(11),
        ]);

        TeamMember::create([
            'team_id' => $team1->id,
            'volunteer_id' => $volunteers[2]->id,
            'role' => 'specialist',
            'status' => 'accepted',
            'role_description' => 'ML Engineer - Implement NLP and intent recognition',
            'assigned_skills' => ['Python', 'Machine Learning', 'NLP'],
            'invited_at' => now()->subDays(12),
            'accepted_at' => now()->subDays(10),
        ]);

        TeamMember::create([
            'team_id' => $team1->id,
            'volunteer_id' => $volunteers[3]->id,
            'role' => 'specialist',
            'status' => 'invited',
            'role_description' => 'UX Designer - Design chat interface and user experience',
            'assigned_skills' => ['Figma', 'User Research', 'Prototyping'],
            'invited_at' => now()->subDays(5),
        ]);

        $teams[] = $team1;

        // Team 2: Chemical Process Optimization Team
        $team2 = Team::create([
            'challenge_id' => $challenges[1]->id,
            'name' => 'Process Optimization Team',
            'description' => 'Chemical engineers focused on production process analysis and improvement',
            'status' => 'forming',
            'leader_id' => $volunteers[1]->id, // Sarah Chen
            'team_match_score' => 88.0,
            'estimated_total_hours' => 60,
        ]);

        TeamMember::create([
            'team_id' => $team2->id,
            'volunteer_id' => $volunteers[1]->id,
            'role' => 'leader',
            'status' => 'accepted',
            'role_description' => 'Team Leader & Chemical Engineer - Lead process analysis',
            'assigned_skills' => ['Process Optimization', 'Chemical Analysis'],
            'invited_at' => now()->subDays(7),
            'accepted_at' => now()->subDays(6),
        ]);

        TeamMember::create([
            'team_id' => $team2->id,
            'volunteer_id' => $volunteers[5]->id,
            'role' => 'specialist',
            'status' => 'invited',
            'role_description' => 'Project Manager - Coordinate activities and manage timeline',
            'assigned_skills' => ['Agile', 'Risk Management'],
            'invited_at' => now()->subDays(3),
        ]);

        $teams[] = $team2;

        return $teams;
    }

    private function createTaskAssignments($challenges, $volunteers): void
    {
        // Get tasks for assignments
        $challengeIds = array_column($challenges, 'id');
        $tasks = Task::whereIn('challenge_id', $challengeIds)->get();

        // Assignment 1: API Development - In Progress (John)
        $task1 = $tasks->where('title', 'Design and implement RESTful API')->first();
        if ($task1) {
            TaskAssignment::create([
                'task_id' => $task1->id,
                'volunteer_id' => $volunteers[0]->id,
                'invitation_status' => 'in_progress',
                'ai_match_score' => 95,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Excellent match based on extensive PHP and Laravel experience',
                    'strengths' => [
                        'Expert-level PHP and Laravel skills (8 years)',
                        'Strong API design experience',
                        'Database expertise with MySQL'
                    ],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(10),
                'responded_at' => now()->subDays(9),
                'started_at' => now()->subDays(9),
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(10),
            ]);
        }

        // Assignment 2: NLP Implementation - Accepted (Mike)
        $task2 = $tasks->where('title', 'Implement NLP intent recognition')->first();
        if ($task2) {
            TaskAssignment::create([
                'task_id' => $task2->id,
                'volunteer_id' => $volunteers[2]->id,
                'invitation_status' => 'accepted',
                'ai_match_score' =>98,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Perfect match for ML and NLP requirements',
                    'strengths' => [
                        'Expert in Machine Learning and Python',
                        'Experienced with TensorFlow',
                        'Strong data science background'
                    ],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(8),
                'responded_at' => now()->subDays(7),
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(8),
            ]);
        }

        // Assignment 3: Chat Widget - Completed with Submission (John - before current task)
        $task3 = $tasks->where('title', 'Build chat widget UI component')->first();
        if ($task3) {
            TaskAssignment::create([
                'task_id' => $task3->id,
                'volunteer_id' => $volunteers[0]->id,
                'invitation_status' => 'submitted',
                'ai_match_score' =>88,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Good match with strong JavaScript and React skills',
                    'strengths' => [
                        'Advanced React knowledge',
                        'Strong JavaScript fundamentals'
                    ],
                    'gaps' => ['Could improve CSS skills']
                ]),
                'invited_at' => now()->subDays(25),
                'responded_at' => now()->subDays(24),
                'started_at' => now()->subDays(24),
                'completed_at' => now()->subDays(12),
                'actual_hours' => 32,
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(25),
            ]);
        }

        // Assignment 4: Process Mapping - Invited (Sarah)
        $task4 = $tasks->where('title', 'Map current production workflow')->first();
        if ($task4) {
            TaskAssignment::create([
                'task_id' => $task4->id,
                'volunteer_id' => $volunteers[1]->id,
                'invitation_status' => 'invited',
                'ai_match_score' =>90,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Strong match for chemical engineering process analysis',
                    'strengths' => [
                        'Advanced process optimization skills',
                        '5 years chemical engineering experience',
                        'Strong technical documentation abilities'
                    ],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(3),
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(4),
            ]);
        }

        // Assignment 5: UX Designer - Invited (Emma)
        $task5 = $tasks->where('title', 'Admin dashboard for monitoring')->first();
        if ($task5) {
            TaskAssignment::create([
                'task_id' => $task5->id,
                'volunteer_id' => $volunteers[3]->id,
                'invitation_status' => 'invited',
                'ai_match_score' =>85,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Good fit for dashboard design and user experience',
                    'strengths' => [
                        'Expert Figma skills',
                        'Strong user research background',
                        'Experience with wireframing and prototyping'
                    ],
                    'gaps' => ['Limited experience with data visualization']
                ]),
                'invited_at' => now()->subDays(2),
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(3),
            ]);
        }

        // Assignment 6: Declined Example (Lisa declined Salesforce integration)
        $task6 = $tasks->where('title', 'Salesforce CRM integration')->first();
        if ($task6) {
            TaskAssignment::create([
                'task_id' => $task6->id,
                'volunteer_id' => $volunteers[4]->id,
                'invitation_status' => 'declined',
                'ai_match_score' =>45,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Partial match - marketing background but limited technical integration experience',
                    'strengths' => [
                        'Familiar with CRM systems from marketing perspective'
                    ],
                    'gaps' => [
                        'Limited API integration experience',
                        'No Salesforce development background'
                    ],
                    'rejection_reason' => 'Task requires technical skills outside my expertise area'
                ]),
                'invited_at' => now()->subDays(6),
                'responded_at' => now()->subDays(5),
            ]);
        }
    }

    private function createWorkSubmissions(): void
    {
        // Get the completed task assignment
        $completedAssignment = TaskAssignment::where('invitation_status', 'submitted')->first();

        if ($completedAssignment) {
            WorkSubmission::create([
                'task_assignment_id' => $completedAssignment->id,
                'task_id' => $completedAssignment->task_id,
                'volunteer_id' => $completedAssignment->volunteer_id,
                'description' => "I've successfully built a responsive chat widget component using React. The implementation includes:\n\n1. **Core Features:**\n   - Real-time message display with typing indicators\n   - Message history with infinite scroll\n   - File upload support (images, documents)\n   - Emoji picker integration\n   - Mobile-responsive design\n\n2. **Technical Implementation:**\n   - Built with React Hooks for state management\n   - WebSocket integration for real-time updates\n   - Optimized rendering with React.memo\n   - Accessibility features (ARIA labels, keyboard navigation)\n\n3. **Browser Compatibility:**\n   - Tested on Chrome, Firefox, Safari, Edge\n   - Mobile testing on iOS and Android\n\n4. **Performance:**\n   - Lazy loading for message history\n   - Image optimization\n   - Minimal bundle size (45KB gzipped)\n\nThe widget is production-ready and follows the design specifications provided.",
                'deliverable_url' => 'https://github.com/johndeveloper/chat-widget-demo',
                'attachments' => [],
                'hours_worked' => 32,
                'status' => 'approved',
                'ai_analysis_status' => 'completed',
                'ai_quality_score' => 88,
                'ai_feedback' => json_encode([
                    'feedback' => 'Excellent implementation with comprehensive features and good documentation. The solution demonstrates strong technical skills and attention to detail.',
                    'strengths' => [
                        'Complete feature implementation covering all requirements',
                        'Good code quality with React best practices',
                        'Thorough testing across browsers and devices',
                        'Clear documentation and comments'
                    ],
                    'areas_for_improvement' => [
                        'Could add more unit test coverage',
                        'Consider adding error boundary for better error handling'
                    ]
                ]),
                'submitted_at' => now()->subDays(12),
                'reviewed_at' => now()->subDays(11),
            ]);
        }
    }

    private function createNdaSignings($volunteers, $challenges): void
    {
        // Sign challenge-specific NDAs for volunteers working on challenges requiring NDA
        foreach ($volunteers as $index => $volunteer) {
            // Sign NDA for challenge 1 (AI Chatbot) - for first 3 volunteers
            if ($index < 3 && $challenges[0]->requires_nda) {
                ChallengeNdaSigning::create([
                    'user_id' => $volunteer->user_id,
                    'challenge_id' => $challenges[0]->id,
                    'nda_version' => '1.0',
                    'ip_address' => '127.0.0.1',
                    'is_valid' => true,
                    'signed_at' => now()->subDays(12),
                ]);
            }

            // Sign NDA for challenge 2 (Chemical Process) - for chemical engineer
            if ($index == 1 && $challenges[1]->requires_nda) {
                ChallengeNdaSigning::create([
                    'user_id' => $volunteer->user_id,
                    'challenge_id' => $challenges[1]->id,
                    'nda_version' => '1.0',
                    'ip_address' => '127.0.0.1',
                    'is_valid' => true,
                    'signed_at' => now()->subDays(7),
                ]);
            }
        }
    }
}
