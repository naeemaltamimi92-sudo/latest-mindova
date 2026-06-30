<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertChallengeAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id', 'volunteer_id', 'role', 'status',
        'selection_score', 'selection_reasoning',
        'final_approved', 'final_approved_at', 'final_approval_notes',
        'invited_at', 'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'selection_score'    => 'decimal:2',
            'final_approved'     => 'boolean',
            'final_approved_at'  => 'datetime',
            'invited_at'         => 'datetime',
            'responded_at'       => 'datetime',
        ];
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function isLeadExpert(): bool
    {
        return $this->role === 'lead_expert';
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['accepted', 'active']);
    }
}
