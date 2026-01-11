<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'volunteer_id',
        'invitation_status',
        'ai_match_score',
        'ai_match_reasoning',
        'invited_at',
        'responded_at',
        'started_at',
        'submitted_at',
        'completed_at',
        'actual_hours',
    ];

    protected function casts(): array
    {
        return [
            'ai_match_score' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'invited_at' => 'datetime',
            'responded_at' => 'datetime',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the task for this assignment.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the volunteer for this assignment.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Get all work submissions for this assignment.
     */
    public function workSubmissions()
    {
        return $this->hasMany(WorkSubmission::class);
    }

    /**
     * Accessor for match_score (alias for ai_match_score).
     */
    public function getMatchScoreAttribute()
    {
        return $this->ai_match_score;
    }

    /**
     * Accessor for match_reasoning (alias for ai_match_reasoning).
     */
    public function getMatchReasoningAttribute()
    {
        return $this->ai_match_reasoning;
    }
}
