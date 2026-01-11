<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'name',
        'description',
        'status',
        'leader_id',
        'objectives',
        'skills_coverage',
        'team_match_score',
        'estimated_total_hours',
        'formation_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'skills_coverage' => 'array',
            'team_match_score' => 'decimal:2',
            'estimated_total_hours' => 'integer',
            'formation_completed_at' => 'datetime',
        ];
    }

    /**
     * Get the challenge this team belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the team leader.
     */
    public function leader()
    {
        return $this->belongsTo(Volunteer::class, 'leader_id');
    }

    /**
     * Get all team members.
     */
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Get all accepted team members.
     */
    public function acceptedMembers()
    {
        return $this->hasMany(TeamMember::class)->where('status', 'accepted');
    }

    /**
     * Get volunteers through team members.
     */
    public function volunteers()
    {
        return $this->belongsToMany(Volunteer::class, 'team_members')
            ->withPivot(['role', 'status', 'role_description', 'assigned_skills', 'contribution_score', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    /**
     * Get only accepted volunteers.
     */
    public function acceptedVolunteers()
    {
        return $this->belongsToMany(Volunteer::class, 'team_members')
            ->wherePivot('status', 'accepted')
            ->withPivot(['role', 'role_description', 'assigned_skills', 'contribution_score'])
            ->withTimestamps();
    }

    /**
     * Check if team is fully formed.
     */
    public function isFullyFormed(): bool
    {
        return $this->status === 'active' && $this->formation_completed_at !== null;
    }

    /**
     * Get team size (accepted members only).
     */
    public function getTeamSizeAttribute(): int
    {
        return $this->acceptedMembers()->count();
    }

    /**
     * Get team acceptance rate.
     */
    public function getAcceptanceRateAttribute(): float
    {
        $total = $this->members()->count();
        if ($total === 0) return 0;

        $accepted = $this->acceptedMembers()->count();
        return round(($accepted / $total) * 100, 2);
    }
}
