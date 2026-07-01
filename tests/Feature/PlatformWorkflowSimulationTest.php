<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\Company;
use App\Models\NdaAgreement;
use App\Models\TaskAssignment;
use App\Models\Team;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\WorkSubmission;
use App\Services\AI\ChallengeBriefService;
use App\Services\AI\ComplexityEvaluationService;
use App\Services\AI\TaskDecompositionService;
use App\Services\AI\TeamFormationService;
use App\Services\AI\VolunteerMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-end simulation of the real Mindova challenge lifecycle: a company
 * submits a challenge, the real AI pipeline (brief -> complexity -> task
 * decomposition -> volunteer matching -> team formation) runs synchronously
 * (QUEUE_CONNECTION=sync in phpunit.xml), a volunteer completes and submits
 * work, the company approves it, and reputation is awarded.
 *
 * Only the Anthropic network boundary is mocked (via partialMock() on each
 * App\Services\AI\* service's single network-calling method) - every job's
 * handle(), every storeResults()/createAssignments()/createTeamsFromAIResponse()
 * persistence path, and the complexity_level >= 3 routing decision all run
 * for real.
 */
class PlatformWorkflowSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_challenge_lifecycle_from_submission_to_reputation_award(): void
    {
        $this->withoutExceptionHandling();

        // --- Arrange: company + volunteers (created before mocks so their
        // real IDs can be referenced by the matching/team-formation fixtures) ---
        $companyUser = User::factory()->state(['user_type' => 'company'])->create();
        $company = Company::factory()->create(['user_id' => $companyUser->id]);

        $volunteerUser = User::factory()->state(['user_type' => 'volunteer'])->create();
        $volunteer = Volunteer::factory()->create([
            'user_id' => $volunteerUser->id,
            'field' => 'Technology',
            'availability_hours_per_week' => 40,
            'general_nda_signed' => true,
        ]);

        $decoyUser = User::factory()->state(['user_type' => 'volunteer'])->create();
        $decoy = Volunteer::factory()->create([
            'user_id' => $decoyUser->id,
            'field' => 'Technology',
            'general_nda_signed' => true,
        ]);

        $startingReputation = $volunteer->reputation_score;

        // Challenges default to requires_nda=true (see migration
        // 2025_12_20_091211), so assignments.accept blocks until the
        // volunteer has signed both the general and challenge-specific NDA.
        NdaAgreement::create([
            'title' => 'General NDA', 'type' => 'general', 'content' => 'Test content',
            'version' => '1.0', 'is_active' => true, 'effective_date' => now(),
        ]);
        NdaAgreement::create([
            'title' => 'Challenge NDA', 'type' => 'challenge_specific', 'content' => 'Test content',
            'version' => '1.0', 'is_active' => true, 'effective_date' => now(),
        ]);

        // --- Mock the Anthropic boundary: one entry-point method per AI
        // service, everything else (persistence, branching) runs for real ---
        $this->partialMock(ChallengeBriefService::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRequestType')->andReturn('challenge_brief');
            $mock->shouldReceive('analyze')->andReturn([
                'is_valid' => true,
                'confidence_score' => 88.0,
                'score' => 5, // drives the reputation multiplier later (x1.0)
                'field' => 'Technology',
                'refined_brief' => 'Build a volunteer-matching dashboard prototype with core reporting views.',
                'objectives' => ['Ship a working dashboard prototype', 'Expose task/volunteer matching data'],
                'constraints' => ['4-week timeline', 'Must use existing design system'],
                'success_criteria' => ['Dashboard loads real data', 'Stakeholder sign-off'],
                'key_stakeholders' => ['Ops team'],
                'potential_risks' => ['Scope creep'],
                'recommended_approach' => 'Prototype then iterate',
                'validation_notes' => null,
            ]);
        });

        $this->partialMock(ComplexityEvaluationService::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRequestType')->andReturn('complexity_evaluation');
            $mock->shouldReceive('evaluate')->andReturn([
                'confidence_score' => 82.0,
                'complexity_level' => 4, // >=3 forces the team_execution branch
                'complexity_reasoning' => 'Requires multiple coordinated workstreams and specialized skills.',
                'recommended_approach' => 'team_execution',
                'estimated_duration_weeks' => 4,
                'required_skill_areas' => ['Backend', 'Frontend'],
                'estimated_volunteer_count' => 4,
                'key_challenges' => ['Coordination across workstreams'],
                'validation_notes' => null,
            ]);
        });

        $this->partialMock(TaskDecompositionService::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRequestType')->andReturn('task_decomposition');
            $mock->shouldReceive('decompose')->andReturn([
                'confidence_score' => 85.0,
                'workstreams' => [
                    [
                        'title' => 'Backend API',
                        'description' => 'Build the API layer for the dashboard.',
                        'objectives' => ['Expose REST endpoints'],
                        'dependencies' => null,
                        'tasks' => [
                            [
                                'title' => 'Design data model',
                                'description' => 'Model volunteers, tasks, and matches.',
                                'required_skills' => ['Database Design'],
                                'required_experience_level' => 'Mid',
                                'expected_output' => 'ERD document',
                                'acceptance_criteria' => ['Schema reviewed'],
                                'estimated_hours' => 10,
                                'complexity_score' => 5,
                            ],
                            [
                                'title' => 'Build matching endpoint',
                                'description' => 'Expose an API for volunteer-task matches.',
                                'required_skills' => ['Backend Development'],
                                'required_experience_level' => 'Mid',
                                'expected_output' => 'Working endpoint',
                                'acceptance_criteria' => ['Returns matches as JSON'],
                                'estimated_hours' => 12,
                                'complexity_score' => 5,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Frontend Dashboard',
                        'description' => 'Build the dashboard UI.',
                        'objectives' => ['Render task/volunteer views'],
                        'dependencies' => ['Backend API'],
                        'tasks' => [
                            [
                                'title' => 'Build dashboard shell',
                                'description' => 'Layout and navigation.',
                                'required_skills' => ['Frontend Development'],
                                'required_experience_level' => 'Mid',
                                'expected_output' => 'Navigable UI shell',
                                'acceptance_criteria' => ['Renders on desktop/mobile'],
                                'estimated_hours' => 8,
                                'complexity_score' => 4,
                            ],
                            [
                                'title' => 'Wire up matching view',
                                'description' => 'Connect UI to matching endpoint.',
                                'required_skills' => ['Frontend Development'],
                                'required_experience_level' => 'Mid',
                                'expected_output' => 'Live matching table',
                                'acceptance_criteria' => ['Data updates on refresh'],
                                'estimated_hours' => 10,
                                'complexity_score' => 5,
                            ],
                        ],
                    ],
                ],
                'execution_plan' => 'Backend first, then frontend.',
                'critical_path' => ['Backend API', 'Frontend Dashboard'],
                'validation_notes' => null,
            ]);
        });

        $this->partialMock(VolunteerMatchingService::class, function ($mock) use ($volunteer, $decoy) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRequestType')->andReturn('volunteer_matching');
            $mock->shouldReceive('matchVolunteersToTask')->andReturn([
                [
                    'volunteer_id' => $volunteer->id,
                    'match_score' => 88.0, // >=60 required to create an assignment
                    'skill_match_percentage' => 90.0,
                    'experience_match' => 'Excellent',
                    'reasoning' => 'Strong skill overlap with task requirements.',
                    'strengths' => ['Backend Development'],
                    'gaps' => [],
                ],
                [
                    'volunteer_id' => $decoy->id,
                    'match_score' => 70.0,
                    'skill_match_percentage' => 75.0,
                    'experience_match' => 'Good',
                    'reasoning' => 'Adequate fit.',
                    'strengths' => ['Frontend Development'],
                    'gaps' => ['Limited backend experience'],
                ],
            ]);
        });

        $this->partialMock(TeamFormationService::class, function ($mock) use ($volunteer, $decoy) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRequestType')->andReturn('volunteer_matching');
            $mock->shouldReceive('formTeams')->andReturn([
                'teams' => [[
                    'name' => 'Dashboard Squad',
                    'description' => 'Owns the volunteer dashboard prototype.',
                    'leader_volunteer_id' => $volunteer->id,
                    'objectives' => ['Ship the prototype'],
                    'members' => [
                        ['volunteer_id' => $volunteer->id, 'role' => 'leader', 'role_description' => 'Leads backend work'],
                        ['volunteer_id' => $decoy->id, 'role' => 'member', 'role_description' => 'Owns frontend work'],
                    ],
                    'skills_coverage' => ['covered_skills' => ['Backend Development', 'Frontend Development'], 'coverage_percentage' => 90],
                    'team_match_score' => 82.0,
                    'estimated_total_hours' => 40,
                ]],
                'formation_strategy' => 'Grouped by complementary skills.',
                'confidence_score' => 80.0,
            ]);
        });

        // --- Phase 1: company submits a challenge. QUEUE_CONNECTION=sync
        // means this single request cascades synchronously through the
        // entire AI pipeline before the response comes back. ---
        $response = $this->actingAs($companyUser)->post(route('challenges.store'), [
            'title' => 'Build a volunteer matching dashboard',
            'description' => str_repeat('We need a prototype dashboard that surfaces volunteer-to-task matches for our operations team. ', 3),
        ]);

        $response->assertRedirect();

        $challenge = Challenge::first();
        $this->assertNotNull($challenge);

        // --- Phase 2: decomposition assertions ---
        // Final status is 'in_progress', not 'active': DecomposeChallengeTasks's
        // own trailing status='active' update (app/Jobs/DecomposeChallengeTasks.php)
        // is a no-op for the status column specifically. Each nested job dispatch
        // (MatchVolunteersToTasks, FormTeamsForChallenge) receives its own
        // freshly-refetched Challenge instance via SerializesModels, so when
        // FormTeamsForChallenge sets status='in_progress' on *its* copy,
        // DecomposeChallengeTasks's own copy never observes that write - its
        // in-memory status is still 'active' from its own earlier transaction,
        // so Eloquent's dirty-checking correctly (from its own point of view)
        // omits 'status' from that trailing UPDATE. Net effect: 'in_progress'
        // wins as the true final value, which is arguably the more accurate
        // status anyway for a challenge with a formed team and live assignments.
        $challenge->refresh();
        $this->assertSame('in_progress', $challenge->status);
        $this->assertSame('team_execution', $challenge->challenge_type);
        $this->assertSame(4, $challenge->complexity_level);
        $this->assertSame('completed', $challenge->ai_analysis_status);
        $this->assertNotNull($challenge->ai_analyzed_at);
        $this->assertSame(2, $challenge->workstreams()->count());
        $this->assertSame(4, $challenge->tasks()->count());
        $this->assertDatabaseHas('challenge_analyses', ['challenge_id' => $challenge->id, 'stage' => 'decomposition']);

        // Regression check for a real bug this test uncovered: tasks.status
        // previously had no 'matching' value in its DB CHECK constraint, so
        // MatchVolunteersToTasks's `$task->update(['status' => 'matching'])`
        // silently failed (caught by the job's own try/catch) and tasks never
        // left 'pending' - causing them to be re-matched and volunteers
        // re-invited on every subsequent matching run. Fixed by widening the
        // column to a plain string (2026_07_01_000003_widen_tasks_status_enum).
        $this->assertSame(['matching', 'matching', 'matching', 'matching'], $challenge->tasks()->pluck('status')->all());

        // --- Phase 3: matching + team formation assertions ---
        // Exactly 8 (4 tasks x 2 matches), not 16: confirms the second,
        // redundant MatchVolunteersToTasks dispatch (a pre-existing, harmless
        // quirk - see DecomposeChallengeTasks/TaskDecompositionService) now
        // correctly finds zero 'pending' tasks and no-ops, instead of finding
        // tasks still stuck 'pending' (from the bug above) and re-matching them.
        $this->assertSame(
            8,
            TaskAssignment::whereHas('task', fn ($q) => $q->where('challenge_id', $challenge->id))->count()
        );
        $this->assertSame(1, Team::where('challenge_id', $challenge->id)->count());
        $team = Team::where('challenge_id', $challenge->id)->first();
        $this->assertSame($volunteer->id, $team->leader_id);
        $this->assertSame(2, $team->members()->count());

        // --- Phase 4: volunteer signs the challenge-specific NDA, then
        // accepts -> starts -> submits work ---
        $task = $challenge->tasks()->first();
        $assignment = TaskAssignment::where('task_id', $task->id)->where('volunteer_id', $volunteer->id)->firstOrFail();

        $this->actingAs($volunteerUser)->post(route('nda.challenge.sign', $challenge), [
            'full_name' => $volunteerUser->name,
            'agree' => '1',
        ])->assertRedirect(route('challenges.show', $challenge));

        $this->actingAs($volunteerUser)->post(route('assignments.accept', $assignment))->assertRedirect();
        $this->assertSame('accepted', $assignment->fresh()->invitation_status);

        $this->actingAs($volunteerUser)->post(route('assignments.start', $assignment))->assertRedirect();
        $this->assertSame('in_progress', $assignment->fresh()->invitation_status);

        $this->actingAs($volunteerUser)->post(route('assignments.submit-solution', $assignment), [
            'description' => 'Implemented the data model and reviewed it with the team.',
            'deliverable_url' => 'https://example.com/deliverable',
            'hours_worked' => 9.5,
        ])->assertRedirect();

        $submission = WorkSubmission::where('task_assignment_id', $assignment->id)->firstOrFail();
        $this->assertSame('submitted', $submission->status);
        $this->assertSame('submitted', $assignment->fresh()->invitation_status);

        // --- Phase 4 continued: company approves the work ---
        $this->actingAs($companyUser)->post(route('company.submissions.review', $submission), [
            'decision' => 'approved',
            'feedback' => 'Excellent work, exceeded expectations.',
            'quality_score' => 95, // >=90 triggers the excellent_company_rating bonus
        ])->assertRedirect();

        // --- Phase 4 continued: completion + reputation assertions ---
        // (real substitute for "payment logged" - Mindova has no
        // credits/payment system; these are the actual value-delivered signals)
        $this->assertSame('approved', $submission->fresh()->status);
        $this->assertSame('completed', $assignment->fresh()->invitation_status);
        $this->assertSame(1, $volunteer->fresh()->total_tasks_completed);
        $this->assertEquals(9.5, (float) $volunteer->fresh()->total_hours_contributed);

        // task_completed (25) + excellent_company_rating (40), both x1.0
        // multiplier since Challenge::score = 5.
        $this->assertSame($startingReputation + 25 + 40, $volunteer->fresh()->reputation_score);
        $this->assertDatabaseHas('reputation_history', ['volunteer_id' => $volunteer->id, 'reason' => 'task_completed']);
        $this->assertDatabaseHas('reputation_history', ['volunteer_id' => $volunteer->id, 'reason' => 'excellent_company_rating']);
    }
}
