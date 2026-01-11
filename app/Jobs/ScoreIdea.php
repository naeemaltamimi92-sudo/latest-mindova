<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\Idea;
use App\Services\AI\IdeaScoringService;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ScoreIdea implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RobustJob;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 180; // 3 minutes

    /**
     * The number of seconds after which the job's unique lock will be released.
     */
    public int $uniqueFor = 300; // 5 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Idea $idea
    ) {}

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'score_idea_' . $this->idea->id;
    }

    /**
     * Get the model ID for logging.
     */
    protected function getModelId(): int|string
    {
        return $this->idea->id;
    }

    /**
     * Execute the job.
     */
    public function handle(
        IdeaScoringService $scoringService,
        NotificationService $notificationService
    ): void {
        $this->executeWithRobustHandling(function () use ($scoringService, $notificationService) {
            // Refresh the model
            $this->idea->refresh();

            // Skip if already scored
            if ($this->idea->ai_score !== null && $this->idea->status === 'scored') {
                Log::info('Idea already scored, skipping', [
                    'idea_id' => $this->idea->id,
                ]);
                return;
            }

            // Get the challenge
            $challenge = $this->idea->challenge;

            if (!$challenge) {
                throw new \Exception('Challenge not found for idea');
            }

            // Score the idea
            $scoring = $scoringService->scoreIdea($this->idea, $challenge);

            Log::info('Idea scoring completed', [
                'idea_id' => $this->idea->id,
                'ai_score' => $scoring['ai_score'] ?? 0,
            ]);

            // Store results
            $scoringService->storeResults($this->idea, $scoring);

            // Send notification to idea submitter
            try {
                $notificationService->notifyIdeaScored($this->idea);
            } catch (\Exception $e) {
                Log::error('Failed to send idea scored notification', [
                    'idea_id' => $this->idea->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Notify company of new idea
            try {
                $notificationService->notifyNewIdea($this->idea);
            } catch (\Exception $e) {
                Log::error('Failed to send new idea notification', [
                    'idea_id' => $this->idea->id,
                    'error' => $e->getMessage(),
                ]);
            }

            Log::info('Idea scoring results stored', [
                'idea_id' => $this->idea->id,
                'final_score' => $this->idea->final_score,
            ]);

        }, [
            'idea_id' => $this->idea->id,
            'challenge_id' => $this->idea->challenge_id,
        ]);
    }

    /**
     * Handle job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->logJobFailed($exception, ['idea_id' => $this->idea->id]);

        // Store failure reason
        $this->storeFailureReason($exception->getMessage(), [
            'idea_id' => $this->idea->id,
            'challenge_id' => $this->idea->challenge_id,
        ]);

        $this->idea->update(['status' => 'error']);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'idea:' . $this->idea->id,
            'challenge:' . $this->idea->challenge_id,
            'idea_scoring',
        ];
    }
}
