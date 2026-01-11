<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGuidanceProgress extends Model
{
    protected $table = 'user_guidance_progress';

    protected $fillable = [
        'user_id',
        'step_identifier',
        'page_identifier',
        'completed',
        'completed_at',
        'stage',
        'metadata',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user who owns this progress record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark step as completed for a user.
     *
     * @param int $userId
     * @param string $stepId
     * @param string|null $pageId
     * @return void
     */
    public static function markComplete(int $userId, string $stepId, ?string $pageId = null): void
    {
        static::updateOrCreate(
            [
                'user_id' => $userId,
                'step_identifier' => $stepId,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
                'page_identifier' => $pageId ?? request()->route()->getName() ?? 'unknown',
            ]
        );
    }

    /**
     * Check if step is completed for a user.
     *
     * @param int $userId
     * @param string $stepId
     * @return bool
     */
    public static function isCompleted(int $userId, string $stepId): bool
    {
        return static::where('user_id', $userId)
            ->where('step_identifier', $stepId)
            ->where('completed', true)
            ->exists();
    }

    /**
     * Get user's current stage.
     *
     * @param int $userId
     * @return string|null
     */
    public static function getCurrentStage(int $userId): ?string
    {
        return static::where('user_id', $userId)
            ->latest('completed_at')
            ->value('stage');
    }

    /**
     * Get all completed step identifiers for a user.
     *
     * @param int $userId
     * @return array
     */
    public static function getCompletedSteps(int $userId): array
    {
        return static::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('step_identifier')
            ->toArray();
    }

    /**
     * Get incomplete steps for user on a specific page.
     *
     * @param int $userId
     * @param string $pageId
     * @param string $role
     * @return array
     */
    public static function getIncompleteSteps(int $userId, string $pageId, string $role): array
    {
        $completedSteps = static::getCompletedSteps($userId);

        $pageSteps = config("user_guidance.{$role}.{$pageId}", []);

        $incompleteSteps = [];
        foreach ($pageSteps as $stepKey => $stepData) {
            $fullStepId = "{$pageId}.{$stepKey}";
            if (!in_array($fullStepId, $completedSteps)) {
                $incompleteSteps[$stepKey] = $stepData;
            }
        }

        return $incompleteSteps;
    }

    /**
     * Get total completed steps count for a user.
     *
     * @param int $userId
     * @return int
     */
    public static function getCompletedCount(int $userId): int
    {
        return static::where('user_id', $userId)
            ->where('completed', true)
            ->count();
    }

    /**
     * Reset all progress for a user (for testing).
     *
     * @param int $userId
     * @return void
     */
    public static function resetProgress(int $userId): void
    {
        static::where('user_id', $userId)->delete();
    }

    /**
     * Update user's progress stage.
     *
     * @param int $userId
     * @param string $stage
     * @return void
     */
    public static function updateStage(int $userId, string $stage): void
    {
        static::where('user_id', $userId)
            ->latest()
            ->first()
            ?->update(['stage' => $stage]);
    }
}
