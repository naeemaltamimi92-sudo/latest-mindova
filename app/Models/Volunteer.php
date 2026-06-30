<?php

namespace App\Models;

use App\Services\ReputationService;
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
        'expert_available',
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
            'reputation_score' => 'integer',
            'trust_score' => 'decimal:1',
            'expert_available' => 'boolean',
            'total_tasks_completed' => 'integer',
            'total_hours_contributed' => 'decimal:2',
            'ai_analysis_confidence' => 'decimal:2',
            'skills_normalized' => 'boolean',
            'ai_analyzed_at' => 'datetime',
            'general_nda_signed' => 'boolean',
            'general_nda_signed_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Reputation & Tier helpers
    // -------------------------------------------------------------------------

    public function getStarsAttribute(): int
    {
        return (int) $this->reputation_score;
    }

    public function getTierAttribute(): array
    {
        return app(ReputationService::class)->getTier($this->stars);
    }

    public function getTierNameAttribute(): string
    {
        return $this->tier['name'];
    }

    public function getTierSlugAttribute(): string
    {
        return $this->tier['slug'];
    }

    public function getTierColorAttribute(): string
    {
        return $this->tier['color'];
    }

    public function getNextTierAttribute(): ?array
    {
        return app(ReputationService::class)->getNextTier($this->stars);
    }

    public function getPublishingCostAttribute(): int
    {
        return app(ReputationService::class)->getPublishingCost($this->stars);
    }

    public function isCertifiedExpert(): bool
    {
        return $this->stars >= 1200;
    }

    public function isExpertCandidate(): bool
    {
        return $this->stars >= 500;
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->hasMany(VolunteerSkill::class);
    }

    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public function ideaVotes()
    {
        return $this->hasMany(IdeaVote::class);
    }

    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot(['role', 'status', 'role_description', 'assigned_skills', 'contribution_score'])
            ->withTimestamps();
    }

    public function ledTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    public function reputationHistory()
    {
        return $this->hasMany(ReputationHistory::class)->latest('created_at');
    }

    public function expertAssignments()
    {
        return $this->hasMany(ExpertChallengeAssignment::class);
    }

    public function activeExpertAssignments()
    {
        return $this->expertAssignments()->whereIn('status', ['accepted', 'active']);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id', 'user_id');
    }

    // -------------------------------------------------------------------------
    // Status helpers
    // -------------------------------------------------------------------------

    public function hasCompletedAnalysis(): bool
    {
        return $this->ai_analysis_status === 'completed';
    }

    public function isAvailable(): bool
    {
        return $this->availability_hours_per_week > 0 && $this->hasCompletedAnalysis();
    }
}
