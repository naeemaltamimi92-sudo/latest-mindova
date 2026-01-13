<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_assignment_id',
        'task_id',
        'volunteer_id',
        'description',
        'deliverable_url',
        'attachments',
        'hours_worked',
        'status',
        'ai_quality_score',
        'ai_analysis_status',
        'ai_feedback',
        'solves_task',
        'is_spam',
        'relevance_score',
        'ai_analyzed_at',
        'submitted_at',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'ai_quality_score' => 'integer',
            'solves_task' => 'boolean',
            'is_spam' => 'boolean',
            'relevance_score' => 'decimal:2',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'ai_analyzed_at' => 'datetime',
        ];
    }

    /**
     * Get the task assignment this submission belongs to.
     */
    public function taskAssignment()
    {
        return $this->belongsTo(TaskAssignment::class);
    }

    /**
     * Get the task this submission is for.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the volunteer who submitted this.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Get all reviews for this submission.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
