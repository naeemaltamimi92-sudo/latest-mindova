<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InAppGuide extends Model
{
    protected $fillable = [
        'page_identifier',
        'page_title',
        'what_is_this',
        'what_to_do',
        'next_step',
        'ui_highlights',
        'video_url',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'what_to_do' => 'array',        // Store as JSON array
        'ui_highlights' => 'array',     // Store as JSON array
        'is_active' => 'boolean',
    ];

    /**
     * Get guide for a specific page.
     *
     * @param string $pageIdentifier
     * @return InAppGuide|null
     */
    public static function getForPage(string $pageIdentifier): ?self
    {
        return static::where('page_identifier', $pageIdentifier)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Scope to get only active guides.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
