<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'volunteer_id',
        'role',
        'status',
        'role_description',
        'assigned_skills',
        'contribution_score',
        'invited_at',
        'accepted_at',
        'declined_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_skills' => 'array',
            'contribution_score' => 'decimal:2',
            'invited_at' => 'datetime',
            'accepted_at' => 'datetime',
            'declined_at' => 'datetime',
        ];
    }

    /**
     * Get the team this member belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the volunteer.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Check if member is team leader.
     */
    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }

    /**
     * Check if member has accepted invitation.
     */
    public function hasAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Accept team invitation.
     */
    public function accept(): void
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Update team status if all members accepted
        $this->checkTeamFormation();
    }

    /**
     * Decline team invitation.
     */
    public function decline(): void
    {
        $this->update([
            'status' => 'declined',
            'declined_at' => now(),
        ]);
    }

    /**
     * Check if team formation is complete.
     */
    protected function checkTeamFormation(): void
    {
        $team = $this->team;
        $totalMembers = $team->members()->count();
        $acceptedMembers = $team->acceptedMembers()->count();

        // If all members have accepted, mark team as active
        if ($acceptedMembers === $totalMembers && $team->status === 'forming') {
            $team->update([
                'status' => 'active',
                'formation_completed_at' => now(),
            ]);
        }
    }
}
