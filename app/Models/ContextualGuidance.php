<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContextualGuidance extends Model
{
    protected $fillable = [
        'page_identifier',
        'page_title',
        'guidance_text',
        'icon',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active guidance for a specific page.
     *
     * @param string $pageIdentifier Route name or page identifier
     * @return ContextualGuidance|null
     */
    public static function getForPage(string $pageIdentifier): ?self
    {
        return static::where('page_identifier', $pageIdentifier)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Scope to get only active guidances.
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
