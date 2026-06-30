<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id', 'challenge_id', 'company_id', 'expert_id',
        'certificate_number', 'certificate_type',
        'role', 'contribution_summary', 'contribution_types',
        'total_hours', 'time_breakdown',
        'company_confirmed', 'confirmed_at', 'company_logo_path',
        'expert_approved_at',
        'project_start_date', 'project_end_date',
        'industry', 'technologies',
        'verification_hash', 'show_company_name',
        'pdf_path', 'issued_at',
        'is_revoked', 'revoked_at', 'revocation_reason',
    ];

    protected $casts = [
        'contribution_types'  => 'array',
        'time_breakdown'      => 'array',
        'technologies'        => 'array',
        'total_hours'         => 'decimal:2',
        'company_confirmed'   => 'boolean',
        'show_company_name'   => 'boolean',
        'is_revoked'          => 'boolean',
        'confirmed_at'        => 'datetime',
        'expert_approved_at'  => 'datetime',
        'issued_at'           => 'datetime',
        'revoked_at'          => 'datetime',
        'project_start_date'  => 'date',
        'project_end_date'    => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cert) {
            if (empty($cert->certificate_number)) {
                $cert->certificate_number = self::generateCertificateNumber();
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function expertVolunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'expert_id');
    }

    // -------------------------------------------------------------------------
    // Business logic
    // -------------------------------------------------------------------------

    public function isValid(): bool
    {
        return !$this->is_revoked;
    }

    public function isExpertApproved(): bool
    {
        return $this->expert_approved_at !== null;
    }

    public function getProjectDurationAttribute(): ?string
    {
        if (!$this->project_start_date || !$this->project_end_date) {
            return null;
        }
        $days = $this->project_start_date->diffInDays($this->project_end_date);
        return $days >= 7 ? ceil($days / 7) . ' weeks' : $days . ' days';
    }

    public function getVerifyUrlAttribute(): string
    {
        return route('certificates.verify') . '?id=' . $this->certificate_number;
    }

    public function revoke(string $reason = null): bool
    {
        $this->is_revoked        = true;
        $this->revoked_at        = now();
        $this->revocation_reason = $reason;
        return $this->save();
    }

    // -------------------------------------------------------------------------
    // Static helpers
    // -------------------------------------------------------------------------

    public static function generateCertificateNumber(): string
    {
        $year = date('Y');
        do {
            $number = "MDVA-{$year}-" . strtoupper(Str::random(6));
        } while (self::where('certificate_number', $number)->exists());

        return $number;
    }

    public static function makeVerificationHash(Certificate $cert): string
    {
        return hash('sha256', implode('|', [
            $cert->certificate_number,
            $cert->user_id,
            $cert->challenge_id,
            $cert->issued_at?->toIso8601String() ?? now()->toIso8601String(),
            $cert->role,
        ]));
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getFormattedTypeAttribute(): string
    {
        return ucfirst($this->certificate_type) . ' Certificate';
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)        { return $query->where('is_revoked', false); }
    public function scopeRevoked($query)       { return $query->where('is_revoked', true); }
    public function scopeCompletion($query)    { return $query->where('certificate_type', 'completion'); }
    public function scopeParticipation($query) { return $query->where('certificate_type', 'participation'); }
}
