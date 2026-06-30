<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class SuccessStoryController extends Controller
{
    // All demo stories available on the index
    private static function allStories(): array
    {
        return [
            self::storyConveyor(),
            self::storySupplyChain(),
            self::storyHrPlatform(),
        ];
    }

    public function index()
    {
        $stories = self::allStories();
        return view('success-stories.index', compact('stories'));
    }

    public function show(string $slug)
    {
        $stories = collect(self::allStories())->keyBy('slug');
        $story   = $stories->get($slug, self::storyConveyor());
        $related = $stories->reject(fn($s) => $s['slug'] === $story['slug'])->values()->take(2)->toArray();

        return view('success-stories.show', compact('story', 'related'));
    }

    // ── Demo Story 1: Industrial Conveyor Failure ─────────────────────────────

    private static function storyConveyor(): array
    {
        return [
            'slug'       => 'conveyor-belt-optimization',
            'field'      => 'Manufacturing',
            'color'      => 'indigo',
            'posted'     => 'March 12, 2025',
            'duration'   => '8 Days',

            'company' => [
                'name'      => 'Horizon Industrial Group',
                'logo'      => 'HIG',
                'industry'  => 'Heavy Manufacturing',
                'country'   => 'Saudi Arabia',
                'employees' => '1,200+',
                'rating'    => 4.9,
                'reviews'   => 38,
                'category'  => 'Process Engineering',
            ],

            'pain' => [
                'title'       => 'Critical Conveyor Belt Failures Causing Production Shutdown',
                'description' => "Our main conveyor belt assembly line (Unit C-7 and C-9) has been experiencing recurring mechanical failures every 72–90 hours. Each failure causes an unplanned shutdown of 4–6 hours, affecting an estimated 340 units of production output per event. We have replaced belts three times in the past 45 days with no improvement. The root cause remains unidentified. We suspect misalignment, thermal stress, or substandard replacement parts but lack the expertise to confirm. We need a verified engineering assessment and a permanent corrective action plan.",
                'priority'    => 'Critical',
                'budget'      => '$12,000 – $18,000',
                'files'       => ['Maintenance_Log_C7_C9.pdf', 'Belt_Failure_Photos.zip', 'Machine_Specs_2019.pdf'],
                'tags'        => ['Conveyor Systems', 'Root Cause Analysis', 'Process Engineering', 'Mechanical'],
            ],

            'ai_analysis' => [
                'summary'     => 'Recurring conveyor belt failure in Units C-7 and C-9 with a 72–90 hour MTBF. Pattern analysis suggests primary cause is dynamic misalignment compounded by thermal cycling during shift transitions. Secondary suspect: non-OEM replacement belts with incorrect tensile specifications.',
                'root_causes' => [
                    'Conveyor frame lateral drift (>2.3mm) detected from uploaded vibration logs',
                    'Belt tension inconsistency across shift changes (thermal expansion not compensated)',
                    'Replacement belt tensile rating 12% below OEM specification',
                    'Idler roller bearing wear causing load distribution imbalance',
                ],
                'expertise'   => ['Mechanical Engineering', 'Process Engineering', 'Vibration Analysis', 'Materials Science'],
                'team'        => 'Lead Process Engineer + Senior Mechanical Engineer + QA Specialist + AI Coordinator',
                'complexity'  => 7,
                'timeline'    => '6–10 days',
                'confidence'  => 91,
            ],

            'experts' => [
                [
                    'name'        => 'John Smith',
                    'avatar'      => 'JS',
                    'title'       => 'Senior Process Engineer',
                    'role'        => 'Lead Expert',
                    'country'     => 'Germany',
                    'stars'       => 5,
                    'trust'       => 96,
                    'challenges'  => 127,
                    'success'     => 96,
                    'skills'      => ['Process Optimization', 'RCA', 'Six Sigma', 'Lean Manufacturing'],
                    'color'       => 'indigo',
                ],
                [
                    'name'        => 'Ahmed Al-Rashidi',
                    'avatar'      => 'AA',
                    'title'       => 'Senior Mechanical Engineer',
                    'role'        => 'Core Expert',
                    'country'     => 'UAE',
                    'stars'       => 5,
                    'trust'       => 94,
                    'challenges'  => 84,
                    'success'     => 93,
                    'skills'      => ['Conveyor Systems', 'Vibration Analysis', 'Predictive Maintenance'],
                    'color'       => 'violet',
                ],
                [
                    'name'        => 'Sarah Chen',
                    'avatar'      => 'SC',
                    'title'       => 'Quality Assurance Specialist',
                    'role'        => 'Core Expert',
                    'country'     => 'Singapore',
                    'stars'       => 5,
                    'trust'       => 98,
                    'challenges'  => 71,
                    'success'     => 97,
                    'skills'      => ['ISO 9001', 'Root Cause Analysis', 'Failure Mode Analysis'],
                    'color'       => 'emerald',
                ],
                [
                    'name'        => 'Dr. Marcus Weber',
                    'avatar'      => 'MW',
                    'title'       => 'Materials Science Engineer',
                    'role'        => 'Supporting Expert',
                    'country'     => 'Germany',
                    'stars'       => 4,
                    'trust'       => 91,
                    'challenges'  => 53,
                    'success'     => 89,
                    'skills'      => ['Material Fatigue', 'Polymer Testing', 'Tensile Analysis'],
                    'color'       => 'amber',
                ],
                [
                    'name'        => 'Mindova AI',
                    'avatar'      => 'AI',
                    'title'       => 'AI Project Coordinator',
                    'role'        => 'System Generated',
                    'country'     => 'Platform',
                    'stars'       => 0,
                    'trust'       => 100,
                    'challenges'  => 2400,
                    'success'     => 94,
                    'skills'      => ['Task Decomposition', 'Quality Scoring', 'Pattern Recognition'],
                    'color'       => 'cyan',
                    'is_ai'       => true,
                ],
            ],

            'timeline' => [
                ['event' => 'Company published challenge',       'time' => 'Mar 12 · 09:14',  'status' => 'completed', 'icon' => 'briefcase'],
                ['event' => 'AI analyzed challenge brief',       'time' => 'Mar 12 · 09:16',  'status' => 'completed', 'icon' => 'cpu'],
                ['event' => 'Complexity scored: 7/10',          'time' => 'Mar 12 · 09:17',  'status' => 'completed', 'icon' => 'chart'],
                ['event' => '4 experts invited by AI',          'time' => 'Mar 12 · 09:20',  'status' => 'completed', 'icon' => 'users'],
                ['event' => 'All experts accepted',             'time' => 'Mar 12 · 11:45',  'status' => 'completed', 'icon' => 'check'],
                ['event' => 'NDA signed by all parties',        'time' => 'Mar 12 · 12:30',  'status' => 'completed', 'icon' => 'shield'],
                ['event' => 'Discovery phase started',          'time' => 'Mar 13 · 08:00',  'status' => 'completed', 'icon' => 'search'],
                ['event' => 'AI decomposed 6 tasks',            'time' => 'Mar 13 · 08:05',  'status' => 'completed', 'icon' => 'cpu'],
                ['event' => 'Tasks assigned to team',           'time' => 'Mar 13 · 08:10',  'status' => 'completed', 'icon' => 'assign'],
                ['event' => 'Implementation phase',             'time' => 'Mar 14–19',        'status' => 'completed', 'icon' => 'wrench'],
                ['event' => 'Final solution submitted',         'time' => 'Mar 19 · 16:30',  'status' => 'completed', 'icon' => 'upload'],
                ['event' => 'Company approved solution',        'time' => 'Mar 20 · 10:15',  'status' => 'completed', 'icon' => 'star'],
                ['event' => 'Certificates generated',           'time' => 'Mar 20 · 10:16',  'status' => 'completed', 'icon' => 'certificate'],
                ['event' => 'Reputation scores updated',        'time' => 'Mar 20 · 10:17',  'status' => 'completed', 'icon' => 'trophy'],
            ],

            'activity' => [
                ['actor' => 'John Smith',      'avatar' => 'JS', 'action' => 'uploaded Root Cause Analysis Report (22 pages)',    'time' => 'Mar 13 · 14:22', 'type' => 'upload'],
                ['actor' => 'Ahmed Al-Rashidi','avatar' => 'AA', 'action' => 'completed Task #2: Vibration Frequency Mapping',   'time' => 'Mar 14 · 09:18', 'type' => 'task'],
                ['actor' => 'Mindova AI',      'avatar' => 'AI', 'action' => 'generated interim summary — 3 patterns detected',  'time' => 'Mar 14 · 09:20', 'type' => 'ai'],
                ['actor' => 'Sarah Chen',      'avatar' => 'SC', 'action' => 'reviewed material compliance documentation',        'time' => 'Mar 14 · 11:45', 'type' => 'review'],
                ['actor' => 'Company',         'avatar' => 'HG', 'action' => 'requested clarification on belt spec tolerances',   'time' => 'Mar 15 · 08:30', 'type' => 'comment'],
                ['actor' => 'Dr. Marcus Weber','avatar' => 'MW', 'action' => 'completed tensile material analysis (Task #3)',     'time' => 'Mar 15 · 13:00', 'type' => 'task'],
                ['actor' => 'John Smith',      'avatar' => 'JS', 'action' => 'replied to company clarification with spec sheet',  'time' => 'Mar 15 · 14:10', 'type' => 'comment'],
                ['actor' => 'Ahmed Al-Rashidi','avatar' => 'AA', 'action' => 'completed Task #4: Frame Alignment Correction Plan','time' => 'Mar 16 · 10:30', 'type' => 'task'],
                ['actor' => 'Mindova AI',      'avatar' => 'AI', 'action' => 'scored submission quality: 94/100',                'time' => 'Mar 16 · 10:31', 'type' => 'ai'],
                ['actor' => 'Sarah Chen',      'avatar' => 'SC', 'action' => 'completed QA validation report (Task #5)',          'time' => 'Mar 17 · 15:45', 'type' => 'task'],
                ['actor' => 'John Smith',      'avatar' => 'JS', 'action' => 'submitted Executive Summary — project moved to Review','time' => 'Mar 19 · 16:30','type' => 'upload'],
                ['actor' => 'Company',         'avatar' => 'HG', 'action' => 'approved solution and issued 5-star rating',        'time' => 'Mar 20 · 10:15', 'type' => 'approve'],
            ],

            'contributions' => [
                [
                    'name'      => 'John Smith',
                    'avatar'    => 'JS',
                    'title'     => 'Senior Process Engineer',
                    'role'      => 'Lead Expert',
                    'color'     => 'indigo',
                    'tasks'     => ['Root Cause Analysis Report', 'Process Flow Review', 'Executive Summary'],
                    'hours'     => 18,
                    'files'     => 7,
                    'ideas'     => 4,
                    'accepted'  => 4,
                    'score'     => 98,
                    'verdict'   => 'Verified',
                ],
                [
                    'name'      => 'Ahmed Al-Rashidi',
                    'avatar'    => 'AA',
                    'title'     => 'Senior Mechanical Engineer',
                    'role'      => 'Core Expert',
                    'color'     => 'violet',
                    'tasks'     => ['Vibration Frequency Mapping', 'Frame Alignment Correction Plan'],
                    'hours'     => 14,
                    'files'     => 5,
                    'ideas'     => 3,
                    'accepted'  => 3,
                    'score'     => 95,
                    'verdict'   => 'Verified',
                ],
                [
                    'name'      => 'Sarah Chen',
                    'avatar'    => 'SC',
                    'title'     => 'QA Specialist',
                    'role'      => 'Core Expert',
                    'color'     => 'emerald',
                    'tasks'     => ['QA Validation Report', 'Compliance Documentation Review'],
                    'hours'     => 11,
                    'files'     => 4,
                    'ideas'     => 2,
                    'accepted'  => 2,
                    'score'     => 97,
                    'verdict'   => 'Verified',
                ],
                [
                    'name'      => 'Dr. Marcus Weber',
                    'avatar'    => 'MW',
                    'title'     => 'Materials Science Engineer',
                    'role'      => 'Supporting Expert',
                    'color'     => 'amber',
                    'tasks'     => ['Tensile Material Analysis & Spec Comparison'],
                    'hours'     => 7,
                    'files'     => 3,
                    'ideas'     => 2,
                    'accepted'  => 2,
                    'score'     => 91,
                    'verdict'   => 'Verified',
                ],
            ],

            'ai_summary' => [
                'problems'       => 3,
                'repeated_ideas' => 1,
                'recommendation' => 'Replace all idler rollers on C-7 and C-9 with OEM-grade SKF bearings, re-tension belts to 850N using laser alignment tools, and implement a 48-hour run-in protocol after each belt replacement. Install thermal sensors at junction points to catch expansion drift before failure.',
                'risks'          => [
                    'Short-term: 2-day production pause required for full alignment correction',
                    'Parts sourcing: SKF OEM idler rollers have a 4-day lead time from certified supplier',
                ],
                'business_impact'  => 'Elimination of unplanned downtime events expected to recover 340+ units/event × 1.2 events/week = approximately 408 additional units per week.',
                'cost_savings'     => '$18,400 / month',
                'confidence'       => 91,
            ],

            'solution' => [
                'executive_summary' => 'Root cause confirmed as a combination of idler roller bearing wear causing lateral belt drift and non-OEM replacement belts rated 12% below the required tensile specification. The combined effect creates a resonance failure at thermal equilibrium (typically 72–90 hours into operation). Corrective action: OEM roller replacement, laser re-alignment, and a mandatory 48-hour belt run-in procedure.',
                'improvements' => [
                    'Replaced 24 idler rollers across C-7 and C-9 with OEM SKF 6305-2Z bearings',
                    'Laser-aligned both conveyor frames — drift corrected from 2.3mm to 0.08mm',
                    'Established OEM-only belt procurement policy with tensile rating ≥950N',
                    'Implemented 48-hour post-installation run-in protocol with thermal monitoring',
                    'Installed 6 thermal sensors at junction points with automated alerts at 65°C',
                ],
                'before' => ['MTBF' => '72–90 hrs', 'Downtime/event' => '4–6 hrs', 'Monthly events' => '~5', 'Production loss' => '~1,700 units/mo'],
                'after'  => ['MTBF' => '720+ hrs',  'Downtime/event' => '0',       'Monthly events' => '0',  'Production loss' => '0'],
            ],

            'impact' => [
                ['label' => 'Production Increase',  'value' => '+21%',    'sub' => 'vs prior 90 days',     'color' => 'emerald', 'target' => 21],
                ['label' => 'Downtime Reduced',     'value' => '-37%',    'sub' => 'monthly hours saved',  'color' => 'blue',    'target' => 37],
                ['label' => 'Monthly Savings',      'value' => '$18,400', 'sub' => 'confirmed by finance', 'color' => 'violet',  'target' => 18400],
                ['label' => 'ROI',                  'value' => '312%',    'sub' => '30-day projection',    'color' => 'amber',   'target' => 312],
                ['label' => 'Implementation Time',  'value' => '8 Days',  'sub' => 'from challenge post',  'color' => 'cyan',    'target' => 8],
                ['label' => 'Expert Confidence',    'value' => '91%',     'sub' => 'AI confidence score',  'color' => 'pink',    'target' => 91],
            ],

            'feedback' => [
                'rep'       => 'Khalid Al-Harbi',
                'position'  => 'VP of Operations',
                'rating'    => 5,
                'review'    => "We tried to solve this internally for 45 days and replaced the belts three times without success. The Mindova team identified the root cause within 48 hours and had a corrective action plan running by day 4. The transparency of the process — seeing every task, every file, and every AI analysis in real time — is unlike anything we have experienced with traditional consultants. The ROI was confirmed within 30 days. We have already posted two additional challenges.",
                'recommend' => true,
                'date'      => 'March 21, 2025',
            ],

            'certificates' => [
                ['name' => 'John Smith',       'role' => 'Lead Process Engineer',       'hours' => 18, 'score' => 98, 'id' => 'MNV-2025-0312-001', 'color' => 'indigo'],
                ['name' => 'Ahmed Al-Rashidi', 'role' => 'Senior Mechanical Engineer',  'hours' => 14, 'score' => 95, 'id' => 'MNV-2025-0312-002', 'color' => 'violet'],
                ['name' => 'Sarah Chen',       'role' => 'QA Specialist',               'hours' => 11, 'score' => 97, 'id' => 'MNV-2025-0312-003', 'color' => 'emerald'],
            ],

            'reputation' => [
                ['name' => 'John Smith',       'avatar' => 'JS', 'stars_before' => 1118, 'stars_after' => 1203, 'gained' => 85,  'trust_before' => 94, 'trust_after' => 96, 'challenges' => ['127', '128'], 'hours' => ['921', '939'], 'color' => 'indigo'],
                ['name' => 'Ahmed Al-Rashidi', 'avatar' => 'AA', 'stars_before' => 742,  'stars_after' => 806,  'gained' => 64,  'trust_before' => 93, 'trust_after' => 94, 'challenges' => ['84', '85'],   'hours' => ['612', '626'], 'color' => 'violet'],
                ['name' => 'Sarah Chen',       'avatar' => 'SC', 'stars_before' => 610,  'stars_after' => 660,  'gained' => 50,  'trust_before' => 97, 'trust_after' => 98, 'challenges' => ['71', '72'],   'hours' => ['487', '498'], 'color' => 'emerald'],
                ['name' => 'Dr. Marcus Weber', 'avatar' => 'MW', 'stars_before' => 468,  'stars_after' => 506,  'gained' => 38,  'trust_before' => 90, 'trust_after' => 91, 'challenges' => ['53', '54'],   'hours' => ['341', '348'], 'color' => 'amber'],
            ],
        ];
    }

    // ── Demo Story 2: Supply Chain ─────────────────────────────────────────────

    private static function storySupplyChain(): array
    {
        return [
            'slug'     => 'supply-chain-visibility',
            'field'    => 'Logistics',
            'color'    => 'teal',
            'posted'   => 'February 3, 2025',
            'duration' => '11 Days',
            'company'  => ['name' => 'NexaTrade MENA', 'logo' => 'NT', 'industry' => 'Logistics & Supply Chain', 'country' => 'UAE', 'employees' => '650+', 'rating' => 4.8, 'reviews' => 24, 'category' => 'Operations'],
            'pain'     => ['title' => 'Supply Chain Blind Spots Causing $40K/Month in Losses', 'description' => 'We have no real-time visibility into our third-party warehouse partners. 30% of orders arrive late with no advance warning, causing customer escalations and emergency shipping costs.', 'priority' => 'High', 'budget' => '$8,000 – $14,000', 'files' => ['Order_Data_Q1.xlsx', 'Partner_SLA_Contracts.pdf'], 'tags' => ['Supply Chain', 'Data Engineering', 'KPI Design']],
            'ai_analysis' => ['summary' => 'Data silo problem between 3PL partners and internal ERP.', 'root_causes' => ['No API integration with 3PL WMS systems', 'Manual reporting causing 24–48hr data lag'], 'expertise' => ['Data Engineering', 'Logistics Operations'], 'team' => '2 experts + AI', 'complexity' => 6, 'timeline' => '8–12 days', 'confidence' => 87],
            'experts'  => [],
            'timeline' => [],
            'activity' => [],
            'contributions' => [],
            'ai_summary' => ['problems' => 2, 'repeated_ideas' => 0, 'recommendation' => 'API gateway + webhook layer between 3PL systems and internal ERP.', 'risks' => ['3PL partner API access requires legal review (~5 days)'], 'business_impact' => 'Real-time visibility expected to cut late-shipment rate from 30% to <5%.', 'cost_savings' => '$31,000 / month', 'confidence' => 87],
            'solution' => ['executive_summary' => 'Built a middleware integration layer connecting 3 partner WMS systems to internal ERP via REST APIs.', 'improvements' => ['REST API integration with all 3 partner WMS systems', 'Real-time webhook alerts for shipment status changes'], 'before' => ['On-time Rate' => '70%'], 'after' => ['On-time Rate' => '96%']],
            'impact' => [
                ['label' => 'Late Shipments Reduced', 'value' => '-82%', 'sub' => 'vs prior quarter', 'color' => 'teal',   'target' => 82],
                ['label' => 'Monthly Savings',        'value' => '$31K',  'sub' => 'confirmed',        'color' => 'emerald','target' => 31000],
                ['label' => 'ROI',                    'value' => '270%',  'sub' => '60-day',           'color' => 'blue',   'target' => 270],
                ['label' => 'Implementation',         'value' => '11 Days','sub' => 'end-to-end',      'color' => 'violet', 'target' => 11],
            ],
            'feedback'     => ['rep' => 'Fatima Al-Blooshi', 'position' => 'Head of Operations', 'rating' => 5, 'review' => 'Exceptional quality and speed. The team delivered a complete integration within 11 days — something our internal team estimated at 3 months.', 'recommend' => true, 'date' => 'February 15, 2025'],
            'certificates' => [],
            'reputation'   => [],
        ];
    }

    // ── Demo Story 3: HR Platform ─────────────────────────────────────────────

    private static function storyHrPlatform(): array
    {
        return [
            'slug'     => 'hr-onboarding-automation',
            'field'    => 'Human Resources',
            'color'    => 'pink',
            'posted'   => 'January 18, 2025',
            'duration' => '6 Days',
            'company'  => ['name' => 'PeopleBridge Corp', 'logo' => 'PB', 'industry' => 'SaaS / HR Technology', 'country' => 'Jordan', 'employees' => '200+', 'rating' => 4.7, 'reviews' => 19, 'category' => 'HR & People Ops'],
            'pain'     => ['title' => 'Employee Onboarding Taking 14 Days — Losing Talent Before Day One', 'description' => 'Our onboarding process takes 14 days on average. New hires are dropping out before their first day citing confusion and slow setup. We are losing 18% of accepted offers.', 'priority' => 'High', 'budget' => '$5,000 – $9,000', 'files' => ['Onboarding_Checklist.pdf', 'Exit_Interview_Analysis.xlsx'], 'tags' => ['HR Automation', 'UX Design', 'Process Optimization']],
            'ai_analysis' => ['summary' => 'Manual, fragmented onboarding process with no single source of truth for new hires.', 'root_causes' => ['7 separate tools with no integration', 'No self-service portal for new hires', '14 manual email sequences prone to human error'], 'expertise' => ['HR Technology', 'UX Design', 'Automation'], 'team' => '2 experts + AI', 'complexity' => 5, 'timeline' => '5–8 days', 'confidence' => 89],
            'experts'  => [],
            'timeline' => [],
            'activity' => [],
            'contributions' => [],
            'ai_summary' => ['problems' => 3, 'repeated_ideas' => 1, 'recommendation' => 'Unified onboarding portal with automated task sequences and real-time progress tracking.', 'risks' => ['Integration with existing HRIS requires IT access'], 'business_impact' => 'Reduce time-to-productivity from 14 days to 3 days and offer dropout from 18% to <3%.', 'cost_savings' => '$14,200 / month', 'confidence' => 89],
            'solution' => ['executive_summary' => 'Designed and implemented a self-service onboarding portal consolidating all 7 tools into one flow.', 'improvements' => ['Single portal replacing 7 disparate tools', 'Automated task assignment reducing HR manual work by 80%', 'Offer dropout rate reduced from 18% to 2.4%'], 'before' => ['Onboarding' => '14 days', 'Offer dropout' => '18%'], 'after' => ['Onboarding' => '3 days', 'Offer dropout' => '2.4%']],
            'impact' => [
                ['label' => 'Onboarding Time',    'value' => '-79%',  'sub' => '14 days → 3 days', 'color' => 'pink',   'target' => 79],
                ['label' => 'Offer Dropout',      'value' => '-87%',  'sub' => '18% → 2.4%',       'color' => 'rose',   'target' => 87],
                ['label' => 'Monthly Savings',    'value' => '$14.2K','sub' => 'HR cost reduction', 'color' => 'violet', 'target' => 14200],
                ['label' => 'Implementation',     'value' => '6 Days','sub' => 'end-to-end',        'color' => 'indigo', 'target' => 6],
            ],
            'feedback'     => ['rep' => 'Rania Khalil', 'position' => 'Chief People Officer', 'rating' => 5, 'review' => "We posted the challenge on Monday and had a full solution running by Sunday. The quality of insight from the Mindova team was genuinely impressive — they identified process problems we had missed internally.", 'recommend' => true, 'date' => 'January 25, 2025'],
            'certificates' => [],
            'reputation'   => [],
        ];
    }
}
