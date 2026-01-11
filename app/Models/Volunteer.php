<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'cv_file_path',
        'availability_hours_per_week',
        'experience_level',
        'bio',
        'field',
        'education',
        'work_experience',
        'professional_domains',
        'years_of_experience',
        'reputation_score',
        'total_tasks_completed',
        'total_hours_contributed',
        'ai_analysis_status',
        'ai_analysis_confidence',
        'validation_status',
        'validation_errors',
        'skills_normalized',
        'ai_analyzed_at',
        'general_nda_signed',
        'general_nda_signed_at',
        'general_nda_version',
    ];

    protected function casts(): array
    {
        return [
            'education' => 'array',
            'work_experience' => 'array',
            'professional_domains' => 'array',
            'validation_errors' => 'array',
            'years_of_experience' => 'decimal:1',
            'reputation_score' => 'decimal:2',
            'total_tasks_completed' => 'integer',
            'total_hours_contributed' => 'decimal:2',
            'ai_analysis_confidence' => 'decimal:2',
            'skills_normalized' => 'boolean',
            'ai_analyzed_at' => 'datetime',
            'general_nda_signed' => 'boolean',
            'general_nda_signed_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the volunteer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all skills for this volunteer.
     */
    public function skills()
    {
        return $this->hasMany(VolunteerSkill::class);
    }

    /**
     * Get all task assignments for this volunteer.
     */
    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Get all ideas submitted by this volunteer.
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    /**
     * Get all idea votes by this volunteer.
     */
    public function ideaVotes()
    {
        return $this->hasMany(IdeaVote::class);
    }

    /**
     * Get all team memberships for this volunteer.
     */
    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Get all teams this volunteer belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot(['role', 'status', 'role_description', 'assigned_skills', 'contribution_score'])
            ->withTimestamps();
    }

    /**
     * Get teams this volunteer leads.
     */
    public function ledTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    /**
     * Get reputation history for this volunteer.
     */
    public function reputationHistory()
    {
        return $this->hasMany(ReputationHistory::class);
    }

    /**
     * Get all certificates for this volunteer.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id', 'user_id');
    }

    /**
     * Check if CV analysis is complete.
     */
    public function hasCompletedAnalysis(): bool
    {
        return $this->ai_analysis_status === 'completed';
    }

    /**
     * Check if volunteer is available for tasks.
     */
    public function isAvailable(): bool
    {
        return $this->availability_hours_per_week > 0 && $this->hasCompletedAnalysis();
    }
}
