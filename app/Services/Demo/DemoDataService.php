<?php

namespace App\Services\Demo;

use App\Models\Certificate;
use App\Models\Challenge;
use App\Models\Company;
use App\Models\ExpertChallengeAssignment;
use App\Models\Idea;
use App\Models\Notification;
use App\Models\ReputationHistory;
use App\Models\SuccessStory;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerSkill;
use App\Models\WorkSubmission;
use App\Models\Workstream;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DemoDataService
{
    private array $professionals  = [];
    private array $companies      = [];

    // =========================================================================
    // PUBLIC API
    // =========================================================================

    public function seed(): array
    {
        $summary = [];

        DB::transaction(function () use (&$summary) {
            $this->createProfessionals();
            $this->createCompanies();
            $summary = $this->createChallenges();
        });

        return $summary;
    }

    public function clear(): int
    {
        $demoUserIds = User::where('is_demo', true)->pluck('id');
        $count       = $demoUserIds->count();

        if ($demoUserIds->isEmpty()) {
            return 0;
        }

        $demoVolIds       = Volunteer::whereIn('user_id', $demoUserIds)->pluck('id');
        $demoChallengeIds = Challenge::where('is_demo', true)->pluck('id');
        $demoTaskIds      = Task::whereIn('challenge_id', $demoChallengeIds)->pluck('id');
        $assignmentIds    = TaskAssignment::whereIn('task_id', $demoTaskIds)->pluck('id');

        DB::transaction(function () use ($demoUserIds, $demoVolIds, $demoChallengeIds, $demoTaskIds, $assignmentIds) {
            SuccessStory::whereIn('challenge_id', $demoChallengeIds)->delete();
            Certificate::where(function ($q) use ($demoUserIds, $demoChallengeIds) {
                $q->whereIn('user_id', $demoUserIds)->orWhereIn('challenge_id', $demoChallengeIds);
            })->delete();
            ReputationHistory::whereIn('volunteer_id', $demoVolIds)->delete();
            WorkSubmission::whereIn('task_assignment_id', $assignmentIds)->delete();
            TaskAssignment::whereIn('id', $assignmentIds)->delete();
            Task::whereIn('id', $demoTaskIds)->delete();
            Workstream::whereIn('challenge_id', $demoChallengeIds)->delete();
            Idea::whereIn('challenge_id', $demoChallengeIds)->delete();
            ExpertChallengeAssignment::whereIn('challenge_id', $demoChallengeIds)->delete();
            Notification::whereIn('user_id', $demoUserIds)->delete();
            VolunteerSkill::whereIn('volunteer_id', $demoVolIds)->delete();
            Challenge::where('is_demo', true)->delete();
            Volunteer::whereIn('user_id', $demoUserIds)->delete();
            Company::whereIn('user_id', $demoUserIds)->delete();
            User::where('is_demo', true)->delete();
        });

        return $count;
    }

    // =========================================================================
    // DEMO PROFESSIONALS
    // =========================================================================

    private function createProfessionals(): void
    {
        $professionals = [
            [
                'name'    => 'Ahmed Al-Rashid',
                'email'   => 'ahmed.demo@mindova.test',
                'bio'     => 'Senior manufacturing engineer with 8+ years optimizing industrial production lines across Saudi Arabia and the GCC. Specialist in lean manufacturing, process automation, and Industry 4.0.',
                'field'   => 'Manufacturing',
                'level'   => 'Expert',
                'years'   => 8.0,
                'hours'   => 20,
                'stars'   => 520,
                'trust'   => 94.0,
                'domains' => ['Manufacturing', 'Process Engineering', 'Quality Control'],
                'skills'  => [
                    ['skill_name' => 'Lean Manufacturing',    'proficiency_level' => 'expert',       'years_of_experience' => 8, 'category' => 'Engineering'],
                    ['skill_name' => 'Process Optimization',  'proficiency_level' => 'expert',       'years_of_experience' => 7, 'category' => 'Engineering'],
                    ['skill_name' => 'Six Sigma',             'proficiency_level' => 'advanced',     'years_of_experience' => 5, 'category' => 'Quality'],
                    ['skill_name' => 'AutoCAD',               'proficiency_level' => 'advanced',     'years_of_experience' => 6, 'category' => 'Tools'],
                    ['skill_name' => 'PLC Programming',       'proficiency_level' => 'intermediate', 'years_of_experience' => 3, 'category' => 'Automation'],
                ],
            ],
            [
                'name'    => 'Sarah Mitchell',
                'email'   => 'sarah.demo@mindova.test',
                'bio'     => 'Data scientist with 6 years building predictive models and analytics platforms for Fortune 500 companies. Expert in Python, machine learning, and business intelligence. Based in London.',
                'field'   => 'Data Science',
                'level'   => 'Expert',
                'years'   => 6.0,
                'hours'   => 30,
                'stars'   => 310,
                'trust'   => 91.5,
                'domains' => ['Data Science', 'Business Intelligence', 'Analytics'],
                'skills'  => [
                    ['skill_name' => 'Python',           'proficiency_level' => 'expert',       'years_of_experience' => 6, 'category' => 'Programming'],
                    ['skill_name' => 'Machine Learning', 'proficiency_level' => 'expert',       'years_of_experience' => 5, 'category' => 'AI/ML'],
                    ['skill_name' => 'SQL',              'proficiency_level' => 'advanced',     'years_of_experience' => 6, 'category' => 'Database'],
                    ['skill_name' => 'TensorFlow',       'proficiency_level' => 'advanced',     'years_of_experience' => 4, 'category' => 'AI/ML'],
                    ['skill_name' => 'Power BI',         'proficiency_level' => 'intermediate', 'years_of_experience' => 3, 'category' => 'Analytics'],
                ],
            ],
            [
                'name'    => 'Marco Rossi',
                'email'   => 'marco.demo@mindova.test',
                'bio'     => 'Chemical process engineer with 12 years in petrochemical and specialty chemical industries. Deep expertise in reactor design, yield optimization, and process safety. 8 peer-reviewed publications.',
                'field'   => 'Chemical Engineering',
                'level'   => 'Expert',
                'years'   => 12.0,
                'hours'   => 15,
                'stars'   => 840,
                'trust'   => 97.0,
                'domains' => ['Chemical Engineering', 'Process Safety', 'R&D'],
                'skills'  => [
                    ['skill_name' => 'Chemical Process Design', 'proficiency_level' => 'expert',   'years_of_experience' => 12, 'category' => 'Engineering'],
                    ['skill_name' => 'Reactor Engineering',     'proficiency_level' => 'expert',   'years_of_experience' => 10, 'category' => 'Engineering'],
                    ['skill_name' => 'Aspen Plus',              'proficiency_level' => 'expert',   'years_of_experience' => 8,  'category' => 'Simulation'],
                    ['skill_name' => 'Process Safety',          'proficiency_level' => 'expert',   'years_of_experience' => 9,  'category' => 'Safety'],
                    ['skill_name' => 'HAZOP Analysis',          'proficiency_level' => 'advanced', 'years_of_experience' => 7,  'category' => 'Safety'],
                ],
            ],
            [
                'name'    => 'Fatima Al-Zahra',
                'email'   => 'fatima.demo@mindova.test',
                'bio'     => 'Business analyst with 5 years translating operational challenges into structured solutions. Experienced in requirements gathering, process mapping, and stakeholder management across UAE and GCC markets.',
                'field'   => 'Business Analysis',
                'level'   => 'Mid',
                'years'   => 5.0,
                'hours'   => 25,
                'stars'   => 145,
                'trust'   => 88.0,
                'domains' => ['Business Analysis', 'Operations', 'HR Technology'],
                'skills'  => [
                    ['skill_name' => 'Business Analysis',      'proficiency_level' => 'advanced',     'years_of_experience' => 5, 'category' => 'Business'],
                    ['skill_name' => 'Process Mapping',        'proficiency_level' => 'advanced',     'years_of_experience' => 4, 'category' => 'Business'],
                    ['skill_name' => 'JIRA',                   'proficiency_level' => 'advanced',     'years_of_experience' => 4, 'category' => 'Tools'],
                    ['skill_name' => 'Stakeholder Management', 'proficiency_level' => 'intermediate', 'years_of_experience' => 3, 'category' => 'Management'],
                    ['skill_name' => 'Power BI',               'proficiency_level' => 'intermediate', 'years_of_experience' => 2, 'category' => 'Analytics'],
                ],
            ],
            [
                'name'    => 'Carlos Mendez',
                'email'   => 'carlos.demo@mindova.test',
                'bio'     => 'Full-stack engineer with 10 years building enterprise web platforms. Expert in system architecture and cloud infrastructure. Led engineering teams of 12 at two Y Combinator-backed startups.',
                'field'   => 'Software Engineering',
                'level'   => 'Expert',
                'years'   => 10.0,
                'hours'   => 35,
                'stars'   => 430,
                'trust'   => 93.5,
                'domains' => ['Software Development', 'Cloud Architecture', 'DevOps'],
                'skills'  => [
                    ['skill_name' => 'React',               'proficiency_level' => 'expert',   'years_of_experience' => 7, 'category' => 'Frontend'],
                    ['skill_name' => 'Node.js',             'proficiency_level' => 'expert',   'years_of_experience' => 8, 'category' => 'Backend'],
                    ['skill_name' => 'PostgreSQL',          'proficiency_level' => 'advanced', 'years_of_experience' => 9, 'category' => 'Database'],
                    ['skill_name' => 'AWS',                 'proficiency_level' => 'advanced', 'years_of_experience' => 6, 'category' => 'Cloud'],
                    ['skill_name' => 'System Architecture', 'proficiency_level' => 'expert',   'years_of_experience' => 6, 'category' => 'Architecture'],
                ],
            ],
            [
                'name'    => 'Li Wei',
                'email'   => 'liwei.demo@mindova.test',
                'bio'     => 'Quality assurance engineer with 7 years implementing ISO standards and SPC in high-volume manufacturing. Certified Six Sigma Black Belt. Based in Singapore.',
                'field'   => 'Quality Assurance',
                'level'   => 'Expert',
                'years'   => 7.0,
                'hours'   => 20,
                'stars'   => 265,
                'trust'   => 95.0,
                'domains' => ['Quality Assurance', 'Manufacturing', 'Compliance'],
                'skills'  => [
                    ['skill_name' => 'ISO 9001',                    'proficiency_level' => 'expert',   'years_of_experience' => 7, 'category' => 'Quality'],
                    ['skill_name' => 'Statistical Process Control', 'proficiency_level' => 'expert',   'years_of_experience' => 6, 'category' => 'Quality'],
                    ['skill_name' => 'Root Cause Analysis',         'proficiency_level' => 'expert',   'years_of_experience' => 7, 'category' => 'Analysis'],
                    ['skill_name' => 'Minitab',                     'proficiency_level' => 'advanced', 'years_of_experience' => 5, 'category' => 'Tools'],
                    ['skill_name' => 'Failure Mode Analysis',       'proficiency_level' => 'advanced', 'years_of_experience' => 5, 'category' => 'Quality'],
                ],
            ],
            [
                'name'    => 'Emma Johnson',
                'email'   => 'emma.demo@mindova.test',
                'bio'     => 'Healthcare operations consultant with 9 years improving clinical supply chains and regulatory compliance for NHS Trusts and private hospital groups. PRINCE2 certified.',
                'field'   => 'Healthcare',
                'level'   => 'Expert',
                'years'   => 9.0,
                'hours'   => 20,
                'stars'   => 615,
                'trust'   => 96.5,
                'domains' => ['Healthcare', 'Supply Chain', 'Regulatory Compliance'],
                'skills'  => [
                    ['skill_name' => 'Healthcare Supply Chain', 'proficiency_level' => 'expert',   'years_of_experience' => 9, 'category' => 'Healthcare'],
                    ['skill_name' => 'Clinical Operations',     'proficiency_level' => 'expert',   'years_of_experience' => 8, 'category' => 'Healthcare'],
                    ['skill_name' => 'NHS Procurement',         'proficiency_level' => 'expert',   'years_of_experience' => 7, 'category' => 'Procurement'],
                    ['skill_name' => 'PRINCE2',                 'proficiency_level' => 'advanced', 'years_of_experience' => 5, 'category' => 'Management'],
                    ['skill_name' => 'Risk Management',         'proficiency_level' => 'advanced', 'years_of_experience' => 6, 'category' => 'Management'],
                ],
            ],
            [
                'name'    => 'Yusuf Okonkwo',
                'email'   => 'yusuf.demo@mindova.test',
                'bio'     => 'Logistics and supply chain manager with 11 years coordinating cross-border operations across Africa and the Middle East. Expert in demand forecasting and warehouse optimization. MBA from Lagos Business School.',
                'field'   => 'Logistics',
                'level'   => 'Expert',
                'years'   => 11.0,
                'hours'   => 15,
                'stars'   => 375,
                'trust'   => 90.5,
                'domains' => ['Logistics', 'Supply Chain', 'Operations Management'],
                'skills'  => [
                    ['skill_name' => 'Supply Chain Management', 'proficiency_level' => 'expert',       'years_of_experience' => 11, 'category' => 'Logistics'],
                    ['skill_name' => 'Demand Forecasting',      'proficiency_level' => 'expert',       'years_of_experience' => 9,  'category' => 'Analytics'],
                    ['skill_name' => 'SAP',                     'proficiency_level' => 'advanced',     'years_of_experience' => 7,  'category' => 'ERP'],
                    ['skill_name' => 'Warehouse Optimization',  'proficiency_level' => 'advanced',     'years_of_experience' => 8,  'category' => 'Logistics'],
                    ['skill_name' => 'ERP Systems',             'proficiency_level' => 'intermediate', 'years_of_experience' => 5,  'category' => 'ERP'],
                ],
            ],
            [
                'name'    => 'Priya Sharma',
                'email'   => 'priya.demo@mindova.test',
                'bio'     => 'Senior UX designer with 6 years crafting digital experiences for SaaS products and enterprise platforms. Expert in design systems, user research, and accessibility. 2M+ daily active users served.',
                'field'   => 'UX Design',
                'level'   => 'Expert',
                'years'   => 6.0,
                'hours'   => 30,
                'stars'   => 190,
                'trust'   => 89.0,
                'domains' => ['UX/UI Design', 'Product Design', 'Digital Experience'],
                'skills'  => [
                    ['skill_name' => 'Figma',             'proficiency_level' => 'expert',       'years_of_experience' => 6, 'category' => 'Design'],
                    ['skill_name' => 'User Research',     'proficiency_level' => 'expert',       'years_of_experience' => 5, 'category' => 'Research'],
                    ['skill_name' => 'Design Systems',    'proficiency_level' => 'advanced',     'years_of_experience' => 4, 'category' => 'Design'],
                    ['skill_name' => 'Prototyping',       'proficiency_level' => 'advanced',     'years_of_experience' => 6, 'category' => 'Design'],
                    ['skill_name' => 'WCAG Accessibility','proficiency_level' => 'intermediate', 'years_of_experience' => 3, 'category' => 'Design'],
                ],
            ],
            [
                'name'    => 'Daniel Weber',
                'email'   => 'daniel.demo@mindova.test',
                'bio'     => 'Financial analyst with 8 years building financial models and ROI analysis for chemical and manufacturing companies across the DACH region. CFA charterholder.',
                'field'   => 'Finance',
                'level'   => 'Expert',
                'years'   => 8.0,
                'hours'   => 20,
                'stars'   => 285,
                'trust'   => 92.0,
                'domains' => ['Finance', 'Cost Analysis', 'Strategic Planning'],
                'skills'  => [
                    ['skill_name' => 'Financial Modeling', 'proficiency_level' => 'expert',       'years_of_experience' => 8, 'category' => 'Finance'],
                    ['skill_name' => 'ROI Analysis',       'proficiency_level' => 'expert',       'years_of_experience' => 7, 'category' => 'Finance'],
                    ['skill_name' => 'Excel / VBA',        'proficiency_level' => 'expert',       'years_of_experience' => 8, 'category' => 'Tools'],
                    ['skill_name' => 'Cost Engineering',   'proficiency_level' => 'advanced',     'years_of_experience' => 6, 'category' => 'Finance'],
                    ['skill_name' => 'SAP FI/CO',          'proficiency_level' => 'intermediate', 'years_of_experience' => 4, 'category' => 'ERP'],
                ],
            ],
        ];

        foreach ($professionals as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make('Demo@2024!'),
                'user_type'         => 'volunteer',
                'is_active'         => true,
                'is_demo'           => true,
                'email_verified_at' => now()->subDays(rand(30, 180)),
            ]);

            $volunteer = Volunteer::create([
                'user_id'                    => $user->id,
                'bio'                        => $data['bio'],
                'field'                      => $data['field'],
                'experience_level'           => $data['level'],
                'years_of_experience'        => $data['years'],
                'availability_hours_per_week' => $data['hours'],
                'reputation_score'           => $data['stars'],
                'trust_score'                => $data['trust'],
                'expert_available'           => $data['stars'] >= 500,
                'professional_domains'       => $data['domains'],
                'general_nda_signed'         => true,
                'general_nda_signed_at'      => now()->subDays(rand(20, 120)),
                'general_nda_version'        => '2.1',
                'ai_analysis_status'         => 'completed',
                'ai_analysis_confidence'     => rand(82, 98) / 100,
                'validation_status'          => 'passed',
                'skills_normalized'          => true,
                'total_tasks_completed'      => rand(3, 25),
                'total_hours_contributed'    => rand(40, 300),
                'ai_analyzed_at'             => now()->subDays(rand(5, 60)),
            ]);

            foreach ($data['skills'] as $skill) {
                VolunteerSkill::create([
                    'volunteer_id'        => $volunteer->id,
                    'skill_name'          => $skill['skill_name'],
                    'category'            => $skill['category'],
                    'proficiency_level'   => $skill['proficiency_level'],
                    'years_of_experience' => $skill['years_of_experience'],
                    'source'              => 'manual',
                ]);
            }

            $this->professionals[$data['name']] = [
                'user'      => $user,
                'volunteer' => $volunteer,
            ];
        }
    }

    // =========================================================================
    // DEMO COMPANIES
    // =========================================================================

    private function createCompanies(): void
    {
        $companies = [
            [
                'name'         => 'Al-Noor Manufacturing',
                'email'        => 'alnoor.demo@mindova.test',
                'company_name' => 'Al-Noor Manufacturing Co.',
                'industry'     => 'Manufacturing',
                'description'  => 'A leading industrial manufacturer in Riyadh, Saudi Arabia, producing precision components for oil & gas, automotive, and construction sectors. 1,200+ employees across 4 facilities.',
                'website'      => 'https://alnoor-manufacturing.example',
            ],
            [
                'name'         => 'TechFlow Solutions',
                'email'        => 'techflow.demo@mindova.test',
                'company_name' => 'TechFlow Solutions FZ LLC',
                'industry'     => 'Information Technology',
                'description'  => 'Dubai-based SaaS company providing enterprise workflow automation and HR management platforms to 800+ companies across MENA. 2.1M monthly active users.',
                'website'      => 'https://techflow.example',
            ],
            [
                'name'         => 'MedCare Group',
                'email'        => 'medcare.demo@mindova.test',
                'company_name' => 'MedCare Group PLC',
                'industry'     => 'Healthcare',
                'description'  => 'UK-based private healthcare operator running 14 hospitals and 62 specialist clinics across the UK and Ireland. 380,000 patients served annually.',
                'website'      => 'https://medcare-group.example',
            ],
            [
                'name'         => 'Gulf Chemical Industries',
                'email'        => 'gci.demo@mindova.test',
                'company_name' => 'Gulf Chemical Industries LLC',
                'industry'     => 'Chemical',
                'description'  => 'Abu Dhabi-based specialty chemical manufacturer producing polymer intermediates and industrial solvents. 180,000 metric tonnes annual production, $420M revenue.',
                'website'      => 'https://gulf-chemical.example',
            ],
            [
                'name'         => 'TechFlow HR',
                'email'        => 'techflow-hr.demo@mindova.test',
                'company_name' => 'TechFlow Solutions (HR Division)',
                'industry'     => 'Information Technology',
                'description'  => 'HR division of TechFlow Solutions focused on internal talent development and organizational challenges.',
                'website'      => 'https://techflow.example/hr',
            ],
        ];

        foreach ($companies as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make('Demo@2024!'),
                'user_type'         => 'company',
                'is_active'         => true,
                'is_demo'           => true,
                'email_verified_at' => now()->subDays(rand(30, 180)),
            ]);

            $company = Company::create([
                'user_id'      => $user->id,
                'company_name' => $data['company_name'],
                'industry'     => $data['industry'],
                'description'  => $data['description'],
                'website'      => $data['website'],
            ]);

            $this->companies[$data['name']] = [
                'user'    => $user,
                'company' => $company,
            ];
        }
    }

    // =========================================================================
    // DEMO CHALLENGES
    // =========================================================================

    private function createChallenges(): array
    {
        $summary = [];

        // ── CHALLENGE 1: COMPLETED (Manufacturing Bottleneck) ───────────────
        $ch1 = $this->makeChallenge(
            companyEntry: $this->companies['Al-Noor Manufacturing'],
            data: [
                'title'                => 'Production Line Bottleneck at Assembly Station 7',
                'original_description' => 'Our Assembly Station 7 has been operating at approximately 60% of designed capacity for 3 months, causing cascading delays. We have 8 specialized machines and 14 operators, yet throughput dropped from 340 to 198 units/hour. Initial assessment points to micro-stoppages, changeover inefficiency, and a possible scheduling conflict between machine cycles. We need expert analysis to identify root cause and implement a scalable solution before Q3 delivery commitments.',
                'refined_brief'        => 'Production bottleneck at Assembly Station 7 reducing throughput by 42% (340 → 198 units/hr) over 3 months. Root cause analysis needed across machine micro-stoppages, changeover scheduling, and operator workflow. Solution must be implementable without production shutdown.',
                'field'                => 'Manufacturing',
                'score'                => 8,
                'complexity_level'     => 4,
                'challenge_type'       => 'team_execution',
                'status'               => 'archived',
                'estimated_budget'     => 85000,
                'currency'             => 'SAR',
                'tags'                 => ['lean-manufacturing', 'bottleneck', 'throughput', 'OEE'],
                'closed_at'            => now()->subDays(12),
                'ai_analyzed_at'       => now()->subDays(45),
                'completed_at'         => now()->subDays(14),
                'last_activity_at'     => now()->subDays(14),
            ]
        );

        $this->simulateCompletedChallenge($ch1, ['Ahmed Al-Rashid', 'Li Wei', 'Carlos Mendez'], [
            'ideas' => [
                [
                    'volunteer' => 'Ahmed Al-Rashid',
                    'content'   => 'Implement real-time OEE monitoring at Station 7 using IoT sensors on each machine. Micro-stoppage data collected over 72 hours will enable a Pareto analysis of downtime causes. My analysis of shift logs shows changeover between SKU-A and SKU-C is taking 38 minutes vs. the designed 18 minutes. A SMED workshop targeting this changeover could recover 15–18 units/hour immediately. Secondary recommendation: review machine cycle synchronization — machines 3 and 6 appear to be creating a 4-second idle gap each cycle.',
                    'ai_score'  => 91.5,
                    'votes_up'  => 18,
                ],
                [
                    'volunteer' => 'Li Wei',
                    'content'   => 'Root cause is likely a combination of: (1) Micro-defects causing inspection rejection loops — SPC charts show Cpk at 0.87 vs. the 1.33 target; (2) Operator rotation gaps during peak shift change; (3) Material staging delays from the upstream buffer. Recommend SPC implementation with automated alerts, plus a layout study to reduce walking distance by 30%.',
                    'ai_score'  => 87.2,
                    'votes_up'  => 12,
                ],
                [
                    'volunteer' => 'Carlos Mendez',
                    'content'   => 'The scheduling conflict between machine cycles is a software problem. The PLC program was designed for a 3-SKU product mix, but you are running 7 SKUs. The machine sequencing logic has not been updated. Rewriting the cycle scheduling algorithm for the current product mix should eliminate the 4-second idle gap and reduce micro-stoppages. Estimated impact: 22–26 units/hour recovery from software optimization alone.',
                    'ai_score'  => 85.0,
                    'votes_up'  => 9,
                ],
            ],
            'correct_idea_index' => 0,
            'tasks' => [
                ['title' => 'OEE Baseline Measurement & Pareto Analysis',  'volunteer' => 'Ahmed Al-Rashid', 'hours' => 32, 'quality' => 94, 'level' => 'Expert'],
                ['title' => 'SMED Workshop for SKU-A to SKU-C Changeover', 'volunteer' => 'Ahmed Al-Rashid', 'hours' => 24, 'quality' => 91, 'level' => 'Expert'],
                ['title' => 'SPC Implementation & Capability Analysis',     'volunteer' => 'Li Wei',          'hours' => 28, 'quality' => 96, 'level' => 'Expert'],
                ['title' => 'PLC Cycle Scheduling Algorithm Rewrite',       'volunteer' => 'Carlos Mendez',   'hours' => 40, 'quality' => 89, 'level' => 'Expert'],
                ['title' => 'Layout Optimization & Operator Flow Study',    'volunteer' => 'Li Wei',          'hours' => 20, 'quality' => 88, 'level' => 'Mid'],
            ],
            'story' => [
                'executive_summary'    => 'Al-Noor Manufacturing\'s Assembly Station 7 was operating at 58% capacity due to compounded bottlenecks: scheduling conflicts, changeover inefficiencies, and an outdated PLC algorithm. A 3-professional Mindova team diagnosed all root causes within 9 days and implemented integrated solutions that restored throughput to 341 units/hour — a 72% improvement — within 6 weeks. Annual savings: 2.3M SAR.',
                'initial_problem'      => 'Assembly Station 7 throughput declined from 340 to 198 units/hour (42% drop) over 3 months, threatening Q3 delivery commitments for 4 major clients. Internal diagnostics had not identified the root cause despite 6 weeks of investigation.',
                'ai_analysis'          => 'Mindova AI analyzed 847 shift log entries, 3 months of OEE data, and PLC event logs. Findings: (1) Changeover SKU-A→SKU-C drifted to 38 minutes vs. 18 designed — 31% of lost throughput; (2) PLC scheduling optimized for 3 SKUs but running 7 — creating 4-second idle gaps — 19% of lost throughput; (3) SPC Cpk 0.87 causing rejection loops — 12% of lost throughput.',
                'solution_timeline'    => 'Days 1–3: OEE instrumentation and data collection. Days 4–7: Root cause confirmed, SMED workshop designed, PLC scope defined. Days 8–14: SMED workshop executed — changeover reduced to 19 minutes. Days 15–28: PLC algorithm deployed on 2 test machines, SPC configured. Days 29–42: Full station rollout, operator training, layout adjustments.',
                'final_implementation' => 'Three integrated interventions: (1) SMED methodology reduced SKU-A→SKU-C changeover from 38 to 19 minutes; (2) PLC scheduling rewritten for 7-SKU mix, eliminating idle gaps; (3) SPC deployed with Cpk < 1.0 alerts, reducing rejection loops 78%. Layout study reduced operator walking distance 34%.',
                'results_achieved'     => 'Throughput: 341 units/hour (from 198, exceeding 340 target). OEE: 84.3% (from 57%). Changeover: 19 min (from 38). Cpk: 1.41 (from 0.87). Scrap: reduced 61%. Walking distance: reduced 34%.',
                'business_impact'      => 'Quarterly production increase: 18,200 units. Annual revenue impact: +3.1M SAR. Cost savings from reduced scrap and overtime: 890K SAR/year. Total annual benefit: 2.3M SAR. ROI: 27:1. Q3 delivery commitments met with 8-day buffer.',
                'company_testimonial'  => '"We had been struggling with this bottleneck for 3 months and our own engineers couldn\'t crack it. The Mindova team brought expertise from three disciplines — manufacturing, quality, and software — under one roof. Root cause confirmed on Day 7, solution running on Day 14. We are now applying the same methodology to two other stations." — Operations Director, Al-Noor Manufacturing Co.',
                'lessons_learned'      => 'Multi-disciplinary diagnosis is critical for complex bottlenecks. The root cause spanned software (PLC), process methodology (SMED), and quality systems (SPC). Real-time OEE instrumentation should be a permanent capability, not a diagnostic tool used only during failures.',
            ],
        ]);
        $summary[] = 'Challenge 1 (Manufacturing Bottleneck) — COMPLETED with success story';

        // ── CHALLENGE 2: COMPLETED (Portal Performance) ──────────────────────
        $ch2 = $this->makeChallenge(
            companyEntry: $this->companies['TechFlow Solutions'],
            data: [
                'title'                => 'Customer Portal Performance Degradation — 12x Load Time Spike',
                'original_description' => 'Our customer-facing portal has experienced severe performance degradation over 6 weeks. Page load time increased from 1.2 to 12.4 seconds. We\'ve seen a 35% drop in daily active sessions and received 847 support tickets mentioning slowness. The portal serves 2.1M monthly users across 38 countries running React with Node.js on AWS. The issue appeared after a simultaneous database migration and React upgrade 6 weeks ago. Internal engineers have been unable to isolate the cause — profiling shows problems across multiple layers.',
                'refined_brief'        => 'Enterprise portal degraded from 1.2s to 12.4s average load time post-migration. 35% DAU drop, 847 support tickets. React/Node.js/AWS stack. Multi-layer performance issue requiring systematic diagnosis and remediation.',
                'field'                => 'Software Development',
                'score'                => 7,
                'complexity_level'     => 3,
                'challenge_type'       => 'team_execution',
                'status'               => 'archived',
                'estimated_budget'     => 65000,
                'currency'             => 'AED',
                'tags'                 => ['performance', 'web-optimization', 'database', 'react', 'aws'],
                'closed_at'            => now()->subDays(8),
                'ai_analyzed_at'       => now()->subDays(28),
                'completed_at'         => now()->subDays(10),
                'last_activity_at'     => now()->subDays(10),
            ]
        );

        $this->simulateCompletedChallenge($ch2, ['Carlos Mendez', 'Priya Sharma', 'Sarah Mitchell'], [
            'ideas' => [
                [
                    'volunteer' => 'Carlos Mendez',
                    'content'   => 'The 12.4s load time and timing (post DB migration + React upgrade) strongly suggest N+1 query problems from the ORM upgrade and redundant React re-renders. Recommendation: (1) Run EXPLAIN ANALYZE on the 20 heaviest API endpoints to find missing indexes; (2) Add Redis caching for auth tokens and frequently-accessed data; (3) Enable React Profiler to identify components making redundant API calls. I estimate this addresses 70% of the issue within 5 business days.',
                    'ai_score'  => 93.0,
                    'votes_up'  => 24,
                ],
                [
                    'volunteer' => 'Sarah Mitchell',
                    'content'   => 'The degradation pattern — worse in Southeast Asia — suggests a CDN and asset delivery issue. The React upgrade likely increased bundle size significantly without CDN cache invalidation. Recommendation: (1) Analyze bundle size diff; (2) Implement code splitting and lazy loading; (3) Review CDN cache-hit ratios by region. This could account for 3–4 seconds of latency independently of the database issue.',
                    'ai_score'  => 88.5,
                    'votes_up'  => 16,
                ],
                [
                    'volunteer' => 'Priya Sharma',
                    'content'   => 'Implement skeleton screens and optimistic UI updates so users perceive the portal as faster during remediation — this won\'t solve the root problem but will reduce ticket volume 40–50% within 48 hours. Also, the support tickets mention slowness specifically during dashboard load — the dashboard may be loading all data synchronously instead of progressively.',
                    'ai_score'  => 76.0,
                    'votes_up'  => 8,
                ],
            ],
            'correct_idea_index' => 0,
            'tasks' => [
                ['title' => 'Database Query Profiling & Index Optimization',   'volunteer' => 'Carlos Mendez', 'hours' => 28, 'quality' => 97, 'level' => 'Expert'],
                ['title' => 'Redis Caching Layer Implementation',              'volunteer' => 'Carlos Mendez', 'hours' => 24, 'quality' => 92, 'level' => 'Expert'],
                ['title' => 'React Bundle Analysis & Code Splitting',          'volunteer' => 'Sarah Mitchell', 'hours' => 20, 'quality' => 90, 'level' => 'Expert'],
                ['title' => 'CDN Configuration & Regional Cache Optimization', 'volunteer' => 'Sarah Mitchell', 'hours' => 16, 'quality' => 88, 'level' => 'Mid'],
                ['title' => 'Dashboard Progressive Loading & Skeleton UI',     'volunteer' => 'Priya Sharma',   'hours' => 18, 'quality' => 85, 'level' => 'Mid'],
            ],
            'story' => [
                'executive_summary'    => 'TechFlow Solutions\' enterprise portal degraded from 1.2s to 12.4s average load time following a simultaneous DB migration and React upgrade. A Mindova team of 3 identified 4 root causes across database, caching, frontend bundle, and CDN layers. Load time reduced to 0.83 seconds in 18 days — faster than the original baseline. User sessions recovered to 97% within 3 weeks.',
                'initial_problem'      => 'The portal serving 2.1M monthly users across 38 countries experienced 933% load time degradation (1.2s → 12.4s). Daily active sessions dropped 35%, generating 847 support tickets. Internal engineering spent 4 weeks unable to isolate root cause due to simultaneous changes across multiple layers.',
                'ai_analysis'          => 'Mindova AI correlated APM traces, DB slow query logs, CDN access logs, and bundle analysis. Root causes: (1) DB migration introduced 23 missing indexes — 6.8s of latency; (2) Redis cache not configured for new auth token format — full DB lookup per request; (3) React v18 increased bundle from 287KB to 891KB without tree shaking — 2.1s on mobile; (4) CDN cache invalidation rules not updated — 34% miss rate.',
                'solution_timeline'    => 'Days 1–3: Profiling, query analysis, bundle audit. Day 4: 11 critical DB indexes added — 5.2s improvement visible immediately. Days 5–8: Redis reconfigured and expanded. Days 9–13: React bundle split into 6 chunks. Days 14–18: CDN updated, regional cache warmed, progressive dashboard loading deployed.',
                'final_implementation' => 'Four parallel workstreams: (1) 23 DB indexes added, 8 N+1 queries eliminated; (2) Redis cache expanded — DB read load reduced 73%; (3) React bundle split from 891KB to 6 route-based chunks, initial load 124KB; (4) CDN rules updated, achieving 91% cache hit rate across all regions.',
                'results_achieved'     => 'Page load: 0.83s (from 12.4s — 93% improvement, faster than pre-incident). DB query P95: 42ms (from 890ms). Bundle initial: 124KB (from 891KB). CDN hit rate: 91% (from 66%). DAU: 97% recovery in 3 weeks. Support tickets: reduced 94%. CSAT: 4.7/5 (from 2.9/5).',
                'business_impact'      => 'Revenue recovery: 180,000 AED/month from recovered sessions. 3 enterprise clients who had initiated cancellation conversations renewed. SLA penalty avoidance: 425,000 AED. Total 12-month economic impact: 2.1M AED.',
                'company_testimonial'  => '"The Mindova team found in 3 days what our engineers couldn\'t find in 4 weeks. They also communicated in business language, not just technical jargon. The results speak for themselves — we\'re now faster than we were before the incident." — CTO, TechFlow Solutions',
                'lessons_learned'      => 'Never deploy two significant changes simultaneously — the DB migration and React upgrade made root cause isolation exponentially harder. Performance regression testing should be automated in CI/CD. Code splitting should be standard for React applications serving a geographically diverse user base.',
            ],
        ]);
        $summary[] = 'Challenge 2 (Portal Performance) — COMPLETED with success story';

        // ── CHALLENGE 3: IN PROGRESS (Medical Supply Chain) ─────────────────
        $ch3 = $this->makeChallenge(
            companyEntry: $this->companies['MedCare Group'],
            data: [
                'title'                => 'Medical Supply Chain Disruption — 23 Stockouts in 18 Months',
                'original_description' => 'MedCare Group has experienced 23 critical medication stockout events across 6 of our 14 hospitals over the past 18 months. Three events required emergency procurement at 3–4x standard cost. Our inventory system runs on a legacy ERP with a simple reorder-point model. We believe the core problem involves inaccurate demand forecasting for seasonal medications, increased supplier lead time variability since 2022, and lack of cross-facility inventory visibility. We need a comprehensive analysis and a new supply chain resilience framework.',
                'refined_brief'        => 'Healthcare supply chain disruption: 23 stockouts in 18 months across 6 hospitals. Root causes: inaccurate seasonal demand forecasting, increased supplier lead time variability, no cross-facility visibility. Need resilience framework and ERP enhancement roadmap.',
                'field'                => 'Healthcare',
                'score'                => 7,
                'complexity_level'     => 3,
                'challenge_type'       => 'team_execution',
                'status'               => 'in_progress',
                'estimated_budget'     => 95000,
                'currency'             => 'GBP',
                'tags'                 => ['supply-chain', 'healthcare', 'inventory', 'forecasting'],
                'closed_at'            => now()->addDays(18),
                'ai_analyzed_at'       => now()->subDays(12),
                'last_activity_at'     => now()->subDays(1),
            ]
        );

        $this->simulateInProgressChallenge($ch3, ['Emma Johnson', 'Yusuf Okonkwo', 'Fatima Al-Zahra'], [
            'ideas' => [
                [
                    'volunteer' => 'Emma Johnson',
                    'content'   => 'The 23 stockout events reflect three interconnected failures: (1) Your reorder-point model does not account for seasonal demand spikes — respiratory medications spike 340% in November–January in UK hospitals; (2) Supplier lead times increased from 2.1 to 5.8 days but your safety stock still assumes 2.1 days; (3) Hospital A may carry 90-day stock while Hospital B is in stockout. I recommend: seasonal demand model rebuild using 3 years of dispense data; safety stock recalculation using current lead time distributions; real-time inter-hospital transfer protocol.',
                    'ai_score'  => 94.5,
                    'votes_up'  => 21,
                ],
                [
                    'volunteer' => 'Yusuf Okonkwo',
                    'content'   => 'The supply chain problem has a procurement contracting dimension being overlooked. Current supplier contracts have 7-day cancellation clauses and no demand signaling. Renegotiating to include: (1) Rolling 90-day demand forecasts shared with suppliers; (2) Buffer stock agreements where suppliers hold 14 days of your consumption; (3) Second-source qualification for top 20 critical medications. This alone would have prevented 14 of the 23 stockout events.',
                    'ai_score'  => 88.0,
                    'votes_up'  => 15,
                ],
                [
                    'volunteer' => 'Fatima Al-Zahra',
                    'content'   => 'The ERP enhancement roadmap should prioritize a cross-facility inventory dashboard as a quick win — this is a reporting layer, not a system change, deployable in 2 weeks using existing ERP data. The dashboard gives pharmacy directors real-time network-wide stock visibility and triggers automated transfer requests when any facility drops below 14-day supply.',
                    'ai_score'  => 81.5,
                    'votes_up'  => 11,
                ],
            ],
            'correct_idea_index' => 0,
            'tasks' => [
                ['title' => 'Historical Demand Analysis & Seasonal Model Build',   'volunteer' => 'Emma Johnson',    'status' => 'in_progress', 'level' => 'Expert'],
                ['title' => 'Safety Stock Recalculation Using Current Lead Times', 'volunteer' => 'Emma Johnson',    'status' => 'pending',     'level' => 'Expert'],
                ['title' => 'Cross-Facility Inventory Dashboard Design',           'volunteer' => 'Fatima Al-Zahra', 'status' => 'completed',   'level' => 'Mid'],
                ['title' => 'Supplier Contract Renegotiation Framework',           'volunteer' => 'Yusuf Okonkwo',  'status' => 'in_progress', 'level' => 'Expert'],
                ['title' => 'Inter-Hospital Transfer Protocol Documentation',      'volunteer' => 'Yusuf Okonkwo',  'status' => 'pending',     'level' => 'Mid'],
            ],
        ]);
        $summary[] = 'Challenge 3 (Medical Supply Chain) — IN PROGRESS';

        // ── CHALLENGE 4: IN PROGRESS (Reactor Yield) ─────────────────────────
        $ch4 = $this->makeChallenge(
            companyEntry: $this->companies['Gulf Chemical Industries'],
            data: [
                'title'                => 'Reactor R-204 Yield Optimization — 15-Point Gap Costing $850K/Month',
                'original_description' => 'Reactor Unit R-204, our primary polymer synthesis reactor, has been producing at 72% of theoretical yield for 4 months. Target yield is 87%. The 15-point gap translates to approximately $850,000 in lost revenue per month. Equipment failure has been eliminated — the reactor passed full inspection 5 months ago. Process engineers believe the issue may relate to feed stream quality variations, catalyst deactivation, or temperature/pressure profile optimization. We need expert chemical engineering and financial analysis to identify root cause, model the economic impact of each solution scenario, and design a corrective action plan.',
                'refined_brief'        => 'Polymer synthesis reactor R-204 at 72% yield vs. 87% target — 15-point gap costing $850K/month. Equipment ruled out. Root cause likely in feed quality variability, catalyst deactivation, or process parameter optimization. Need technical root cause analysis + financial modelling.',
                'field'                => 'Chemical Engineering',
                'score'                => 9,
                'complexity_level'     => 5,
                'challenge_type'       => 'team_execution',
                'status'               => 'in_progress',
                'estimated_budget'     => 120000,
                'currency'             => 'USD',
                'tags'                 => ['reactor', 'yield-optimization', 'chemical-engineering', 'catalyst'],
                'closed_at'            => now()->addDays(25),
                'ai_analyzed_at'       => now()->subDays(8),
                'last_activity_at'     => now()->subDays(2),
            ]
        );

        $this->simulateInProgressChallenge($ch4, ['Marco Rossi', 'Daniel Weber', 'Ahmed Al-Rashid'], [
            'ideas' => [
                [
                    'volunteer' => 'Marco Rossi',
                    'content'   => 'Based on the yield curve and 4-month timeline, this is almost certainly catalyst deactivation accelerated by feed stream impurities. The yield decline from 87% to 72% follows a first-order deactivation curve consistent with sulfur poisoning at 2–3 ppm above specification. My approach: (1) GC analysis on archived feed samples over 6 months to establish sulfur trend; (2) Kinetic modelling in Aspen Plus to quantify deactivation rate; (3) Evaluate catalyst regeneration vs. replacement vs. feed pretreatment. Preliminary diagnosis within 5 working days.',
                    'ai_score'  => 95.5,
                    'votes_up'  => 19,
                ],
                [
                    'volunteer' => 'Daniel Weber',
                    'content'   => 'The $850K/month figure needs refinement across remediation options. Full catalyst replacement: $1.2M–$1.8M but restores yield immediately. Feed pretreatment system: $380K–$520K CAPEX with $45K/month OPEX but takes 90 days to commission. I\'ll build an NPV model for each option over 24 months including lost production during downtime, enabling management to decide based on total cost, not upfront cost.',
                    'ai_score'  => 87.0,
                    'votes_up'  => 14,
                ],
                [
                    'volunteer' => 'Ahmed Al-Rashid',
                    'content'   => 'Temperature and pressure profiles should be reviewed — I\'ve seen similar yield drops caused by controller drift of just 2°C in inlet temperature, which wouldn\'t trigger alarms but significantly affects reaction selectivity. Recommend additional temperature measurement points in the reactor bed and a 72-hour parameter sensitivity study before concluding the cause is catalytic. This adds 3 days but could save $1.2M if the issue is control-related.',
                    'ai_score'  => 82.0,
                    'votes_up'  => 10,
                ],
            ],
            'correct_idea_index' => 0,
            'tasks' => [
                ['title' => 'Feed Stream GC Analysis & Sulfur Trend Report',      'volunteer' => 'Marco Rossi',    'status' => 'completed',   'level' => 'Expert'],
                ['title' => 'Reactor Kinetic Modelling in Aspen Plus',            'volunteer' => 'Marco Rossi',    'status' => 'in_progress', 'level' => 'Expert'],
                ['title' => '72-Hour Temperature & Pressure Parameter Study',     'volunteer' => 'Ahmed Al-Rashid','status' => 'completed',   'level' => 'Expert'],
                ['title' => 'NPV Financial Model for 3 Remediation Scenarios',    'volunteer' => 'Daniel Weber',   'status' => 'in_progress', 'level' => 'Expert'],
                ['title' => 'Catalyst Regeneration vs. Replacement Evaluation',   'volunteer' => 'Marco Rossi',    'status' => 'pending',     'level' => 'Expert'],
            ],
        ]);
        $summary[] = 'Challenge 4 (Reactor Yield Optimization) — IN PROGRESS';

        // ── CHALLENGE 5: ACTIVE (ideas phase) ────────────────────────────────
        $ch5 = $this->makeChallenge(
            companyEntry: $this->companies['TechFlow HR'],
            data: [
                'title'                => 'New Employee Onboarding — From Confusion to Clarity in 30 Days',
                'original_description' => 'TechFlow\'s growth from 120 to 380 employees over 18 months has exposed serious weaknesses in our onboarding process. New hire satisfaction dropped from 4.2 to 2.8 out of 5. Our 90-day voluntary turnover rate for new hires is 22%, up from 8%. HR spends 14 hours per new hire on manual tasks. New hires report feeling lost about company processes, systems, and culture for the first 3–4 weeks. We need a complete redesign of the onboarding experience — from pre-boarding digital welcome to the first 90-day structure.',
                'refined_brief'        => 'Onboarding failure: satisfaction dropped 4.2→2.8/5, 90-day turnover 22% (up from 8%), 14 manual HR hours per hire. Company scaled from 120→380 employees in 18 months. Need complete experience redesign with measurable satisfaction and retention impact.',
                'field'                => 'Human Resources',
                'score'                => 5,
                'complexity_level'     => 2,
                'challenge_type'       => 'community_discussion',
                'status'               => 'active',
                'estimated_budget'     => 35000,
                'currency'             => 'AED',
                'tags'                 => ['HR', 'onboarding', 'employee-experience', 'people-ops'],
                'closed_at'            => now()->addDays(30),
                'ai_analyzed_at'       => now()->subDays(3),
                'last_activity_at'     => now()->subDays(1),
            ]
        );

        $this->simulateActiveChallenge($ch5, [
            [
                'volunteer' => 'Priya Sharma',
                'content'   => 'The 22% 90-day turnover rate tells me this isn\'t just about information delivery — it\'s about belonging. My UX research shows new hires who feel "seen" in the first week stay significantly longer. Proposal: (1) Personalized digital welcome portal adapting to each hire\'s role and learning style; (2) Interactive 30-60-90 day roadmap with milestone check-ins; (3) Peer buddy system for the first 4 weeks; (4) Automate the 14 manual HR tasks using existing HRIS. Peer buddy systems alone typically reduce 90-day turnover by 30–40%.',
                'ai_score'  => 89.5,
                'votes_up'  => 17,
            ],
            [
                'volunteer' => 'Fatima Al-Zahra',
                'content'   => 'Your 14 manual HR hours typically break down as: document collection (4h), system access provisioning (3h), meeting scheduling (2h), welcome comms (2h), equipment coordination (3h). All can be automated with an onboarding workflow tool integrated to your HRIS. Pair this with a structured cultural immersion program: 2 hours/week for the first month focused on how decisions get made — not just what people do.',
                'ai_score'  => 86.0,
                'votes_up'  => 13,
            ],
            [
                'volunteer' => 'Carlos Mendez',
                'content'   => 'The biggest onboarding failure mode is information architecture — too much, too soon. New hires don\'t need an 80-page handbook; they need just-in-time information. Build a lightweight knowledge base organized by role and week-in-tenure: Week 1 — the 10 things needed to survive; Week 2 — team processes; Month 2 — broader organizational context. This sequencing reduces the "drinking from a firehose" feeling reflected in your 2.8/5 scores.',
                'ai_score'  => 82.5,
                'votes_up'  => 9,
            ],
        ]);
        $summary[] = 'Challenge 5 (Employee Onboarding) — ACTIVE (ideas phase)';

        return $summary;
    }

    // =========================================================================
    // WORKFLOW SIMULATORS
    // =========================================================================

    private function simulateCompletedChallenge(Challenge $ch, array $profNames, array $data): void
    {
        $companyUserId = $ch->company->user_id;

        // 1. Create ideas
        $ideas = [];
        foreach ($data['ideas'] as $ideaData) {
            $pro    = $this->professionals[$ideaData['volunteer']];
            $ideas[] = Idea::create([
                'challenge_id'         => $ch->id,
                'volunteer_id'         => $pro['volunteer']->id,
                'content'              => $ideaData['content'],
                'ai_quality_score'     => $ideaData['ai_score'],
                'ai_relevance_score'   => $ideaData['ai_score'] - rand(2, 8),
                'ai_feasibility_score' => $ideaData['ai_score'] - rand(1, 5),
                'ai_score'             => $ideaData['ai_score'],
                'final_score'          => $ideaData['ai_score'],
                'community_votes_up'   => $ideaData['votes_up'],
                'community_votes_down' => rand(0, 3),
                'status'               => 'scored',
                'is_correct_answer'    => false,
            ]);
        }

        $correctIdea = $ideas[$data['correct_idea_index']];
        $correctIdea->update(['is_correct_answer' => true]);
        $ch->update(['correct_idea_id' => $correctIdea->id]);

        // 2. Create workstream
        $workstream = Workstream::create([
            'challenge_id' => $ch->id,
            'title'        => 'Main Workstream',
            'description'  => 'Primary execution workstream for: ' . $ch->title,
            'order'        => 1,
            'status'       => 'completed',
        ]);

        // 3. Create tasks and assignments
        $baseDate = now()->subDays(50);
        $hoursMap = [];

        foreach ($data['tasks'] as $i => $taskData) {
            $pro = $this->professionals[$taskData['volunteer']];

            $task = Task::create([
                'workstream_id'             => $workstream->id,
                'challenge_id'              => $ch->id,
                'title'                     => $taskData['title'],
                'description'               => 'Deliver comprehensive analysis and implementation for: ' . $taskData['title'] . '. All acceptance criteria must be met with documented deliverables.',
                'required_skills'           => json_encode([$pro['volunteer']->field]),
                'required_experience_level' => $taskData['level'],
                'expected_output'           => 'Completed deliverable with documented findings and implementation evidence for: ' . $taskData['title'],
                'acceptance_criteria'       => json_encode(['All acceptance criteria met', 'Deliverable documented', 'Client review completed']),
                'estimated_hours'           => $taskData['hours'],
                'complexity_score'          => rand(6, 9),
                'priority'                  => $i === 0 ? 'high' : 'medium',
                'status'                    => 'completed',
                'order'                     => $i + 1,
                'quality_check_passed'      => true,
                'validation_status'         => 'passed',
            ]);

            $volunteer   = $pro['volunteer'];
            $startedAt   = $baseDate->copy()->addDays($i * 3 + rand(0, 2));
            $completedAt = $startedAt->copy()->addDays(rand(4, 8));

            $assignment = TaskAssignment::create([
                'task_id'           => $task->id,
                'volunteer_id'      => $volunteer->id,
                'invitation_status' => 'completed',
                'ai_match_score'    => rand(82, 97) / 100,
                'nda_signed'        => true,
                'invited_at'        => $baseDate->copy()->addDays($i * 2),
                'responded_at'      => $baseDate->copy()->addDays($i * 2 + 1),
                'started_at'        => $startedAt,
                'submitted_at'      => $completedAt->copy()->subDay(),
                'completed_at'      => $completedAt,
                'actual_hours'      => $taskData['hours'],
            ]);

            WorkSubmission::create([
                'task_assignment_id' => $assignment->id,
                'task_id'            => $task->id,
                'volunteer_id'       => $volunteer->id,
                'description'        => 'Completed deliverable for "' . $taskData['title'] . '". All acceptance criteria met. Final output documented and shared with the client.',
                'hours_worked'       => $taskData['hours'],
                'status'             => 'approved',
                'ai_quality_score'   => $taskData['quality'],
                'solves_task'        => true,
                'submitted_at'       => $completedAt->copy()->subDay(),
            ]);

            $volunteer->increment('total_tasks_completed');
            $volunteer->increment('total_hours_contributed', $taskData['hours']);

            $hoursMap[$taskData['volunteer']] = ($hoursMap[$taskData['volunteer']] ?? 0) + $taskData['hours'];

            ReputationHistory::create([
                'volunteer_id'  => $volunteer->id,
                'change_amount' => 25,
                'new_total'     => $volunteer->reputation_score + ($i + 1) * 5,
                'reason'        => 'task_completed',
                'related_type'  => Task::class,
                'related_id'    => $task->id,
                'created_at'    => $completedAt,
            ]);
        }

        // 4. Issue certificates
        foreach ($profNames as $name) {
            $pro       = $this->professionals[$name];
            $volunteer = $pro['volunteer'];
            $user      = $pro['user'];
            $hours     = $hoursMap[$name] ?? 40;

            $certificate = Certificate::create([
                'user_id'              => $user->id,
                'challenge_id'         => $ch->id,
                'company_id'           => $companyUserId,
                'certificate_type'     => 'completion',
                'role'                 => $volunteer->experience_level . ' ' . $volunteer->field . ' Specialist',
                'contribution_summary' => 'Expert contributor to "' . $ch->title . '", delivering measurable business impact through specialist analysis and implementation.',
                'contribution_types'   => $volunteer->professional_domains ?? ['Technical Contribution'],
                'technologies'         => $volunteer->professional_domains ?? ['Technical Contribution'],
                'industry'             => $ch->field,
                'total_hours'          => $hours,
                'company_confirmed'    => true,
                'confirmed_at'         => $ch->completed_at,
                'show_company_name'    => true,
                'issued_at'            => $ch->completed_at,
                'project_start_date'   => now()->subDays(50)->toDateString(),
                'project_end_date'     => $ch->completed_at?->toDateString(),
            ]);

            $certificate->update([
                'verification_hash' => Certificate::makeVerificationHash($certificate),
            ]);
        }

        // 5. Success story
        SuccessStory::create([
            'challenge_id'              => $ch->id,
            'executive_summary'         => $data['story']['executive_summary'],
            'initial_problem'           => $data['story']['initial_problem'],
            'ai_analysis'               => $data['story']['ai_analysis'],
            'team_members'              => array_map(fn($n) => ['name' => $n, 'field' => $this->professionals[$n]['volunteer']->field], $profNames),
            'solution_timeline'         => $data['story']['solution_timeline'],
            'final_implementation'      => $data['story']['final_implementation'],
            'results_achieved'          => $data['story']['results_achieved'],
            'business_impact'           => $data['story']['business_impact'],
            'company_testimonial'       => $data['story']['company_testimonial'],
            'lessons_learned'           => $data['story']['lessons_learned'],
            'total_verified_hours'      => array_sum(array_column($data['tasks'], 'hours')),
            'reputation_points_awarded' => count($profNames) * 75,
            'is_demo'                   => true,
            'is_published'              => true,
        ]);

        // 6. Notifications
        foreach ($profNames as $name) {
            $this->notify($this->professionals[$name]['user'], 'challenge_completed', 'Challenge Completed', '"' . $ch->title . '" has been completed. Your verified certificate has been issued.');
        }
        $companyEntry = $this->companyEntryFor($ch);
        if ($companyEntry) {
            $this->notify($companyEntry['user'], 'challenge_completed', 'Challenge Delivered', '"' . $ch->title . '" has been completed. Review the success story and certificates.');
        }
    }

    private function simulateInProgressChallenge(Challenge $ch, array $profNames, array $data): void
    {
        // Ideas
        foreach ($data['ideas'] as $i => $ideaData) {
            $pro = $this->professionals[$ideaData['volunteer']];
            Idea::create([
                'challenge_id'         => $ch->id,
                'volunteer_id'         => $pro['volunteer']->id,
                'content'              => $ideaData['content'],
                'ai_quality_score'     => $ideaData['ai_score'],
                'ai_relevance_score'   => $ideaData['ai_score'] - rand(2, 8),
                'ai_feasibility_score' => $ideaData['ai_score'] - rand(1, 5),
                'ai_score'             => $ideaData['ai_score'],
                'final_score'          => $ideaData['ai_score'],
                'community_votes_up'   => $ideaData['votes_up'],
                'community_votes_down' => rand(0, 2),
                'status'               => 'scored',
                'is_correct_answer'    => $i === $data['correct_idea_index'],
            ]);
        }

        // Workstream
        $workstream = Workstream::create([
            'challenge_id' => $ch->id,
            'title'        => 'Main Workstream',
            'description'  => 'Primary execution workstream for: ' . $ch->title,
            'order'        => 1,
            'status'       => 'active',
        ]);

        // Tasks
        foreach ($data['tasks'] as $i => $taskData) {
            $status = $taskData['status'];
            $pro    = $this->professionals[$taskData['volunteer']];

            $task = Task::create([
                'workstream_id'             => $workstream->id,
                'challenge_id'              => $ch->id,
                'title'                     => $taskData['title'],
                'description'               => 'Deliver comprehensive analysis for: ' . $taskData['title'],
                'required_skills'           => json_encode([$pro['volunteer']->field]),
                'required_experience_level' => $taskData['level'],
                'expected_output'           => 'Documented deliverable with findings and recommendations for: ' . $taskData['title'],
                'acceptance_criteria'       => json_encode(['Acceptance criteria met', 'Deliverable documented']),
                'estimated_hours'           => rand(16, 40),
                'complexity_score'          => rand(5, 9),
                'priority'                  => $i < 2 ? 'high' : 'medium',
                'status'                    => $status,
                'order'                     => $i + 1,
                'validation_status'         => $status === 'completed' ? 'passed' : 'pending',
            ]);

            $volunteer = $pro['volunteer'];

            if (in_array($status, ['in_progress', 'completed'])) {
                $invStatus  = $status === 'completed' ? 'completed' : 'in_progress';
                $assignment = TaskAssignment::create([
                    'task_id'           => $task->id,
                    'volunteer_id'      => $volunteer->id,
                    'invitation_status' => $invStatus,
                    'ai_match_score'    => rand(80, 96) / 100,
                    'nda_signed'        => true,
                    'invited_at'        => now()->subDays(10),
                    'responded_at'      => now()->subDays(9),
                    'started_at'        => now()->subDays(8),
                    'submitted_at'      => $status === 'completed' ? now()->subDays(1) : null,
                    'completed_at'      => $status === 'completed' ? now()->subDays(1) : null,
                    'actual_hours'      => $status === 'completed' ? rand(16, 32) : null,
                ]);

                if ($status === 'completed') {
                    WorkSubmission::create([
                        'task_assignment_id' => $assignment->id,
                        'task_id'            => $task->id,
                        'volunteer_id'       => $volunteer->id,
                        'description'        => 'Deliverable for "' . $taskData['title'] . '" submitted for client review.',
                        'hours_worked'       => rand(16, 32),
                        'status'             => 'approved',
                        'ai_quality_score'   => rand(85, 96),
                        'solves_task'        => true,
                        'submitted_at'       => now()->subDays(1),
                    ]);
                }
            }
        }
    }

    private function simulateActiveChallenge(Challenge $ch, array $ideas): void
    {
        foreach ($ideas as $ideaData) {
            $pro = $this->professionals[$ideaData['volunteer']];
            Idea::create([
                'challenge_id'         => $ch->id,
                'volunteer_id'         => $pro['volunteer']->id,
                'content'              => $ideaData['content'],
                'ai_quality_score'     => $ideaData['ai_score'],
                'ai_relevance_score'   => $ideaData['ai_score'] - rand(2, 8),
                'ai_feasibility_score' => $ideaData['ai_score'] - rand(1, 5),
                'ai_score'             => $ideaData['ai_score'],
                'final_score'          => $ideaData['ai_score'],
                'community_votes_up'   => $ideaData['votes_up'],
                'community_votes_down' => rand(0, 2),
                'status'               => 'scored',
                'is_correct_answer'    => false,
            ]);
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function makeChallenge(array $companyEntry, array $data): Challenge
    {
        return Challenge::create([
            'company_id'           => $companyEntry['company']->id,
            'title'                => $data['title'],
            'original_description' => $data['original_description'],
            'refined_brief'        => $data['refined_brief'] ?? null,
            'field'                => $data['field'],
            'score'                => $data['score'],
            'complexity_level'     => $data['complexity_level'],
            'challenge_type'       => $data['challenge_type'],
            'status'               => $data['status'],
            'estimated_budget'     => $data['estimated_budget'],
            'currency'             => $data['currency'],
            'tags'                 => $data['tags'],
            'closed_at'            => $data['closed_at'],
            'ai_analysis_status'   => 'completed',
            'ai_analyzed_at'       => $data['ai_analyzed_at'],
            'completed_at'         => $data['completed_at'] ?? null,
            'last_activity_at'     => $data['last_activity_at'],
            'is_demo'              => true,
            'is_featured'          => true,
        ]);
    }

    private function companyEntryFor(Challenge $ch): ?array
    {
        foreach ($this->companies as $entry) {
            if ($entry['company']->id === $ch->company_id) {
                return $entry;
            }
        }
        return null;
    }

    private function notify(User $user, string $type, string $title, string $message): void
    {
        try {
            Notification::create([
                'user_id'    => $user->id,
                'type'       => $type,
                'title'      => $title,
                'message'    => $message,
                'action_url' => '#',
                'is_read'    => false,
            ]);
        } catch (\Exception $e) {
            Log::warning('Demo notification failed: ' . $e->getMessage());
        }
    }
}
