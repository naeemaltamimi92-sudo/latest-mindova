<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIChallengeInsight extends Model
{
    protected $fillable = [
        'challenge_id',
        'task_id',
        'extracted_summary',
        'key_details',
        'relevant_attachments',
        'insight_type',
        'relevance_score',
    ];

    protected $casts = [
        'key_details' => 'array',
        'relevant_attachments' => 'array',
        'relevance_score' => 'integer',
    ];

    /**
     * Insight types
     */
    public const TYPE_GENERAL = 'general';
    public const TYPE_TASK_SPECIFIC = 'task_specific';
    public const TYPE_TECHNICAL = 'technical';
    public const TYPE_CONSTRAINT = 'constraint';

    /**
     * Get the challenge this insight belongs to
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the task this insight is specific to (optional)
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Scope for general insights
     */
    public function scopeGeneral($query)
    {
        return $query->where('insight_type', self::TYPE_GENERAL);
    }

    /**
     * Scope for task-specific insights
     */
    public function scopeTaskSpecific($query, $taskId)
    {
        return $query->where('insight_type', self::TYPE_TASK_SPECIFIC)
                     ->where('task_id', $taskId);
    }

    /**
     * Scope for technical insights
     */
    public function scopeTechnical($query)
    {
        return $query->where('insight_type', self::TYPE_TECHNICAL);
    }

    /**
     * Scope for constraint insights
     */
    public function scopeConstraints($query)
    {
        return $query->where('insight_type', self::TYPE_CONSTRAINT);
    }

    /**
     * Scope for high relevance insights (score >= 70)
     */
    public function scopeHighRelevance($query)
    {
        return $query->where('relevance_score', '>=', 70);
    }

    /**
     * Get insights for a specific task
     */
    public static function getInsightsForTask(int $taskId)
    {
        return self::where('task_id', $taskId)
                   ->orWhere(function ($query) use ($taskId) {
                       $query->whereNull('task_id')
                             ->where('insight_type', self::TYPE_GENERAL);
                   })
                   ->orderByDesc('relevance_score')
                   ->get();
    }
}
