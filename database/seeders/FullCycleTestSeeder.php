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
use App\Models\TeamMessage;
use App\Models\WorkSubmission;
use App\Models\ChallengeNdaSigning;
use App\Models\Idea;
use App\Models\IdeaVote;
use App\Models\ChallengeComment;
use App\Models\CommentVote;
use App\Models\Review;
use App\Models\Certificate;
use App\Models\Notification;
use App\Models\ReputationHistory;
use App\Models\BugReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FullCycleTestSeeder extends Seeder
{
    /**
     * Full platform cycle test seeder covering ALL workflow states
     *
     * This seeder creates comprehensive test data including:
     * - Admin users for platform administration
     * - Companies with varying profiles and verification states
     * - Volunteers with diverse skills, experience levels, and NDA states
     * - Challenges in all states (submitted, analyzing, active, completed, rejected)
     * - Tasks with various statuses and complexity levels
     * - Task assignments in all invitation states
     * - Teams with members in different states
     * - Team chat messages for collaboration testing
     * - Work submissions with AI analysis and reviews
     * - Community discussions with ideas and comments
     * - Idea and comment voting
     * - Reviews and ratings
     * - Certificates for completed work
     * - Notifications for all user types
     * - Reputation history tracking
     * - Bug reports for testing
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║     🚀 MINDOVA FULL CYCLE TEST DATA SEEDER                   ║');
        $this->command->info('║     Comprehensive test data for manual testing               ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');

        // Clear existing data
        $this->clearExistingData();

        // Create base data
        $this->command->info('📊 Creating base data...');
        $admin = $this->createAdminUser();
        $companies = $this->createCompanies();
        $volunteers = $this->createVolunteers();

        $this->command->info('🎯 Creating challenges (all workflow states)...');
        $challenges = $this->createChallenges($companies);

        $this->command->info('📋 Creating workstreams and tasks...');
        $this->createWorkstreamsAndTasks($challenges);

        $this->command->info('👥 Forming teams...');
        $teams = $this->createTeams($challenges, $volunteers);

        $this->command->info('💬 Adding team chat messages...');
        $this->createTeamMessages($teams, $volunteers);

        $this->command->info('✉️  Creating task assignments (all states)...');
        $this->createTaskAssignments($challenges, $volunteers);

        $this->command->info('📤 Creating work submissions...');
        $this->createWorkSubmissions($volunteers);

        $this->command->info('⭐ Adding reviews and ratings...');
        $this->createReviews($companies);

        $this->command->info('🏆 Creating certificates...');
        $this->createCertificates($volunteers, $challenges, $companies);

        $this->command->info('💡 Adding community discussions...');
        $this->createCommunityDiscussions($challenges, $volunteers);

        $this->command->info('🗳️  Adding votes on ideas and comments...');
        $this->createVotes($volunteers);

        $this->command->info('🔒 Signing NDAs...');
        $this->createNdaSignings($volunteers, $challenges);

        $this->command->info('🔔 Creating notifications...');
        $this->createNotifications($volunteers, $companies, $challenges);

        $this->command->info('📈 Adding reputation history...');
        $this->createReputationHistory($volunteers);

        $this->command->info('🐛 Adding bug reports...');
        $this->createBugReports($volunteers, $companies);

        $this->command->info('');
        $this->command->info('✅ Full Cycle Test Data Seeding Completed!');
        $this->command->info('');
        $this->printLoginCredentials();
        $this->printTestScenarios();
    }

    private function clearExistingData(): void
    {
        $this->command->info('🗑️  Clearing existing data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'bug_reports', 'reputation_history', 'certificates', 'reviews',
            'comment_votes', 'challenge_comments', 'idea_votes', 'ideas',
            'team_messages', 'work_submissions', 'task_assignments',
            'team_members', 'teams', 'tasks', 'workstreams',
            'challenge_nda_signings', 'challenges', 'notifications',
            'volunteer_skills', 'volunteers', 'companies', 'users'
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('  ✓ Data cleared');
    }

    private function createAdminUser(): User
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mindova.com',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('  ✓ Created admin user');
        return $admin;
    }

    private function createCompanies(): array
    {
        $companies = [];

        // Company 1: Tech Startup (Active, Verified)
        $user1 = User::create([
            'name' => 'TechCorp Solutions',
            'email' => 'tech@company.com',
            'password' => Hash::make('password123'),
            'user_type' => 'company',
            'email_verified_at' => now(),
        ]);
        $companies[] = Company::create([
            'user_id' => $user1->id,
            'company_name' => 'TechCorp Solutions',
            'website' => 'https://techcorp.com',
            'industry' => 'Technology',
            'description' => 'Leading AI and machine learning solutions provider specializing in enterprise software.',
            'total_challenges_submitted' => 3,
        ]);

        // Company 2: Manufacturing (Active, Verified)
        $user2 = User::create([
            'name' => 'Global Manufacturing Inc',
            'email' => 'manufacturing@company.com',
            'password' => Hash::make('password123'),
            'user_type' => 'company',
            'email_verified_at' => now(),
        ]);
        $companies[] = Company::create([
            'user_id' => $user2->id,
            'company_name' => 'Global Manufacturing Inc',
            'website' => 'https://globalmanuf.com',
            'industry' => 'Manufacturing',
            'description' => 'Sustainable manufacturing solutions for chemical and industrial processes.',
            'total_challenges_submitted' => 2,
        ]);

        // Company 3: Healthcare Startup (Verified)
        $user3 = User::create([
            'name' => 'HealthTech Innovations',
            'email' => 'health@company.com',
            'password' => Hash::make('password123'),
            'user_type' => 'company',
            'email_verified_at' => now(),
        ]);
        $companies[] = Company::create([
            'user_id' => $user3->id,
            'company_name' => 'HealthTech Innovations',
            'website' => 'https://healthtech.io',
            'industry' => 'Healthcare',
            'description' => 'Digital health platform connecting patients with healthcare providers through AI-powered analytics.',
            'total_challenges_submitted' => 1,
        ]);

        // Company 4: E-commerce (Verified)
        $user4 = User::create([
            'name' => 'ShopFlow Commerce',
            'email' => 'shop@company.com',
            'password' => Hash::make('password123'),
            'user_type' => 'company',
            'email_verified_at' => now(),
        ]);
        $companies[] = Company::create([
            'user_id' => $user4->id,
            'company_name' => 'ShopFlow Commerce',
            'website' => 'https://shopflow.com',
            'industry' => 'E-commerce',
            'description' => 'Next-generation e-commerce platform with AI-powered personalization.',
            'total_challenges_submitted' => 1,
        ]);

        // Company 5: NEW Company (Unverified email - for testing verification flow)
        $user5 = User::create([
            'name' => 'NewStartup Inc',
            'email' => 'newstartup@company.com',
            'password' => Hash::make('password123'),
            'user_type' => 'company',
            'email_verified_at' => null, // Not verified
        ]);
        $companies[] = Company::create([
            'user_id' => $user5->id,
            'company_name' => 'NewStartup Inc',
            'website' => 'https://newstartup.com',
            'industry' => 'FinTech',
            'description' => 'New fintech startup seeking volunteer expertise.',
            'total_challenges_submitted' => 0,
        ]);

        $this->command->info('  ✓ Created ' . count($companies) . ' companies (1 unverified)');
        return $companies;
    }

    private function createVolunteers(): array
    {
        $volunteers = [];

        // Volunteer 1: Senior Software Engineer (Expert, NDA Signed, High Rep)
        $user1 = User::create([
            'name' => 'Alex Johnson',
            'email' => 'alex@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer1 = Volunteer::create([
            'user_id' => $user1->id,
            'field' => 'Software Engineering',
            'experience_level' => 'Expert',
            'years_of_experience' => 10,
            'availability_hours_per_week' => 20,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(30),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 92,
            'cv_file_path' => 'cvs/alex_johnson_cv.pdf',
        ]);
        $this->addSkills($volunteer1, [
            ['skill_name' => 'PHP', 'proficiency_level' => 'expert', 'years_of_experience' => 10],
            ['skill_name' => 'Laravel', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
            ['skill_name' => 'JavaScript', 'proficiency_level' => 'expert', 'years_of_experience' => 10],
            ['skill_name' => 'React', 'proficiency_level' => 'advanced', 'years_of_experience' => 6],
            ['skill_name' => 'Vue.js', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'API Design', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
            ['skill_name' => 'MySQL', 'proficiency_level' => 'expert', 'years_of_experience' => 10],
        ]);
        $volunteers[] = $volunteer1;

        // Volunteer 2: ML/AI Specialist (Expert, NDA Signed, Highest Rep)
        $user2 = User::create([
            'name' => 'Dr. Sarah Chen',
            'email' => 'sarah@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer2 = Volunteer::create([
            'user_id' => $user2->id,
            'field' => 'Data Science',
            'experience_level' => 'Expert',
            'years_of_experience' => 8,
            'availability_hours_per_week' => 15,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(25),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 95,
            'cv_file_path' => 'cvs/sarah_chen_cv.pdf',
        ]);
        $this->addSkills($volunteer2, [
            ['skill_name' => 'Python', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
            ['skill_name' => 'Machine Learning', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
            ['skill_name' => 'TensorFlow', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'PyTorch', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'NLP', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'Data Visualization', 'proficiency_level' => 'advanced', 'years_of_experience' => 7],
        ]);
        $volunteers[] = $volunteer2;

        // Volunteer 3: Chemical Engineer (Mid, NDA Signed)
        $user3 = User::create([
            'name' => 'Michael Rodriguez',
            'email' => 'michael@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer3 = Volunteer::create([
            'user_id' => $user3->id,
            'field' => 'Chemical Engineering',
            'experience_level' => 'Mid',
            'years_of_experience' => 6,
            'availability_hours_per_week' => 12,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(20),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 78,
            'cv_file_path' => 'cvs/michael_rodriguez_cv.pdf',
        ]);
        $this->addSkills($volunteer3, [
            ['skill_name' => 'Process Optimization', 'proficiency_level' => 'advanced', 'years_of_experience' => 6],
            ['skill_name' => 'Chemical Analysis', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'Quality Control', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'Safety Compliance', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
        ]);
        $volunteers[] = $volunteer3;

        // Volunteer 4: UX/UI Designer (Mid, NDA Signed)
        $user4 = User::create([
            'name' => 'Emma Williams',
            'email' => 'emma@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer4 = Volunteer::create([
            'user_id' => $user4->id,
            'field' => 'UX Design',
            'experience_level' => 'Mid',
            'years_of_experience' => 5,
            'availability_hours_per_week' => 15,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(15),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 82,
            'cv_file_path' => 'cvs/emma_williams_cv.pdf',
        ]);
        $this->addSkills($volunteer4, [
            ['skill_name' => 'Figma', 'proficiency_level' => 'expert', 'years_of_experience' => 5],
            ['skill_name' => 'Adobe XD', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'User Research', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'Wireframing', 'proficiency_level' => 'expert', 'years_of_experience' => 5],
            ['skill_name' => 'Prototyping', 'proficiency_level' => 'expert', 'years_of_experience' => 4],
        ]);
        $volunteers[] = $volunteer4;

        // Volunteer 5: Frontend Developer (Mid, NDA Signed)
        $user5 = User::create([
            'name' => 'James Lee',
            'email' => 'james@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer5 = Volunteer::create([
            'user_id' => $user5->id,
            'field' => 'Frontend Development',
            'experience_level' => 'Mid',
            'years_of_experience' => 4,
            'availability_hours_per_week' => 10,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(10),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 75,
            'cv_file_path' => 'cvs/james_lee_cv.pdf',
        ]);
        $this->addSkills($volunteer5, [
            ['skill_name' => 'JavaScript', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'React', 'proficiency_level' => 'expert', 'years_of_experience' => 4],
            ['skill_name' => 'TypeScript', 'proficiency_level' => 'advanced', 'years_of_experience' => 3],
            ['skill_name' => 'CSS', 'proficiency_level' => 'expert', 'years_of_experience' => 4],
            ['skill_name' => 'Tailwind CSS', 'proficiency_level' => 'advanced', 'years_of_experience' => 2],
        ]);
        $volunteers[] = $volunteer5;

        // Volunteer 6: Marketing Specialist (Mid, NDA Signed)
        $user6 = User::create([
            'name' => 'Sophia Martinez',
            'email' => 'sophia@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer6 = Volunteer::create([
            'user_id' => $user6->id,
            'field' => 'Marketing',
            'experience_level' => 'Mid',
            'years_of_experience' => 6,
            'availability_hours_per_week' => 10,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(5),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 70,
            'cv_file_path' => 'cvs/sophia_martinez_cv.pdf',
        ]);
        $this->addSkills($volunteer6, [
            ['skill_name' => 'Digital Marketing', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'SEO', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'Content Marketing', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'Social Media Marketing', 'proficiency_level' => 'expert', 'years_of_experience' => 6],
            ['skill_name' => 'Google Analytics', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
        ]);
        $volunteers[] = $volunteer6;

        // Volunteer 7: Project Manager (Manager, NDA Signed, Top Rep)
        $user7 = User::create([
            'name' => 'David Brown',
            'email' => 'david@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer7 = Volunteer::create([
            'user_id' => $user7->id,
            'field' => 'Project Management',
            'experience_level' => 'Manager',
            'years_of_experience' => 12,
            'availability_hours_per_week' => 15,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(40),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 98,
            'cv_file_path' => 'cvs/david_brown_cv.pdf',
        ]);
        $this->addSkills($volunteer7, [
            ['skill_name' => 'Agile', 'proficiency_level' => 'expert', 'years_of_experience' => 12],
            ['skill_name' => 'Scrum', 'proficiency_level' => 'expert', 'years_of_experience' => 10],
            ['skill_name' => 'JIRA', 'proficiency_level' => 'expert', 'years_of_experience' => 8],
            ['skill_name' => 'Risk Management', 'proficiency_level' => 'expert', 'years_of_experience' => 12],
            ['skill_name' => 'Stakeholder Management', 'proficiency_level' => 'expert', 'years_of_experience' => 12],
        ]);
        $volunteers[] = $volunteer7;

        // Volunteer 8: Backend Developer (Mid, NDA Signed)
        $user8 = User::create([
            'name' => 'Olivia Taylor',
            'email' => 'olivia@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer8 = Volunteer::create([
            'user_id' => $user8->id,
            'field' => 'Backend Development',
            'experience_level' => 'Mid',
            'years_of_experience' => 5,
            'availability_hours_per_week' => 12,
            'general_nda_signed' => true,
            'general_nda_signed_at' => now()->subDays(3),
            'general_nda_version' => '1.0',
            'ai_analysis_status' => 'completed',
            'reputation_score' => 68,
            'cv_file_path' => 'cvs/olivia_taylor_cv.pdf',
        ]);
        $this->addSkills($volunteer8, [
            ['skill_name' => 'Node.js', 'proficiency_level' => 'expert', 'years_of_experience' => 5],
            ['skill_name' => 'Express.js', 'proficiency_level' => 'expert', 'years_of_experience' => 5],
            ['skill_name' => 'MongoDB', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'PostgreSQL', 'proficiency_level' => 'advanced', 'years_of_experience' => 3],
            ['skill_name' => 'Docker', 'proficiency_level' => 'intermediate', 'years_of_experience' => 2],
        ]);
        $volunteers[] = $volunteer8;

        // Volunteer 9: NEW Volunteer (No NDA signed - for testing NDA flow)
        $user9 = User::create([
            'name' => 'Ryan Wilson',
            'email' => 'ryan@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer9 = Volunteer::create([
            'user_id' => $user9->id,
            'field' => 'DevOps',
            'experience_level' => 'Mid',
            'years_of_experience' => 4,
            'availability_hours_per_week' => 8,
            'general_nda_signed' => false, // Not signed
            'general_nda_signed_at' => null,
            'general_nda_version' => null,
            'ai_analysis_status' => 'completed',
            'reputation_score' => 50,
            'cv_file_path' => 'cvs/ryan_wilson_cv.pdf',
        ]);
        $this->addSkills($volunteer9, [
            ['skill_name' => 'AWS', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'Docker', 'proficiency_level' => 'expert', 'years_of_experience' => 4],
            ['skill_name' => 'Kubernetes', 'proficiency_level' => 'advanced', 'years_of_experience' => 3],
            ['skill_name' => 'CI/CD', 'proficiency_level' => 'expert', 'years_of_experience' => 4],
        ]);
        $volunteers[] = $volunteer9;

        // Volunteer 10: NEW Volunteer (AI Analysis Pending - for testing CV analysis)
        $user10 = User::create([
            'name' => 'Lisa Anderson',
            'email' => 'lisa@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);
        $volunteer10 = Volunteer::create([
            'user_id' => $user10->id,
            'field' => 'Business Analysis',
            'experience_level' => 'Mid',
            'years_of_experience' => 5,
            'availability_hours_per_week' => 10,
            'general_nda_signed' => false,
            'general_nda_signed_at' => null,
            'general_nda_version' => null,
            'ai_analysis_status' => 'pending', // Pending analysis
            'reputation_score' => 50,
            'cv_file_path' => 'cvs/lisa_anderson_cv.pdf',
        ]);
        $this->addSkills($volunteer10, [
            ['skill_name' => 'Business Analysis', 'proficiency_level' => 'advanced', 'years_of_experience' => 5],
            ['skill_name' => 'Requirements Gathering', 'proficiency_level' => 'expert', 'years_of_experience' => 5],
            ['skill_name' => 'SQL', 'proficiency_level' => 'intermediate', 'years_of_experience' => 3],
        ]);
        $volunteers[] = $volunteer10;

        // Volunteer 11: Unverified Email (for testing email verification)
        $user11 = User::create([
            'name' => 'Mark Thompson',
            'email' => 'mark@volunteer.com',
            'password' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'email_verified_at' => null, // Not verified
        ]);
        $volunteer11 = Volunteer::create([
            'user_id' => $user11->id,
            'field' => 'Mobile Development',
            'experience_level' => 'Mid',
            'years_of_experience' => 4,
            'availability_hours_per_week' => 15,
            'general_nda_signed' => false,
            'general_nda_signed_at' => null,
            'general_nda_version' => null,
            'ai_analysis_status' => 'pending',
            'reputation_score' => 50,
            'cv_file_path' => null,
        ]);
        $this->addSkills($volunteer11, [
            ['skill_name' => 'React Native', 'proficiency_level' => 'advanced', 'years_of_experience' => 4],
            ['skill_name' => 'Flutter', 'proficiency_level' => 'intermediate', 'years_of_experience' => 2],
            ['skill_name' => 'Swift', 'proficiency_level' => 'intermediate', 'years_of_experience' => 2],
        ]);
        $volunteers[] = $volunteer11;

        $this->command->info('  ✓ Created ' . count($volunteers) . ' volunteers (various states)');
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

        // ═══════════════════════════════════════════════════════════════
        // COMPLETED CHALLENGES
        // ═══════════════════════════════════════════════════════════════

        // Challenge 1: COMPLETED - Chemical Engineering
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Batch Reactor Temperature Control Optimization',
            'original_description' => 'Optimize temperature control system for our batch reactor to reduce energy consumption and improve product consistency.',
            'refined_brief' => 'Analyze current temperature control systems in batch reactor operations. Develop optimized PID control parameters to reduce energy consumption by 15% while maintaining ±2°C temperature precision. Deliver process simulation model and implementation guide.',
            'field' => 'Chemical Engineering',
            'status' => 'completed',
            'challenge_type' => 'team_execution',
            'complexity_level' => 7,
            'score' => 7,
            'requires_nda' => true,
            'deadline' => now()->subDays(5)->format('Y-m-d'),
            'created_at' => now()->subDays(90),
            'completed_at' => now()->subDays(7),
        ]);

        // ═══════════════════════════════════════════════════════════════
        // ACTIVE CHALLENGES
        // ═══════════════════════════════════════════════════════════════

        // Challenge 2: ACTIVE - Team Working
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Distillation Column Efficiency Improvement',
            'original_description' => 'Need to improve distillation column separation efficiency and reduce reflux ratio to save energy costs.',
            'refined_brief' => 'Conduct detailed analysis of distillation column performance. Optimize reflux ratio, feed location, and tray design to improve separation efficiency by 12% and reduce energy consumption by 20%.',
            'field' => 'Chemical Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 8,
            'score' => 8,
            'requires_nda' => true,
            'deadline' => now()->addDays(45)->format('Y-m-d'),
            'created_at' => now()->subDays(20),
        ]);

        // Challenge 3: ACTIVE - Tasks Assigned
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Chemical Production Process Optimization',
            'original_description' => 'Optimize our chemical production process to reduce waste and improve efficiency.',
            'refined_brief' => 'Conduct comprehensive analysis of current production workflow, identify bottlenecks, and recommend improvements to reduce waste by 15% and improve efficiency by 20%.',
            'field' => 'Chemical Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 7,
            'score' => 7,
            'requires_nda' => true,
            'deadline' => now()->addDays(60)->format('Y-m-d'),
            'created_at' => now()->subDays(15),
        ]);

        // Challenge 4: ACTIVE - Software Engineering
        $challenges[] = Challenge::create([
            'company_id' => $companies[0]->id,
            'source_type' => 'company',
            'title' => 'AI Customer Support Chatbot Development',
            'original_description' => 'Build an intelligent customer support chatbot using NLP to handle common queries and integrate with our CRM.',
            'refined_brief' => 'Develop AI-powered chatbot with NLP capabilities for customer support. Must handle 80% of common queries autonomously, integrate with Salesforce CRM, provide real-time analytics dashboard.',
            'field' => 'Software Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 8,
            'score' => 8,
            'requires_nda' => true,
            'deadline' => now()->addDays(60)->format('Y-m-d'),
            'created_at' => now()->subDays(25),
        ]);

        // ═══════════════════════════════════════════════════════════════
        // ANALYZING CHALLENGES
        // ═══════════════════════════════════════════════════════════════

        // Challenge 5: ANALYZING
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Catalytic Reactor Performance Enhancement',
            'original_description' => 'Our catalytic reactor is experiencing declining conversion rates and increased pressure drop. Need comprehensive analysis and solution.',
            'refined_brief' => 'Investigate catalyst deactivation mechanisms, analyze reactor bed pressure drop issues, and recommend catalyst regeneration schedule or replacement.',
            'field' => 'Chemical Engineering',
            'status' => 'analyzing',
            'challenge_type' => 'team_execution',
            'complexity_level' => 9,
            'score' => 9,
            'requires_nda' => true,
            'deadline' => now()->addDays(90)->format('Y-m-d'),
            'created_at' => now()->subDays(3),
        ]);

        // ═══════════════════════════════════════════════════════════════
        // SUBMITTED CHALLENGES (Awaiting AI Analysis)
        // ═══════════════════════════════════════════════════════════════

        // Challenge 6: SUBMITTED
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Waste Heat Recovery System Design',
            'original_description' => 'Design a waste heat recovery system to capture heat from our reactor exhaust streams.',
            'refined_brief' => null,
            'field' => 'Chemical Engineering',
            'status' => 'submitted',
            'challenge_type' => 'team_execution',
            'complexity_level' => null,
            'score' => null,
            'requires_nda' => false,
            'deadline' => now()->addDays(60)->format('Y-m-d'),
            'created_at' => now()->subHours(2),
        ]);

        // ═══════════════════════════════════════════════════════════════
        // REJECTED CHALLENGE
        // ═══════════════════════════════════════════════════════════════

        // Challenge 7: REJECTED
        $challenges[] = Challenge::create([
            'company_id' => $companies[0]->id,
            'source_type' => 'company',
            'title' => 'Hack Competitor Website',
            'original_description' => 'We need to analyze competitor website security.',
            'refined_brief' => null,
            'field' => 'Software Engineering',
            'status' => 'rejected',
            'challenge_type' => 'team_execution',
            'complexity_level' => null,
            'score' => null,
            'requires_nda' => false,
            'deadline' => now()->addDays(30)->format('Y-m-d'),
            'rejection_reason' => 'This challenge appears to request unauthorized access to competitor systems, which violates our platform policies and potentially applicable laws.',
            'created_at' => now()->subDays(10),
        ]);

        // ═══════════════════════════════════════════════════════════════
        // COMMUNITY DISCUSSION CHALLENGES
        // ═══════════════════════════════════════════════════════════════

        // Challenge 8: COMMUNITY - Chemical Engineering
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Corrosion Prevention in Chemical Piping Systems',
            'original_description' => 'Experiencing corrosion issues in our chemical transfer piping. Looking for community input.',
            'refined_brief' => 'Community discussion on corrosion prevention in chemical piping systems handling acidic solutions (pH 2-4) at 80-120°C.',
            'field' => 'Chemical Engineering',
            'status' => 'active',
            'challenge_type' => 'community_discussion',
            'complexity_level' => 2,
            'score' => 2,
            'requires_nda' => false,
            'deadline' => now()->addDays(30)->format('Y-m-d'),
            'created_at' => now()->subDays(7),
        ]);

        // Challenge 9: COMMUNITY - Software Engineering
        $challenges[] = Challenge::create([
            'company_id' => $companies[0]->id,
            'source_type' => 'company',
            'title' => 'Best Practices for API Rate Limiting',
            'original_description' => 'We need community input on implementing rate limiting for our public APIs.',
            'refined_brief' => 'Seeking community discussion on API rate limiting strategies including algorithm recommendations and monitoring approaches.',
            'field' => 'Software Engineering',
            'status' => 'active',
            'challenge_type' => 'community_discussion',
            'complexity_level' => 2,
            'score' => 2,
            'requires_nda' => false,
            'deadline' => now()->addDays(25)->format('Y-m-d'),
            'created_at' => now()->subDays(5),
        ]);

        // Challenge 10: COMMUNITY - Data Science
        $challenges[] = Challenge::create([
            'company_id' => $companies[2]->id,
            'source_type' => 'company',
            'title' => 'Feature Engineering for Healthcare Predictions',
            'original_description' => 'Exploring feature engineering techniques for patient outcome predictions.',
            'refined_brief' => 'Community discussion on effective feature engineering for healthcare ML models.',
            'field' => 'Data Science',
            'status' => 'active',
            'challenge_type' => 'community_discussion',
            'complexity_level' => 2,
            'score' => 2,
            'requires_nda' => false,
            'deadline' => now()->addDays(30)->format('Y-m-d'),
            'created_at' => now()->subDays(4),
        ]);

        // Challenge 11: COMMUNITY - UX Design
        $challenges[] = Challenge::create([
            'company_id' => $companies[3]->id,
            'source_type' => 'company',
            'title' => 'Mobile Checkout Flow Optimization',
            'original_description' => 'Our mobile checkout has a high abandonment rate. Looking for UX insights.',
            'refined_brief' => 'Community discussion on optimizing mobile e-commerce checkout with 68% cart abandonment.',
            'field' => 'UX Design',
            'status' => 'active',
            'challenge_type' => 'community_discussion',
            'complexity_level' => 1,
            'score' => 1,
            'requires_nda' => false,
            'deadline' => now()->addDays(20)->format('Y-m-d'),
            'created_at' => now()->subDays(3),
        ]);

        // Challenge 12: COMMUNITY - Marketing
        $challenges[] = Challenge::create([
            'company_id' => $companies[3]->id,
            'source_type' => 'company',
            'title' => 'Social Media Strategy for B2B SaaS',
            'original_description' => 'Need community insights on effective social media strategies for B2B SaaS products.',
            'refined_brief' => 'Community discussion on B2B SaaS social media marketing strategies.',
            'field' => 'Marketing',
            'status' => 'active',
            'challenge_type' => 'community_discussion',
            'complexity_level' => 1,
            'score' => 1,
            'requires_nda' => false,
            'deadline' => now()->addDays(15)->format('Y-m-d'),
            'created_at' => now()->subDays(2),
        ]);

        // ═══════════════════════════════════════════════════════════════
        // ADDITIONAL COMPLEXITY LEVELS (3-10)
        // ═══════════════════════════════════════════════════════════════

        // Complexity 3
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'pH Control System Calibration Procedure',
            'original_description' => 'Need a standardized calibration procedure for our pH control systems.',
            'refined_brief' => 'Develop comprehensive pH meter calibration protocol including buffer selection and drift detection.',
            'field' => 'Chemical Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 3,
            'score' => 3,
            'requires_nda' => false,
            'deadline' => now()->addDays(30)->format('Y-m-d'),
            'created_at' => now()->subDays(1),
        ]);

        // Complexity 5
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Automated Sampling System for Quality Control',
            'original_description' => 'Design an automated sampling system for continuous quality monitoring.',
            'refined_brief' => 'Design automated sampling system with online analyzers for polymer reactor quality control.',
            'field' => 'Chemical Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 5,
            'score' => 5,
            'requires_nda' => true,
            'deadline' => now()->addDays(60)->format('Y-m-d'),
            'created_at' => now()->subDays(3),
        ]);

        // Complexity 10
        $challenges[] = Challenge::create([
            'company_id' => $companies[1]->id,
            'source_type' => 'company',
            'title' => 'Novel Catalyst Development for CO2 Conversion',
            'original_description' => 'Develop new heterogeneous catalyst for converting CO2 to methanol using green hydrogen.',
            'refined_brief' => 'Research and develop novel catalyst system for CO2 hydrogenation to methanol with >80% conversion and >95% selectivity.',
            'field' => 'Chemical Engineering',
            'status' => 'active',
            'challenge_type' => 'team_execution',
            'complexity_level' => 10,
            'score' => 10,
            'requires_nda' => true,
            'deadline' => now()->addDays(180)->format('Y-m-d'),
            'created_at' => now()->subDays(8),
        ]);

        $this->command->info('  ✓ Created ' . count($challenges) . ' challenges (all workflow states)');
        return $challenges;
    }

    private function createWorkstreamsAndTasks($challenges): void
    {
        // Completed Challenge Tasks
        $this->createCompletedChallengeTasks($challenges[0]);

        // Active Challenge Tasks
        $this->createActiveChatbotTasks($challenges[3]);

        // Chemical Process Tasks
        $this->createChemicalProcessTasks($challenges[2]);

        $this->command->info('  ✓ Created workstreams and tasks');
    }

    private function createCompletedChallengeTasks($challenge): void
    {
        $workstream = Workstream::create([
            'challenge_id' => $challenge->id,
            'title' => 'Process Analysis & Optimization',
            'description' => 'Analyze and optimize temperature control systems',
            'order' => 1,
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $workstream->id,
            'title' => 'Analyze current PID parameters',
            'description' => 'Document and analyze existing PID controller settings.',
            'estimated_hours' => 20,
            'required_skills' => ['Process Optimization', 'Chemical Engineering'],
            'required_experience_level' => 'Mid',
            'expected_output' => 'Analysis report with current performance metrics',
            'acceptance_criteria' => ['Complete parameter documentation', 'Performance baseline established'],
            'complexity_score' => 5,
            'status' => 'completed',
            'priority' => 'high',
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $workstream->id,
            'title' => 'Develop optimized control strategy',
            'description' => 'Design new PID parameters for improved control.',
            'estimated_hours' => 30,
            'required_skills' => ['Process Optimization', 'Control Systems'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Optimized PID parameters with simulation results',
            'acceptance_criteria' => ['15% energy reduction achieved', 'Temperature precision ±2°C'],
            'complexity_score' => 7,
            'status' => 'completed',
            'priority' => 'high',
        ]);
    }

    private function createActiveChatbotTasks($challenge): void
    {
        // Backend Workstream
        $ws1 = Workstream::create([
            'challenge_id' => $challenge->id,
            'title' => 'Backend Development',
            'description' => 'API and backend infrastructure',
            'order' => 1,
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $ws1->id,
            'title' => 'Design RESTful API for chatbot',
            'description' => 'Create RESTful API endpoints for chat, authentication, CRM integration.',
            'estimated_hours' => 40,
            'required_skills' => ['PHP', 'Laravel', 'API Design', 'MySQL'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Fully functional RESTful API with documentation',
            'acceptance_criteria' => ['All endpoints functional', 'Authentication working', 'CRM integration complete'],
            'complexity_score' => 6,
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $ws1->id,
            'title' => 'Implement NLP intent recognition',
            'description' => 'Build NLP engine for intent recognition and entity extraction.',
            'estimated_hours' => 35,
            'required_skills' => ['Python', 'Machine Learning', 'NLP', 'TensorFlow'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'NLP model with intent recognition and entity extraction',
            'acceptance_criteria' => ['Intent accuracy > 90%', 'Entity extraction working', 'Model deployed'],
            'complexity_score' => 7,
            'status' => 'assigned',
            'priority' => 'high',
        ]);

        // Frontend Workstream
        $ws2 = Workstream::create([
            'challenge_id' => $challenge->id,
            'title' => 'Frontend Development',
            'description' => 'Chat widget and admin dashboard',
            'order' => 2,
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $ws2->id,
            'title' => 'Build responsive chat widget',
            'description' => 'Create embeddable chat widget with typing indicators and file upload.',
            'estimated_hours' => 30,
            'required_skills' => ['JavaScript', 'React', 'CSS'],
            'required_experience_level' => 'Mid',
            'expected_output' => 'Responsive chat widget component',
            'acceptance_criteria' => ['Works on all browsers', 'Mobile responsive', 'File upload functional'],
            'complexity_score' => 5,
            'status' => 'completed',
            'priority' => 'high',
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $ws2->id,
            'title' => 'Admin analytics dashboard',
            'description' => 'Build admin dashboard for monitoring conversations.',
            'estimated_hours' => 25,
            'required_skills' => ['React', 'Data Visualization'],
            'required_experience_level' => 'Mid',
            'expected_output' => 'Admin dashboard with analytics',
            'acceptance_criteria' => ['Real-time updates', 'Analytics accurate'],
            'complexity_score' => 6,
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $ws2->id,
            'title' => 'Salesforce CRM integration',
            'description' => 'Integrate with Salesforce for customer data sync.',
            'estimated_hours' => 20,
            'required_skills' => ['API Integration', 'Salesforce', 'PHP'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Salesforce integration with data sync',
            'acceptance_criteria' => ['Data syncs correctly', 'Tickets created'],
            'complexity_score' => 7,
            'status' => 'pending',
            'priority' => 'medium',
        ]);
    }

    private function createChemicalProcessTasks($challenge): void
    {
        $ws1 = Workstream::create([
            'challenge_id' => $challenge->id,
            'title' => 'Process Analysis',
            'description' => 'Document and analyze current workflow',
            'order' => 1,
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $ws1->id,
            'title' => 'Map production workflow',
            'description' => 'Create detailed process flow diagrams.',
            'estimated_hours' => 15,
            'required_skills' => ['Process Optimization', 'Chemical Engineering'],
            'required_experience_level' => 'Mid',
            'expected_output' => 'Detailed process flow diagrams',
            'acceptance_criteria' => ['Complete workflow diagrams', 'Current state documented'],
            'complexity_score' => 6,
            'status' => 'assigned',
            'priority' => 'high',
        ]);

        Task::create([
            'challenge_id' => $challenge->id,
            'workstream_id' => $ws1->id,
            'title' => 'Identify bottlenecks',
            'description' => 'Analyze workflow to identify bottlenecks and waste points.',
            'estimated_hours' => 20,
            'required_skills' => ['Chemical Analysis', 'Process Optimization'],
            'required_experience_level' => 'Expert',
            'expected_output' => 'Bottleneck analysis report',
            'acceptance_criteria' => ['All bottlenecks identified', 'Root cause analysis complete'],
            'complexity_score' => 7,
            'status' => 'pending',
            'priority' => 'high',
        ]);
    }

    private function createTeams($challenges, $volunteers): array
    {
        $teams = [];

        // Team 1: AI Chatbot Team (Active)
        $team1 = Team::create([
            'challenge_id' => $challenges[3]->id,
            'name' => 'AI Chatbot Development Squad',
            'description' => 'Full-stack team building the customer support chatbot',
            'status' => 'active',
            'leader_id' => $volunteers[0]->id,
            'team_match_score' => 93.5,
            'estimated_total_hours' => 150,
            'formation_completed_at' => now()->subDays(15),
        ]);

        TeamMember::create([
            'team_id' => $team1->id,
            'volunteer_id' => $volunteers[0]->id,
            'role' => 'leader',
            'status' => 'accepted',
            'role_description' => 'Team Leader & Backend Developer',
            'assigned_skills' => ['PHP', 'Laravel', 'API Design'],
            'invited_at' => now()->subDays(18),
            'accepted_at' => now()->subDays(17),
        ]);

        TeamMember::create([
            'team_id' => $team1->id,
            'volunteer_id' => $volunteers[1]->id,
            'role' => 'specialist',
            'status' => 'accepted',
            'role_description' => 'ML Engineer - NLP implementation',
            'assigned_skills' => ['Python', 'Machine Learning', 'NLP'],
            'invited_at' => now()->subDays(18),
            'accepted_at' => now()->subDays(16),
        ]);

        TeamMember::create([
            'team_id' => $team1->id,
            'volunteer_id' => $volunteers[3]->id,
            'role' => 'specialist',
            'status' => 'invited', // Pending invitation
            'role_description' => 'UX Designer - Chat interface design',
            'assigned_skills' => ['Figma', 'User Research'],
            'invited_at' => now()->subDays(5),
        ]);

        TeamMember::create([
            'team_id' => $team1->id,
            'volunteer_id' => $volunteers[4]->id,
            'role' => 'specialist',
            'status' => 'accepted',
            'role_description' => 'Frontend Developer - Chat widget',
            'assigned_skills' => ['React', 'JavaScript', 'CSS'],
            'invited_at' => now()->subDays(14),
            'accepted_at' => now()->subDays(13),
        ]);

        $teams[] = $team1;

        // Team 2: Chemical Process Team (Forming)
        $team2 = Team::create([
            'challenge_id' => $challenges[2]->id,
            'name' => 'Process Optimization Team',
            'description' => 'Chemical engineers optimizing production',
            'status' => 'forming',
            'leader_id' => $volunteers[2]->id,
            'team_match_score' => 87.0,
            'estimated_total_hours' => 60,
        ]);

        TeamMember::create([
            'team_id' => $team2->id,
            'volunteer_id' => $volunteers[2]->id,
            'role' => 'leader',
            'status' => 'accepted',
            'role_description' => 'Team Leader - Process analysis lead',
            'assigned_skills' => ['Process Optimization', 'Chemical Analysis'],
            'invited_at' => now()->subDays(10),
            'accepted_at' => now()->subDays(9),
        ]);

        TeamMember::create([
            'team_id' => $team2->id,
            'volunteer_id' => $volunteers[6]->id,
            'role' => 'specialist',
            'status' => 'invited', // Pending
            'role_description' => 'Project Manager - Coordination',
            'assigned_skills' => ['Agile', 'Risk Management'],
            'invited_at' => now()->subDays(4),
        ]);

        $teams[] = $team2;

        // Team 3: Completed Challenge Team
        $team3 = Team::create([
            'challenge_id' => $challenges[0]->id,
            'name' => 'Temperature Control Team',
            'description' => 'Completed reactor optimization project',
            'status' => 'completed',
            'leader_id' => $volunteers[2]->id,
            'team_match_score' => 91.0,
            'estimated_total_hours' => 50,
            'formation_completed_at' => now()->subDays(80),
        ]);

        TeamMember::create([
            'team_id' => $team3->id,
            'volunteer_id' => $volunteers[2]->id,
            'role' => 'leader',
            'status' => 'accepted',
            'role_description' => 'Team Leader',
            'assigned_skills' => ['Process Optimization'],
            'invited_at' => now()->subDays(85),
            'accepted_at' => now()->subDays(84),
        ]);

        $teams[] = $team3;

        $this->command->info('  ✓ Created ' . count($teams) . ' teams');
        return $teams;
    }

    private function createTeamMessages($teams, $volunteers): void
    {
        $messageCount = 0;

        // Team 1 Chat (Active team with conversation)
        $team = $teams[0];

        $messages = [
            ['user_id' => $volunteers[0]->user_id, 'message' => 'Welcome everyone to the AI Chatbot project! Excited to work with this talented team.', 'created_at' => now()->subDays(15)],
            ['user_id' => $volunteers[1]->user_id, 'message' => 'Thanks Alex! I\'ve started reviewing the NLP requirements. The intent recognition looks challenging but doable.', 'created_at' => now()->subDays(15)->addHours(2)],
            ['user_id' => $volunteers[4]->user_id, 'message' => 'Hey team! I\'ve set up the React project structure for the chat widget. Should I share the repo link?', 'created_at' => now()->subDays(14)],
            ['user_id' => $volunteers[0]->user_id, 'message' => 'Yes please! Also, let\'s schedule a quick sync call for tomorrow to discuss the API endpoints.', 'created_at' => now()->subDays(14)->addHours(1)],
            ['user_id' => $volunteers[1]->user_id, 'message' => 'Works for me. I\'ll prepare a list of required API endpoints from the NLP side.', 'created_at' => now()->subDays(14)->addHours(3)],
            ['user_id' => $volunteers[4]->user_id, 'message' => 'Great! Here\'s the repo: github.com/team/chatbot-widget. I\'ve added basic component structure.', 'created_at' => now()->subDays(13)],
            ['user_id' => $volunteers[0]->user_id, 'message' => 'Perfect. I\'ve started on the Laravel backend. Authentication endpoints are ready for testing.', 'created_at' => now()->subDays(12)],
            ['user_id' => $volunteers[1]->user_id, 'message' => 'Quick update: Initial intent classifier is showing 85% accuracy. Need more training data to hit 90%.', 'created_at' => now()->subDays(10)],
            ['user_id' => $volunteers[0]->user_id, 'message' => 'That\'s good progress Sarah! I can help generate synthetic training data from common support tickets.', 'created_at' => now()->subDays(10)->addHours(2)],
            ['user_id' => $volunteers[4]->user_id, 'message' => 'Chat widget v1 is ready for review! Added typing indicators and file upload. Demo: chatbot-demo.vercel.app', 'created_at' => now()->subDays(5)],
            ['user_id' => $volunteers[0]->user_id, 'message' => 'Awesome work James! Just tested it - looks great. Minor feedback: can we add a loading skeleton?', 'created_at' => now()->subDays(5)->addHours(1)],
            ['user_id' => $volunteers[4]->user_id, 'message' => 'Good call, adding that now. Should be deployed in an hour.', 'created_at' => now()->subDays(5)->addHours(2)],
            ['user_id' => $volunteers[1]->user_id, 'message' => 'Update: Intent accuracy is now at 92%! The synthetic data really helped. Ready for integration.', 'created_at' => now()->subDays(3)],
            ['user_id' => $volunteers[0]->user_id, 'message' => 'Excellent! Let\'s aim to have the MVP integrated by end of this week. Great teamwork everyone!', 'created_at' => now()->subDays(2)],
        ];

        foreach ($messages as $msg) {
            TeamMessage::create([
                'team_id' => $team->id,
                'user_id' => $msg['user_id'],
                'message' => $msg['message'],
                'is_read' => true,
                'created_at' => $msg['created_at'],
                'updated_at' => $msg['created_at'],
            ]);
            $messageCount++;
        }

        // Team 2 Chat (Forming team)
        $team2 = $teams[1];
        $team2Messages = [
            ['user_id' => $volunteers[2]->user_id, 'message' => 'Hi team! Ready to start the process optimization project. Looking forward to collaborating.', 'created_at' => now()->subDays(9)],
            ['user_id' => $volunteers[2]->user_id, 'message' => 'I\'ve started mapping the current workflow. Will share initial diagrams soon.', 'created_at' => now()->subDays(7)],
        ];

        foreach ($team2Messages as $msg) {
            TeamMessage::create([
                'team_id' => $team2->id,
                'user_id' => $msg['user_id'],
                'message' => $msg['message'],
                'is_read' => false,
                'created_at' => $msg['created_at'],
                'updated_at' => $msg['created_at'],
            ]);
            $messageCount++;
        }

        $this->command->info('  ✓ Created ' . $messageCount . ' team chat messages');
    }

    private function createTaskAssignments($challenges, $volunteers): void
    {
        $tasks = Task::whereIn('challenge_id', collect($challenges)->pluck('id'))->get();
        $assignmentCount = 0;

        // Assignment 1: IN PROGRESS
        $task = $tasks->where('title', 'Design RESTful API for chatbot')->first();
        if ($task) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'volunteer_id' => $volunteers[0]->id,
                'invitation_status' => 'in_progress',
                'ai_match_score' => 96,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Excellent match - 10 years PHP/Laravel experience',
                    'strengths' => ['Expert PHP', 'Expert Laravel', 'Expert API Design'],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(15),
                'responded_at' => now()->subDays(14),
                'started_at' => now()->subDays(14),
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(15),
            ]);
            $assignmentCount++;
        }

        // Assignment 2: ACCEPTED
        $task = $tasks->where('title', 'Implement NLP intent recognition')->first();
        if ($task) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'volunteer_id' => $volunteers[1]->id,
                'invitation_status' => 'accepted',
                'ai_match_score' => 98,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Perfect match for ML/NLP requirements',
                    'strengths' => ['Expert ML', 'Expert NLP', 'Expert TensorFlow'],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(12),
                'responded_at' => now()->subDays(11),
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(12),
            ]);
            $assignmentCount++;
        }

        // Assignment 3: SUBMITTED (Work completed)
        $task = $tasks->where('title', 'Build responsive chat widget')->first();
        if ($task) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'volunteer_id' => $volunteers[4]->id,
                'invitation_status' => 'submitted',
                'ai_match_score' => 92,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Strong React and frontend skills',
                    'strengths' => ['Expert React', 'Expert JavaScript'],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(20),
                'responded_at' => now()->subDays(19),
                'started_at' => now()->subDays(19),
                'completed_at' => now()->subDays(3),
                'actual_hours' => 28,
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(20),
            ]);
            $assignmentCount++;
        }

        // Assignment 4: INVITED (Pending response)
        $task = $tasks->where('title', 'Map production workflow')->first();
        if ($task) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'volunteer_id' => $volunteers[2]->id,
                'invitation_status' => 'invited',
                'ai_match_score' => 89,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Good fit for chemical process analysis',
                    'strengths' => ['Process Optimization', 'Chemical Engineering'],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(5),
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(6),
            ]);
            $assignmentCount++;
        }

        // Assignment 5: DECLINED
        $task = $tasks->where('title', 'Salesforce CRM integration')->first();
        if ($task) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'volunteer_id' => $volunteers[5]->id,
                'invitation_status' => 'declined',
                'ai_match_score' => 42,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Limited technical integration experience',
                    'strengths' => ['Marketing background'],
                    'gaps' => ['No Salesforce dev experience'],
                    'rejection_reason' => 'Task requires technical skills outside my expertise'
                ]),
                'invited_at' => now()->subDays(8),
                'responded_at' => now()->subDays(7),
            ]);
            $assignmentCount++;
        }

        // Assignment 6: COMPLETED (For completed challenge)
        $completedTask = $tasks->where('title', 'Analyze current PID parameters')->first();
        if ($completedTask) {
            TaskAssignment::create([
                'task_id' => $completedTask->id,
                'volunteer_id' => $volunteers[2]->id,
                'invitation_status' => 'completed',
                'ai_match_score' => 88,
                'ai_match_reasoning' => json_encode([
                    'reasoning' => 'Strong chemical engineering background',
                    'strengths' => ['Process Optimization', 'Chemical Analysis'],
                    'gaps' => []
                ]),
                'invited_at' => now()->subDays(85),
                'responded_at' => now()->subDays(84),
                'started_at' => now()->subDays(84),
                'completed_at' => now()->subDays(30),
                'actual_hours' => 18,
                'nda_signed' => true,
                'nda_signed_at' => now()->subDays(85),
            ]);
            $assignmentCount++;
        }

        $this->command->info('  ✓ Created ' . $assignmentCount . ' task assignments (all states)');
    }

    private function createWorkSubmissions($volunteers): void
    {
        $submittedAssignment = TaskAssignment::where('invitation_status', 'submitted')->first();
        $completedAssignment = TaskAssignment::where('invitation_status', 'completed')->first();

        $submissionCount = 0;

        // Submission 1: APPROVED
        if ($submittedAssignment) {
            WorkSubmission::create([
                'task_assignment_id' => $submittedAssignment->id,
                'task_id' => $submittedAssignment->task_id,
                'volunteer_id' => $submittedAssignment->volunteer_id,
                'description' => "# Chat Widget Implementation - Complete\n\n## Summary\nBuilt a production-ready, responsive chat widget with React.\n\n## Features\n- Real-time messaging with WebSocket\n- Typing indicators\n- File upload (images up to 10MB)\n- Mobile responsive\n- Dark/Light theme\n\n## Testing\n- Chrome, Firefox, Safari, Edge\n- Mobile Safari, Chrome Mobile\n\n## Demo\nhttps://chat-widget-demo.vercel.app",
                'deliverable_url' => 'https://github.com/jameslee/chat-widget-demo',
                'attachments' => json_encode(['demo_url' => 'https://chat-widget-demo.vercel.app']),
                'hours_worked' => 28,
                'status' => 'approved',
                'ai_analysis_status' => 'completed',
                'ai_quality_score' => 93,
                'ai_feedback' => json_encode([
                    'feedback' => 'Outstanding implementation with exceptional attention to detail.',
                    'strengths' => ['Complete feature implementation', 'Excellent code quality', 'Thorough testing'],
                    'areas_for_improvement' => ['Consider adding WebSocket reconnection logic']
                ]),
                'submitted_at' => now()->subDays(3),
                'reviewed_at' => now()->subDays(2),
            ]);
            $submissionCount++;
        }

        // Submission 2: PENDING REVIEW
        if ($completedAssignment) {
            WorkSubmission::create([
                'task_assignment_id' => $completedAssignment->id,
                'task_id' => $completedAssignment->task_id,
                'volunteer_id' => $completedAssignment->volunteer_id,
                'description' => "# PID Parameter Analysis Report\n\n## Executive Summary\nCompleted analysis of current reactor temperature control system.\n\n## Findings\n- Current P value: 2.5 (optimal: 3.2)\n- Current I value: 0.8 (optimal: 0.6)\n- Current D value: 0.3 (optimal: 0.4)\n\n## Recommendations\nParameter adjustments can achieve 18% energy reduction.",
                'deliverable_url' => 'https://drive.google.com/analysis-report',
                'attachments' => json_encode(['report_pdf' => 'pid_analysis_report.pdf']),
                'hours_worked' => 18,
                'status' => 'submitted',
                'ai_analysis_status' => 'pending',
                'ai_quality_score' => null,
                'ai_feedback' => null,
                'submitted_at' => now()->subDays(1),
                'reviewed_at' => null,
            ]);
            $submissionCount++;
        }

        // Submission 3: REVISION REQUESTED
        $inProgressAssignment = TaskAssignment::where('invitation_status', 'in_progress')->first();
        if ($inProgressAssignment) {
            WorkSubmission::create([
                'task_assignment_id' => $inProgressAssignment->id,
                'task_id' => $inProgressAssignment->task_id,
                'volunteer_id' => $inProgressAssignment->volunteer_id,
                'description' => "# API Draft v1\n\nInitial API endpoint documentation.\n\n## Endpoints\n- POST /api/chat\n- GET /api/history\n- POST /api/auth/login",
                'deliverable_url' => 'https://github.com/alexj/chatbot-api/tree/v1',
                'attachments' => json_encode([]),
                'hours_worked' => 15,
                'status' => 'revision_requested',
                'ai_analysis_status' => 'completed',
                'ai_quality_score' => 65,
                'ai_feedback' => json_encode([
                    'feedback' => 'Good start but missing key endpoints and error handling documentation.',
                    'strengths' => ['Clean endpoint structure', 'RESTful design'],
                    'areas_for_improvement' => ['Add CRM integration endpoints', 'Include error response formats', 'Add rate limiting documentation']
                ]),
                'submitted_at' => now()->subDays(5),
                'reviewed_at' => now()->subDays(4),
            ]);
            $submissionCount++;
        }

        $this->command->info('  ✓ Created ' . $submissionCount . ' work submissions (various states)');
    }

    private function createReviews($companies): void
    {
        $approvedSubmission = WorkSubmission::where('status', 'approved')->first();
        $reviewCount = 0;

        if ($approvedSubmission) {
            // Company review
            Review::create([
                'work_submission_id' => $approvedSubmission->id,
                'reviewer_id' => $companies[0]->user_id,
                'rating' => 5,
                'quality_score' => 95,
                'timeliness_score' => 90,
                'communication_score' => 92,
                'feedback' => 'Exceptional work! James delivered a polished, production-ready chat widget that exceeded our expectations. Great communication throughout the project.',
                'decision' => 'approved',
                'revision_notes' => null,
            ]);
            $reviewCount++;
        }

        $revisionSubmission = WorkSubmission::where('status', 'revision_requested')->first();
        if ($revisionSubmission) {
            Review::create([
                'work_submission_id' => $revisionSubmission->id,
                'reviewer_id' => $companies[0]->user_id,
                'rating' => 3,
                'quality_score' => 60,
                'timeliness_score' => 85,
                'communication_score' => 80,
                'feedback' => 'Good foundation but needs more complete documentation and additional endpoints.',
                'decision' => 'revision_requested',
                'revision_notes' => 'Please add: 1) CRM integration endpoints 2) Error handling docs 3) Rate limiting specs',
            ]);
            $reviewCount++;
        }

        $this->command->info('  ✓ Created ' . $reviewCount . ' reviews');
    }

    private function createCertificates($volunteers, $challenges, $companies): void
    {
        $certCount = 0;

        // Completion Certificate for completed challenge
        Certificate::create([
            'user_id' => $volunteers[2]->user_id,
            'challenge_id' => $challenges[0]->id,
            'company_id' => $companies[1]->user_id,
            'certificate_type' => 'completion',
            'role' => 'Team Leader',
            'contribution_summary' => 'Led the temperature control optimization project, achieving 18% energy reduction.',
            'contribution_types' => ['Process Analysis', 'Team Leadership', 'Technical Implementation'],
            'total_hours' => 45,
            'time_breakdown' => ['Analysis' => 18, 'Implementation' => 20, 'Documentation' => 7],
            'company_confirmed' => true,
            'confirmed_at' => now()->subDays(5),
            'issued_at' => now()->subDays(5),
            'is_revoked' => false,
        ]);
        $certCount++;

        // Participation Certificate
        Certificate::create([
            'user_id' => $volunteers[4]->user_id,
            'challenge_id' => $challenges[3]->id,
            'company_id' => $companies[0]->user_id,
            'certificate_type' => 'participation',
            'role' => 'Frontend Developer',
            'contribution_summary' => 'Developed the responsive chat widget for the AI chatbot project.',
            'contribution_types' => ['Frontend Development', 'UI Implementation'],
            'total_hours' => 28,
            'time_breakdown' => ['Development' => 22, 'Testing' => 6],
            'company_confirmed' => true,
            'confirmed_at' => now()->subDays(1),
            'issued_at' => now()->subDays(1),
            'is_revoked' => false,
        ]);
        $certCount++;

        // Pending Confirmation Certificate
        Certificate::create([
            'user_id' => $volunteers[1]->user_id,
            'challenge_id' => $challenges[3]->id,
            'company_id' => $companies[0]->user_id,
            'certificate_type' => 'participation',
            'role' => 'ML Engineer',
            'contribution_summary' => 'Implemented NLP intent recognition achieving 92% accuracy.',
            'contribution_types' => ['Machine Learning', 'NLP Development'],
            'total_hours' => 35,
            'time_breakdown' => ['Development' => 25, 'Training' => 10],
            'company_confirmed' => false, // Pending
            'confirmed_at' => null,
            'issued_at' => null,
            'is_revoked' => false,
        ]);
        $certCount++;

        $this->command->info('  ✓ Created ' . $certCount . ' certificates');
    }

    private function createCommunityDiscussions($challenges, $volunteers): void
    {
        $ideaCount = 0;
        $commentCount = 0;

        // Find community challenges
        $chemEngChallenge = collect($challenges)->where('challenge_type', 'community_discussion')->where('field', 'Chemical Engineering')->first();
        $softwareChallenge = collect($challenges)->where('challenge_type', 'community_discussion')->where('field', 'Software Engineering')->first();
        $dataChallenge = collect($challenges)->where('challenge_type', 'community_discussion')->where('field', 'Data Science')->first();
        $uxChallenge = collect($challenges)->where('challenge_type', 'community_discussion')->where('field', 'UX Design')->first();
        $marketingChallenge = collect($challenges)->where('challenge_type', 'community_discussion')->where('field', 'Marketing')->first();

        // Chemical Engineering Ideas
        if ($chemEngChallenge) {
            Idea::create([
                'challenge_id' => $chemEngChallenge->id,
                'volunteer_id' => $volunteers[2]->id,
                'content' => "## Duplex Stainless Steel with Protective Coating\n\nRecommend Duplex 2205 stainless steel with PTFE fluoropolymer coating.\n\n**Cost-Benefit:**\n- 40% higher initial cost\n- 15+ year lifespan vs 3-4 years\n- ROI in 18 months",
                'ai_quality_score' => 8.5,
                'ai_feedback' => 'High-quality contribution with specific recommendations.',
            ]);
            $ideaCount++;

            Idea::create([
                'challenge_id' => $chemEngChallenge->id,
                'volunteer_id' => $volunteers[0]->id,
                'content' => "## Cathodic Protection System\n\nConsider impressed current cathodic protection (ICCP) to extend 316SS life.",
                'ai_quality_score' => 5.5,
                'ai_feedback' => 'Good suggestion but lacks implementation details.',
            ]);
            $ideaCount++;

            // Comments on the challenge
            $comment1 = ChallengeComment::create([
                'challenge_id' => $chemEngChallenge->id,
                'user_id' => $volunteers[6]->user_id,
                'content' => 'Great discussion! Has anyone considered titanium alloys for extreme conditions?',
                'ai_score' => 7,
                'ai_score_status' => 'completed',
                'ai_analysis' => 'Relevant question that adds to the discussion.',
                'ai_scored_at' => now()->subDays(1),
            ]);
            $commentCount++;

            ChallengeComment::create([
                'challenge_id' => $chemEngChallenge->id,
                'user_id' => $volunteers[2]->user_id,
                'content' => 'Titanium is excellent but cost-prohibitive for most applications. Duplex SS offers best value.',
                'ai_score' => 8,
                'ai_score_status' => 'completed',
                'ai_analysis' => 'Expert response with practical considerations.',
                'ai_scored_at' => now()->subHours(12),
            ]);
            $commentCount++;
        }

        // Software Engineering Ideas
        if ($softwareChallenge) {
            Idea::create([
                'challenge_id' => $softwareChallenge->id,
                'volunteer_id' => $volunteers[0]->id,
                'content' => "## Token Bucket with Redis\n\n**Implementation:**\n1. Use Redis INCR with TTL\n2. Per-endpoint limits\n3. Sliding window for bursts\n4. X-RateLimit headers\n\nHappy to share Laravel middleware code.",
                'ai_quality_score' => 8.7,
                'ai_feedback' => 'Excellent practical advice with implementation details.',
            ]);
            $ideaCount++;
        }

        // Data Science Ideas
        if ($dataChallenge) {
            Idea::create([
                'challenge_id' => $dataChallenge->id,
                'volunteer_id' => $volunteers[1]->id,
                'content' => "## Healthcare Feature Engineering\n\n**Temporal Features:**\n- Rolling 7/30-day stats\n- Time since abnormal reading\n- Trend indicators\n\n**Missing Data:**\n- MICE imputation\n- Missingness as information\n\n**Privacy:**\n- Differential privacy\n- Federated learning",
                'ai_quality_score' => 9.0,
                'ai_feedback' => 'Comprehensive coverage with healthcare-specific context.',
            ]);
            $ideaCount++;
        }

        // UX Ideas
        if ($uxChallenge) {
            Idea::create([
                'challenge_id' => $uxChallenge->id,
                'volunteer_id' => $volunteers[3]->id,
                'content' => "## Mobile Checkout Optimization\n\n**Quick Wins:**\n1. Guest checkout default\n2. Single-page accordion\n3. Google Places auto-fill\n\n**A/B Test Ideas:**\n- Single vs multi-page\n- Express checkout placement",
                'ai_quality_score' => 8.5,
                'ai_feedback' => 'Practical UX recommendations with testing ideas.',
            ]);
            $ideaCount++;
        }

        // Marketing Ideas
        if ($marketingChallenge) {
            Idea::create([
                'challenge_id' => $marketingChallenge->id,
                'volunteer_id' => $volunteers[5]->id,
                'content' => "## B2B SaaS Social Strategy\n\n**LinkedIn (60% effort):**\n- Thought leadership 1-2x/week\n- Employee advocacy\n\n**Content Calendar:**\n- Mon: Industry insights\n- Wed: Product tips\n- Fri: Customer spotlight",
                'ai_quality_score' => 7.8,
                'ai_feedback' => 'Good practical advice with platform-specific tactics.',
            ]);
            $ideaCount++;
        }

        $this->command->info('  ✓ Created ' . $ideaCount . ' ideas and ' . $commentCount . ' comments');
    }

    private function createVotes($volunteers): void
    {
        $voteCount = 0;

        // Idea Votes (uses 'up' and 'down')
        $ideas = Idea::all();
        foreach ($ideas as $index => $idea) {
            // Add upvotes from different volunteers
            foreach ([0, 1, 3, 6] as $i) {
                if (isset($volunteers[$i]) && $volunteers[$i]->id !== $idea->volunteer_id) {
                    IdeaVote::create([
                        'idea_id' => $idea->id,
                        'volunteer_id' => $volunteers[$i]->id,
                        'vote_type' => ($index % 3 === 0 && $i === 6) ? 'down' : 'up',
                    ]);
                    $voteCount++;
                }
            }
        }

        // Comment Votes
        $comments = ChallengeComment::all();
        foreach ($comments as $comment) {
            foreach ([0, 1, 2] as $i) {
                if (isset($volunteers[$i]) && $volunteers[$i]->user_id !== $comment->user_id) {
                    CommentVote::create([
                        'comment_id' => $comment->id,
                        'user_id' => $volunteers[$i]->user_id,
                        'vote_type' => 'upvote',
                    ]);
                    $voteCount++;
                }
            }
        }

        $this->command->info('  ✓ Created ' . $voteCount . ' votes');
    }

    private function createNdaSignings($volunteers, $challenges): void
    {
        $signingCount = 0;

        // Sign NDAs for challenges that require it
        $ndaChallenges = collect($challenges)->where('requires_nda', true);

        foreach ($volunteers as $index => $volunteer) {
            if (!$volunteer->general_nda_signed) continue;

            foreach ($ndaChallenges->take(3) as $challenge) {
                if ($index < 5) {
                    $signedAt = now()->subDays(20 - $index);
                    ChallengeNdaSigning::create([
                        'user_id' => $volunteer->user_id,
                        'challenge_id' => $challenge->id,
                        'nda_agreement_id' => 1,
                        'signer_name' => $volunteer->user->name,
                        'signer_email' => $volunteer->user->email,
                        'ip_address' => '192.168.1.' . ($index + 100),
                        'signature_hash' => hash('sha256', $volunteer->user_id . $challenge->id . $signedAt),
                        'is_valid' => true,
                        'signed_at' => $signedAt,
                    ]);
                    $signingCount++;
                }
            }
        }

        $this->command->info('  ✓ Created ' . $signingCount . ' challenge NDA signings');
    }

    private function createNotifications($volunteers, $companies, $challenges): void
    {
        $notifCount = 0;

        // Volunteer Notifications
        foreach ($volunteers as $index => $volunteer) {
            if ($index >= 8) continue;

            // Task invitation notification
            Notification::create([
                'user_id' => $volunteer->user_id,
                'type' => 'task_invitation',
                'title' => 'New Task Invitation',
                'message' => 'You have been invited to work on "API Development" for the AI Chatbot project.',
                'action_url' => '/volunteer/tasks',
                'is_read' => $index < 3,
                'read_at' => $index < 3 ? now()->subDays(2) : null,
            ]);
            $notifCount++;

            // Team invitation notification
            if ($index < 5) {
                Notification::create([
                    'user_id' => $volunteer->user_id,
                    'type' => 'team_invitation',
                    'title' => 'Team Invitation',
                    'message' => 'You have been invited to join "AI Chatbot Development Squad".',
                    'action_url' => '/volunteer/teams',
                    'is_read' => $index < 2,
                    'read_at' => $index < 2 ? now()->subDays(1) : null,
                ]);
                $notifCount++;
            }

            // Submission reviewed notification
            if ($index === 4) {
                Notification::create([
                    'user_id' => $volunteer->user_id,
                    'type' => 'submission_approved',
                    'title' => 'Submission Approved!',
                    'message' => 'Your work submission for "Chat Widget" has been approved. Great job!',
                    'action_url' => '/volunteer/submissions',
                    'is_read' => false,
                    'read_at' => null,
                ]);
                $notifCount++;
            }

            // New challenge matching notification
            Notification::create([
                'user_id' => $volunteer->user_id,
                'type' => 'challenge_match',
                'title' => 'New Challenge Match',
                'message' => 'A new challenge matching your skills is available: "API Rate Limiting".',
                'action_url' => '/challenges',
                'is_read' => false,
                'read_at' => null,
            ]);
            $notifCount++;
        }

        // Company Notifications
        foreach ($companies as $index => $company) {
            if ($index >= 4) continue;

            // Challenge status notification
            Notification::create([
                'user_id' => $company->user_id,
                'type' => 'challenge_update',
                'title' => 'Challenge Status Update',
                'message' => 'Your challenge "Distillation Column Efficiency" has a new team formed.',
                'action_url' => '/company/challenges',
                'is_read' => $index === 0,
                'read_at' => $index === 0 ? now()->subHours(5) : null,
            ]);
            $notifCount++;

            // Submission received notification
            if ($index < 2) {
                Notification::create([
                    'user_id' => $company->user_id,
                    'type' => 'submission_received',
                    'title' => 'New Work Submission',
                    'message' => 'A volunteer has submitted work for review on your challenge.',
                    'action_url' => '/company/submissions',
                    'is_read' => false,
                    'read_at' => null,
                ]);
                $notifCount++;
            }
        }

        $this->command->info('  ✓ Created ' . $notifCount . ' notifications');
    }

    private function createReputationHistory($volunteers): void
    {
        $historyCount = 0;

        foreach ($volunteers as $index => $volunteer) {
            if ($index >= 5) continue;

            // Initial reputation
            ReputationHistory::create([
                'volunteer_id' => $volunteer->id,
                'change_amount' => 50,
                'new_total' => 50,
                'reason' => 'Account created - initial reputation',
                'related_type' => 'App\Models\User',
                'related_id' => $volunteer->user_id,
                'created_at' => now()->subDays(60),
            ]);
            $historyCount++;

            // Profile completion bonus
            ReputationHistory::create([
                'volunteer_id' => $volunteer->id,
                'change_amount' => 10,
                'new_total' => 60,
                'reason' => 'Profile completion bonus',
                'related_type' => 'App\Models\Volunteer',
                'related_id' => $volunteer->id,
                'created_at' => now()->subDays(55),
            ]);
            $historyCount++;

            // Task completion bonuses
            if ($index < 3) {
                ReputationHistory::create([
                    'volunteer_id' => $volunteer->id,
                    'change_amount' => 15,
                    'new_total' => 75,
                    'reason' => 'Task completed successfully',
                    'related_type' => 'App\Models\TaskAssignment',
                    'related_id' => 1,
                    'created_at' => now()->subDays(20),
                ]);
                $historyCount++;

                ReputationHistory::create([
                    'volunteer_id' => $volunteer->id,
                    'change_amount' => 10,
                    'new_total' => 85,
                    'reason' => 'Positive review received',
                    'related_type' => 'App\Models\Review',
                    'related_id' => 1,
                    'created_at' => now()->subDays(15),
                ]);
                $historyCount++;
            }

            // Community contribution
            if ($index < 2) {
                ReputationHistory::create([
                    'volunteer_id' => $volunteer->id,
                    'change_amount' => 5,
                    'new_total' => 90,
                    'reason' => 'High-quality idea contribution',
                    'related_type' => 'App\Models\Idea',
                    'related_id' => 1,
                    'created_at' => now()->subDays(5),
                ]);
                $historyCount++;
            }
        }

        $this->command->info('  ✓ Created ' . $historyCount . ' reputation history records');
    }

    private function createBugReports($volunteers, $companies): void
    {
        $bugCount = 0;

        // Bug from volunteer - Critical (blocked user)
        BugReport::create([
            'user_id' => $volunteers[0]->user_id,
            'issue_type' => 'bug',
            'description' => "The team chat widget doesn't load properly on Safari 17. Steps to reproduce:\n1. Open team chat page\n2. Wait for widget to load\n3. Widget shows blank white screen\n\nExpected: Chat messages should display\nActual: Blank screen with loading spinner stuck",
            'current_page' => '/volunteer/teams/1/chat',
            'user_agent' => 'Safari 17.2 on macOS Sonoma',
            'blocked_user' => true,
            'status' => 'new',
        ]);
        $bugCount++;

        // Bug from company - Something didn't work
        BugReport::create([
            'user_id' => $companies[0]->user_id,
            'issue_type' => 'something_didnt_work',
            'description' => "When clicking download button on work submissions, nothing happens. The file should download but browser shows no response.",
            'current_page' => '/company/submissions/1',
            'user_agent' => 'Chrome 120 on Windows 11',
            'blocked_user' => false,
            'status' => 'reviewing',
        ]);
        $bugCount++;

        // UI/UX issue
        BugReport::create([
            'user_id' => $volunteers[3]->user_id,
            'issue_type' => 'ui_ux_issue',
            'description' => "The submit button is too small on mobile devices, hard to tap accurately.",
            'current_page' => '/volunteer/tasks/submit',
            'user_agent' => 'Chrome Mobile 120 on Android',
            'blocked_user' => false,
            'status' => 'resolved',
        ]);
        $bugCount++;

        // Confusing flow
        BugReport::create([
            'user_id' => $volunteers[1]->user_id,
            'issue_type' => 'confusing_flow',
            'description' => "After submitting work, I wasn't sure if it was submitted successfully. The confirmation message disappeared too quickly.",
            'current_page' => '/volunteer/submissions/create',
            'user_agent' => 'Firefox 121 on Ubuntu',
            'blocked_user' => false,
            'status' => 'new',
        ]);
        $bugCount++;

        $this->command->info('  ✓ Created ' . $bugCount . ' bug reports');
    }

    private function printLoginCredentials(): void
    {
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║                    📝 LOGIN CREDENTIALS                      ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ 🔑 ADMIN:                                                    ║');
        $this->command->info('║    admin@mindova.com / admin123                              ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ 🏢 COMPANIES:                                                ║');
        $this->command->info('║    tech@company.com / password123 (TechCorp)                 ║');
        $this->command->info('║    manufacturing@company.com / password123 (Manufacturing)   ║');
        $this->command->info('║    health@company.com / password123 (HealthTech)             ║');
        $this->command->info('║    shop@company.com / password123 (ShopFlow)                 ║');
        $this->command->info('║    newstartup@company.com / password123 (Unverified Email)   ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ 👥 VOLUNTEERS:                                               ║');
        $this->command->info('║    alex@volunteer.com / password123 (Expert, High Rep)       ║');
        $this->command->info('║    sarah@volunteer.com / password123 (Expert ML, Top Rep)    ║');
        $this->command->info('║    michael@volunteer.com / password123 (Chemical Eng)        ║');
        $this->command->info('║    emma@volunteer.com / password123 (UX Designer)            ║');
        $this->command->info('║    james@volunteer.com / password123 (Frontend Dev)          ║');
        $this->command->info('║    sophia@volunteer.com / password123 (Marketing)            ║');
        $this->command->info('║    david@volunteer.com / password123 (PM, Top Rep)           ║');
        $this->command->info('║    olivia@volunteer.com / password123 (Backend Dev)          ║');
        $this->command->info('║    ryan@volunteer.com / password123 (No NDA Signed)          ║');
        $this->command->info('║    lisa@volunteer.com / password123 (AI Analysis Pending)    ║');
        $this->command->info('║    mark@volunteer.com / password123 (Unverified Email)       ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
    }

    private function printTestScenarios(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════════════════════════╗');
        $this->command->info('║                  🧪 TEST SCENARIOS AVAILABLE                 ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ CHALLENGE STATES:                                            ║');
        $this->command->info('║   ✓ Submitted (awaiting AI analysis)                         ║');
        $this->command->info('║   ✓ Analyzing (AI processing)                                ║');
        $this->command->info('║   ✓ Active (teams working)                                   ║');
        $this->command->info('║   ✓ Completed (with certificates)                            ║');
        $this->command->info('║   ✓ Rejected (policy violation)                              ║');
        $this->command->info('║   ✓ Community Discussion (multiple fields)                   ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ TASK ASSIGNMENT STATES:                                      ║');
        $this->command->info('║   ✓ Invited (pending response)                               ║');
        $this->command->info('║   ✓ Accepted (ready to start)                                ║');
        $this->command->info('║   ✓ In Progress (work ongoing)                               ║');
        $this->command->info('║   ✓ Submitted (awaiting review)                              ║');
        $this->command->info('║   ✓ Completed (reviewed & approved)                          ║');
        $this->command->info('║   ✓ Declined (volunteer rejected)                            ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ TEAM STATES:                                                 ║');
        $this->command->info('║   ✓ Forming (invitations pending)                            ║');
        $this->command->info('║   ✓ Active (team working)                                    ║');
        $this->command->info('║   ✓ Completed (project finished)                             ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ SUBMISSION STATES:                                           ║');
        $this->command->info('║   ✓ Pending (awaiting review)                                ║');
        $this->command->info('║   ✓ Approved (accepted)                                      ║');
        $this->command->info('║   ✓ Revision Requested (needs changes)                       ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ USER STATES:                                                 ║');
        $this->command->info('║   ✓ Verified email                                           ║');
        $this->command->info('║   ✓ Unverified email                                         ║');
        $this->command->info('║   ✓ NDA signed                                               ║');
        $this->command->info('║   ✓ NDA not signed                                           ║');
        $this->command->info('║   ✓ AI analysis completed                                    ║');
        $this->command->info('║   ✓ AI analysis pending                                      ║');
        $this->command->info('╠══════════════════════════════════════════════════════════════╣');
        $this->command->info('║ OTHER FEATURES:                                              ║');
        $this->command->info('║   ✓ Team chat messages                                       ║');
        $this->command->info('║   ✓ Community ideas with voting                              ║');
        $this->command->info('║   ✓ Comments with voting                                     ║');
        $this->command->info('║   ✓ Reviews & ratings                                        ║');
        $this->command->info('║   ✓ Certificates (completion & participation)                ║');
        $this->command->info('║   ✓ Notifications (various types)                            ║');
        $this->command->info('║   ✓ Reputation history                                       ║');
        $this->command->info('║   ✓ Bug reports                                              ║');
        $this->command->info('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->info('🚀 Ready for comprehensive manual testing!');
        $this->command->info('');
    }
}
