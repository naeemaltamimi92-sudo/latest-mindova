<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'company_id',
        'certificate_number',
        'certificate_type',
        'role',
        'contribution_summary',
        'contribution_types',
        'total_hours',
        'time_breakdown',
        'company_confirmed',
        'confirmed_at',
        'company_logo_path',
        'pdf_path',
        'issued_at',
        'is_revoked',
        'revoked_at',
        'revocation_reason',
    ];

    protected $casts = [
        'contribution_types' => 'array',
        'time_breakdown' => 'array',
        'total_hours' => 'decimal:2',
        'company_confirmed' => 'boolean',
        'is_revoked' => 'boolean',
        'confirmed_at' => 'datetime',
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    /**
     * Boot method to generate certificate number automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            if (empty($certificate->certificate_number)) {
                $certificate->certificate_number = self::generateCertificateNumber();
            }
        });
    }

    /**
     * Get the volunteer who received this certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the volunteer (alias for user).
     */
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the challenge this certificate is for.
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the company that issued this certificate.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * Generate unique certificate number.
     *
     * Format: MDVA-YYYY-XXXXXX
     */
    public static function generateCertificateNumber(): string
    {
        $year = date('Y');
        $randomPart = strtoupper(Str::random(6));

        $certificateNumber = "MDVA-{$year}-{$randomPart}";

        // Ensure uniqueness
        while (self::where('certificate_number', $certificateNumber)->exists()) {
            $randomPart = strtoupper(Str::random(6));
            $certificateNumber = "MDVA-{$year}-{$randomPart}";
        }

        return $certificateNumber;
    }

    /**
     * Check if certificate is valid (not revoked).
     */
    public function isValid(): bool
    {
        return !$this->is_revoked;
    }

    /**
     * Revoke the certificate.
     */
    public function revoke(string $reason = null): bool
    {
        $this->is_revoked = true;
        $this->revoked_at = now();
        $this->revocation_reason = $reason;

        return $this->save();
    }

    /**
     * Get formatted certificate type for display.
     */
    public function getFormattedTypeAttribute(): string
    {
        return ucfirst($this->certificate_type) . ' Certificate';
    }

    /**
     * Get PDF download URL.
     */
    public function getPdfUrlAttribute(): ?string
    {
        if ($this->pdf_path) {
            return asset('storage/' . $this->pdf_path);
        }

        return null;
    }

    /**
     * Scope for active (non-revoked) certificates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_revoked', false);
    }

    /**
     * Scope for revoked certificates.
     */
    public function scopeRevoked($query)
    {
        return $query->where('is_revoked', true);
    }

    /**
     * Scope for completion certificates.
     */
    public function scopeCompletion($query)
    {
        return $query->where('certificate_type', 'completion');
    }

    /**
     * Scope for participation certificates.
     */
    public function scopeParticipation($query)
    {
        return $query->where('certificate_type', 'participation');
    }
}
