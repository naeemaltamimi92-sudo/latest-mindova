<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HiringRecord extends Model
{
    protected $fillable = [
        'hire_request_id', 'company_user_id', 'volunteer_id',
        'position_title', 'engagement_type', 'hired_at',
        'hiring_verification_id', 'skills_used', 'verified_certificate_ids',
        'professional_level', 'reputation_stars_at_hire', 'trust_score_at_hire',
        'status', 'ended_at',
    ];

    protected $casts = [
        'hired_at'                  => 'datetime',
        'ended_at'                  => 'datetime',
        'skills_used'               => 'array',
        'verified_certificate_ids'  => 'array',
        'reputation_stars_at_hire'  => 'integer',
        'trust_score_at_hire'       => 'decimal:1',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($record) {
            if (empty($record->hiring_verification_id)) {
                $year = date('Y');
                do {
                    $id = 'HIRE-' . $year . '-' . strtoupper(Str::random(8));
                } while (self::where('hiring_verification_id', $id)->exists());
                $record->hiring_verification_id = $id;
            }
            if (empty($record->hired_at)) {
                $record->hired_at = now();
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function hireRequest(): BelongsTo
    {
        return $this->belongsTo(HireRequest::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_user_id');
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isActive(): bool { return $this->status === 'active'; }

    public function engagementLabel(): string
    {
        return [
            'full_time'  => 'Full-time',
            'part_time'  => 'Part-time',
            'consulting' => 'Consulting',
            'project'    => 'Project',
        ][$this->engagement_type] ?? ucfirst($this->engagement_type);
    }

    public function getVerifyUrlAttribute(): string
    {
        return route('talent.verify-hire') . '?id=' . $this->hiring_verification_id;
    }
}
