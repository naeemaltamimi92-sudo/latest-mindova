<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'workstream_id',
        'challenge_id',
        'title',
        'description',
        'required_skills',
        'required_experience_level',
        'expected_output',
        'acceptance_criteria',
        'estimated_hours',
        'complexity_score',
        'priority',
        'status',
        'order',
        'dependencies',
        'validation_status',
        'quality_check_passed',
    ];

    protected function casts(): array
    {
        return [
            'required_skills' => 'array',
            'acceptance_criteria' => 'array',
            'dependencies' => 'array',
            'estimated_hours' => 'decimal:2',
            'complexity_score' => 'integer',
            'order' => 'integer',
            'quality_check_passed' => 'boolean',
        ];
    }

    /**
     * Get the workstream that this task belongs to.
     */
    public function workstream()
    {
        return $this->belongsTo(Workstream::class);
    }

    /**
     * Get the challenge that this task belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get all assignments for this task.
     */
    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Alias for taskAssignments relationship.
     */
    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Get all work submissions for this task.
     */
    public function submissions()
    {
        return $this->hasMany(WorkSubmission::class);
    }
}
