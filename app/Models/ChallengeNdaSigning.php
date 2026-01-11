<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeNdaSigning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'nda_agreement_id',
        'signer_name',
        'signer_email',
        'ip_address',
        'user_agent',
        'signed_at',
        'signature_hash',
        'is_valid',
        'revoked_at',
        'revocation_reason',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'is_valid' => 'boolean',
        'revoked_at' => 'datetime',
    ];

    /**
     * Get the user who signed the NDA.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the challenge this NDA is for.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the NDA agreement that was signed.
     */
    public function ndaAgreement()
    {
        return $this->belongsTo(NdaAgreement::class);
    }

    /**
     * Check if the volunteer has signed the NDA for a specific challenge.
     */
    public static function hasSignedNda(int $userId, int $challengeId): bool
    {
        return static::where('user_id', $userId)
            ->where('challenge_id', $challengeId)
            ->where('is_valid', true)
            ->exists();
    }

    /**
     * Create a signature hash for verification.
     */
    public static function generateSignatureHash(int $userId, int $challengeId, string $timestamp): string
    {
        return hash('sha256', $userId . $challengeId . $timestamp . config('app.key'));
    }
}
