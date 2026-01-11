<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'volunteer_id',
        'source_type',
        'title',
        'original_description',
        'refined_brief',
        'complexity_level',
        'score',
        'field',
        'challenge_type',
        'status',
        'deadline',
        'ai_analysis_status',
        'rejection_reason',
        'ai_analyzed_at',
        'aggregated_solutions',
        'average_solution_quality',
        'completed_at',
        // Admin management fields
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'is_featured',
        'is_pinned',
        'priority',
        'visibility',
        'view_count',
        'application_count',
        'last_activity_at',
        'estimated_budget',
        'actual_budget',
        'currency',
        'tags',
        'external_reference',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'complexity_level' => 'integer',
            'score' => 'integer',
            'deadline' => 'date',
            'ai_analyzed_at' => 'datetime',
            'aggregated_solutions' => 'array',
            'average_solution_quality' => 'decimal:2',
            'completed_at' => 'datetime',
            // Admin management casts
            'reviewed_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'view_count' => 'integer',
            'application_count' => 'integer',
            'last_activity_at' => 'datetime',
            'estimated_budget' => 'decimal:2',
            'actual_budget' => 'decimal:2',
            'tags' => 'array',
        ];
    }

    /**
     * Get the company that submitted this challenge.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the volunteer that submitted this challenge (for volunteer-sourced challenges).
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Check if this challenge was submitted by a volunteer.
     */
    public function isVolunteerSubmitted(): bool
    {
        return $this->source_type === 'volunteer';
    }

    /**
     * Check if this challenge was submitted by a company.
     */
    public function isCompanySubmitted(): bool
    {
        return $this->source_type === 'company';
    }

    /**
     * Get the owner (either company or volunteer) of this challenge.
     */
    public function getOwnerAttribute()
    {
        return $this->isVolunteerSubmitted() ? $this->volunteer : $this->company;
    }

    /**
     * Get the owner's user account.
     */
    public function getOwnerUserAttribute()
    {
        $owner = $this->owner;
        return $owner ? $owner->user : null;
    }

    /**
     * Get all analyses for this challenge.
     */
    public function challengeAnalyses()
    {
        return $this->hasMany(ChallengeAnalysis::class);
    }

    /**
     * Get all workstreams for this challenge.
     */
    public function workstreams()
    {
        return $this->hasMany(Workstream::class);
    }

    /**
     * Get all tasks for this challenge.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all ideas for this challenge.
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    /**
     * Get all teams for this challenge.
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Check if challenge analysis is complete.
     */
    public function hasCompletedAnalysis(): bool
    {
        return $this->ai_analysis_status === 'completed';
    }

    /**
     * Check if challenge is a community discussion.
     */
    public function isCommunityDiscussion(): bool
    {
        return $this->challenge_type === 'community_discussion';
    }

    /**
     * Check if challenge requires team execution.
     */
    public function isTeamExecution(): bool
    {
        return $this->challenge_type === 'team_execution';
    }

    /**
     * Get all comments for this challenge.
     */
    public function comments()
    {
        return $this->hasMany(ChallengeComment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all attachments for this challenge.
     */
    public function attachments()
    {
        return $this->hasMany(ChallengeAttachment::class);
    }

    /**
     * Get all AI-generated insights for this challenge.
     */
    public function aiInsights()
    {
        return $this->hasMany(AIChallengeInsight::class);
    }

    /**
     * Get all certificates issued for this challenge.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Check if challenge can issue certificates.
     */
    public function canIssueCertificates(): bool
    {
        // Challenge must be completed or delivered to issue certificates
        return in_array($this->status, ['completed', 'delivered']);
    }

    /**
     * Get general (non-task-specific) AI insights.
     */
    public function generalInsights()
    {
        return $this->aiInsights()->whereNull('task_id');
    }

    /**
     * Check if challenge qualifies for community discussion (score 1-2).
     */
    public function qualifiesForCommunity(): bool
    {
        return $this->score !== null && $this->score >= 1 && $this->score <= 2;
    }

    /**
     * Check if challenge should proceed to task analysis (score 3-10).
     */
    public function shouldProceedToTaskAnalysis(): bool
    {
        return $this->score !== null && $this->score >= 3 && $this->score <= 10;
    }

    /**
     * Get progress percentage based on completed tasks.
     */
    public function getProgressPercentageAttribute(): float
    {
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->tasks()->where('status', 'completed')->count();

        return round(($completedTasks / $totalTasks) * 100, 1);
    }

    /**
     * Get total estimated hours for all tasks.
     */
    public function getTotalEstimatedHoursAttribute(): int
    {
        return $this->tasks()->sum('estimated_hours') ?? 0;
    }

    /**
     * Get estimated remaining hours based on incomplete tasks.
     */
    public function getEstimatedRemainingHoursAttribute(): int
    {
        return $this->tasks()
            ->whereNotIn('status', ['completed'])
            ->sum('estimated_hours') ?? 0;
    }

    /**
     * Get time-based progress percentage.
     * Considers both task completion and time elapsed.
     */
    public function getTimeBasedProgressAttribute(): float
    {
        $taskProgress = $this->progress_percentage;

        if (!$this->deadline || !$this->created_at) {
            return $taskProgress;
        }

        $totalDuration = $this->created_at->diffInDays($this->deadline);
        $elapsed = $this->created_at->diffInDays(now());

        if ($totalDuration <= 0) {
            return $taskProgress;
        }

        $timeProgress = min(($elapsed / $totalDuration) * 100, 100);

        // Weighted average: 70% task completion, 30% time elapsed
        return round(($taskProgress * 0.7) + ($timeProgress * 0.3), 1);
    }

    /**
     * Get performance score based on quality and completion rate.
     */
    public function getPerformanceScoreAttribute(): float
    {
        $completedTasks = $this->tasks()->where('status', 'completed')->count();
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            return 0;
        }

        $completionRate = ($completedTasks / $totalTasks) * 100;

        // Get average work submission quality through assignments
        $avgQuality = $this->tasks()
            ->whereHas('assignments.workSubmissions', function($q) {
                $q->where('status', 'approved');
            })
            ->with(['assignments.workSubmissions' => function($q) {
                $q->where('status', 'approved');
            }])
            ->get()
            ->flatMap(function($task) {
                return $task->assignments->flatMap->workSubmissions;
            })
            ->where('status', 'approved')
            ->avg('ai_quality_score') ?? 0;

        // Weighted: 60% completion rate, 40% quality
        return round(($completionRate * 0.6) + ($avgQuality * 0.4), 1);
    }

    /**
     * Get challenge health status based on progress vs time.
     */
    public function getHealthStatusAttribute(): string
    {
        if (!$this->deadline) {
            return 'on_track';
        }

        $taskProgress = $this->progress_percentage;
        $timeProgress = $this->time_based_progress;

        // If task progress is ahead or equal to time progress, we're on track
        if ($taskProgress >= $timeProgress - 10) {
            return 'on_track';
        } elseif ($taskProgress >= $timeProgress - 25) {
            return 'at_risk';
        } else {
            return 'behind';
        }
    }
}
