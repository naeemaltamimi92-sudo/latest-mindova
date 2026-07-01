<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HireRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'company_user_id', 'volunteer_id', 'agency_portal_id',
        'type', 'status', 'position_title', 'message',
        'salary_range', 'proposed_start_date', 'is_private_challenge',
        'responded_at',
    ];

    protected $casts = [
        'proposed_start_date' => 'date',
        'responded_at'        => 'datetime',
        'is_private_challenge' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_user_id');
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    public function agencyPortal(): BelongsTo
    {
        return $this->belongsTo(AgencyPortal::class);
    }

    public function hiringRecord(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(HiringRecord::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isAccepted(): bool  { return $this->status === 'accepted'; }
    public function isDeclined(): bool  { return $this->status === 'declined'; }

    public function typeLabel(): string
    {
        return [
            'full_time'  => 'Full-time Employment',
            'part_time'  => 'Part-time Employment',
            'consulting' => 'Consulting Contract',
            'project'    => 'Project Engagement',
            'invitation' => 'Challenge Invitation',
        ][$this->type] ?? ucfirst($this->type);
    }
}
