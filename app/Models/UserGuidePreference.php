<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGuidePreference extends Model
{
    protected $fillable = [
        'user_id',
        'page_identifier',
        'dismissed',
        'dismissed_at',
    ];

    protected $casts = [
        'dismissed' => 'boolean',
        'dismissed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user has dismissed guide for a page.
     *
     * @param int $userId
     * @param string $pageIdentifier
     * @return bool
     */
    public static function isDismissed(int $userId, string $pageIdentifier): bool
    {
        return static::where('user_id', $userId)
            ->where('page_identifier', $pageIdentifier)
            ->where('dismissed', true)
            ->exists();
    }

    /**
     * Mark guide as dismissed for user.
     *
     * @param int $userId
     * @param string $pageIdentifier
     * @return void
     */
    public static function dismiss(int $userId, string $pageIdentifier): void
    {
        static::updateOrCreate(
            [
                'user_id' => $userId,
                'page_identifier' => $pageIdentifier,
            ],
            [
                'dismissed' => true,
                'dismissed_at' => now(),
            ]
        );
    }

    /**
     * Reset dismissal (show guide again).
     *
     * @param int $userId
     * @param string $pageIdentifier
     * @return void
     */
    public static function reset(int $userId, string $pageIdentifier): void
    {
        static::where('user_id', $userId)
            ->where('page_identifier', $pageIdentifier)
            ->update([
                'dismissed' => false,
                'dismissed_at' => null,
            ]);
    }
}
