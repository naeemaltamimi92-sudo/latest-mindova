<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NdaAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'content',
        'version',
        'is_active',
        'effective_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_date' => 'datetime',
    ];

    /**
     * Get all signings for this NDA.
     */
    public function signings()
    {
        return $this->hasMany(ChallengeNdaSigning::class);
    }

    /**
     * Get the active general NDA.
     */
    public static function getActiveGeneralNda()
    {
        return self::where('type', 'general')
            ->where('is_active', true)
            ->latest('effective_date')
            ->first();
    }

    /**
     * Get the active challenge-specific NDA template.
     */
    public static function getActiveChallengeNda()
    {
        return self::where('type', 'challenge_specific')
            ->where('is_active', true)
            ->latest('effective_date')
            ->first();
    }
}
