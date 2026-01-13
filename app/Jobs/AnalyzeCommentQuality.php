<?php

namespace App\Jobs;

use App\Jobs\Concerns\RobustJob;
use App\Models\ChallengeComment;
use App\Models\ReputationHistory;
use App\Services\AI\CommentScoringService;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeCommentQuality implements ShouldQueue, ShouldBeUnique
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
        public ChallengeComment $comment
    ) {}

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'analyze_comment_' . $this->comment->id;
    }

    /**
     * Get the model ID for logging.
     */
    protected function getModelId(): int|string
    {
        return $this->comment->id;
    }

    /**
     * Execute the job.
     */
    public function handle(CommentScoringService $scoringService): void
    {
        $this->executeWithRobustHandling(function () use ($scoringService) {
            // Refresh the model
            $this->comment->refresh();

            // Skip if already completed
            if ($this->comment->ai_score_status === 'completed') {
                Log::info('Comment analysis already completed, skipping', [
                    'comment_id' => $this->comment->id,
                ]);
                return;
            }

            // Update status
            $this->comment->update(['ai_score_status' => 'processing']);

            // Analyze comment quality
            $analysis = $scoringService->analyzeComment($this->comment);

            Log::info('Comment analysis completed', [
                'comment_id' => $this->comment->id,
                'score' => $analysis['score'] ?? 0,
            ]);

            // Store results
            $scoringService->storeResults($this->comment, $analysis);

            // Notify challenge owner about new high-quality comment (score >= 7)
            if (($analysis['score'] ?? 0) >= 7) {
                $this->handleHighQualityComment($analysis['score']);
            }

        }, [
            'comment_id' => $this->comment->id,
            'challenge_id' => $this->comment->challenge_id,
        ]);
    }

    /**
     * Handle high quality comment notifications and reputation.
     */
    protected function handleHighQualityComment(int $score): void
    {
        try {
            // Get the challenge owner (either company or volunteer)
            $challenge = $this->comment->challenge;
            $ownerUser = null;

            if ($challenge->isVolunteerSubmitted() && $challenge->volunteer) {
                $ownerUser = $challenge->volunteer->user;
            } elseif ($challenge->company) {
                $ownerUser = $challenge->company->user;
            }

            if ($ownerUser) {
                // Use NotificationService for database notification
                $notificationService = app(NotificationService::class);
                $notificationService->send(
                    user: $ownerUser,
                    type: 'high_quality_comment',
                    title: 'High-Quality Comment Received',
                    message: "Received a high-quality comment (score {$score}/10) on your challenge: {$challenge->title}",
                    actionUrl: route('community.challenge', $challenge->id)
                );
            }

            // Award reputation points to the commenter
            $this->awardReputationPoints($score);

        } catch (\Exception $e) {
            Log::error('Failed to process high quality comment actions', [
                'comment_id' => $this->comment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Award reputation points based on comment score.
     */
    protected function awardReputationPoints(int $score): void
    {
        $volunteer = $this->comment->user->volunteer ?? null;

        if (!$volunteer) {
            return;
        }

        // Determine points based on score
        $points = match (true) {
            $score >= 9 => 10,  // Excellent comment: 10 points
            $score >= 7 => 5,   // Good comment: 5 points
            default => 0,
        };

        if ($points > 0) {
            // Update volunteer's reputation score
            $oldScore = $volunteer->reputation_score ?? 50;
            $newScore = $oldScore + $points;

            $volunteer->update(['reputation_score' => $newScore]);

            // Record in reputation history
            ReputationHistory::create([
                'volunteer_id' => $volunteer->id,
                'change_amount' => $points,
                'new_total' => $newScore,
                'reason' => "High-quality community comment (AI score: {$score}/10)",
                'related_type' => ChallengeComment::class,
                'related_id' => $this->comment->id,
                'created_at' => now(),
            ]);

            Log::info('Reputation points awarded', [
                'volunteer_id' => $volunteer->id,
                'comment_id' => $this->comment->id,
                'points' => $points,
                'new_score' => $newScore,
            ]);
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->logJobFailed($exception, ['comment_id' => $this->comment->id]);

        // Store failure reason
        $this->storeFailureReason($exception->getMessage(), [
            'comment_id' => $this->comment->id,
            'challenge_id' => $this->comment->challenge_id,
        ]);

        $this->comment->update(['ai_score_status' => 'failed']);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'comment:' . $this->comment->id,
            'challenge:' . $this->comment->challenge_id,
            'comment_analysis',
        ];
    }
}
