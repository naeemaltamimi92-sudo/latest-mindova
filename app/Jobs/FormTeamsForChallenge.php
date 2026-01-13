<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Challenge;
use App\Models\Volunteer;
use App\Models\TaskAssignment;
use App\Services\AI\TeamFormationService;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class FormTeamsForChallenge implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 900; // 15 minutes

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 1200; // 20 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Challenge $challenge
    ) {}

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'form_teams_' . $this->challenge->id;
    }

    /**
     * Get the model ID for logging.
     */
    protected function getModelId(): int|string
    {
        return $this->challenge->id;
    }

    /**
     * Execute the job.
     */
    public function handle(TeamFormationService $teamFormationService): void
    {
        $this->executeWithRobustHandling(function () use ($teamFormationService) {
            // Refresh the model
            $this->challenge->refresh();

            // Get all volunteers who have been matched to tasks in this challenge
            $matchedVolunteerIds = TaskAssignment::whereHas('task', function ($query) {
                $query->where('challenge_id', $this->challenge->id);
            })
                ->where('ai_match_score', '>=', 60)
                ->pluck('volunteer_id')
                ->unique();

            if ($matchedVolunteerIds->isEmpty()) {
                Log::warning("No matched volunteers found for challenge: {$this->challenge->id}");
                return;
            }

            // Load volunteers with their skills
            $volunteers = Volunteer::with('skills')
                ->whereIn('id', $matchedVolunteerIds)
                ->get();

            // Team formation requires at least 2 volunteers
            if ($volunteers->count() < 2) {
                Log::info("Insufficient volunteers for team formation (need 2+, have {$volunteers->count()}). Skipping team formation for challenge: {$this->challenge->id}");
                return;
            }

            // Use AI to form teams
            $teamFormationData = $teamFormationService->formTeams($this->challenge, $volunteers);

            // Create teams in database
            $teams = $teamFormationService->createTeamsFromAIResponse(
                $this->challenge,
                $teamFormationData
            );

            Log::info("Created {$teams->count()} team(s) for challenge: {$this->challenge->id}");

            // Send notifications to all team members
            $notificationService = app(NotificationService::class);
            foreach ($teams as $team) {
                $team->load('members.volunteer.user');

                foreach ($team->members as $member) {
                    if ($member->volunteer && $member->volunteer->user) {
                        try {
                            $isLeader = $member->role === 'leader';
                            $notificationService->send(
                                user: $member->volunteer->user,
                                type: 'team_invitation',
                                title: $isLeader ? 'Team Leadership Assigned' : 'Team Invitation',
                                message: $isLeader
                                    ? "You've been selected as the leader of {$team->name}!"
                                    : "You've been invited to join {$team->name} as a {$member->role}.",
                                actionUrl: route('challenges.show', $this->challenge->id)
                            );
                        } catch (\Exception $e) {
                            Log::error('Failed to send team invitation notification', [
                                'team_id' => $team->id,
                                'member_id' => $member->id,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }

            // Update challenge status
            $this->challenge->update([
                'status' => 'in_progress',
            ]);

            Log::info("Team formation completed for challenge: {$this->challenge->id}");

        }, ['challenge_id' => $this->challenge->id]);
    }

    /**
     * Handle job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->logJobFailed($exception, ['challenge_id' => $this->challenge->id]);

        // Store failure reason
        $this->storeFailureReason($exception->getMessage(), [
            'challenge_id' => $this->challenge->id,
            'challenge_title' => $this->challenge->title,
        ]);

        // Challenge remains active, team formation just failed
        // Admin can manually form teams or retry
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'challenge:' . $this->challenge->id,
            'team_formation',
        ];
    }
}
