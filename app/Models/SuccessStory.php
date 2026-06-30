<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuccessStory extends Model
{
    protected $fillable = [
        'challenge_id', 'executive_summary', 'initial_problem', 'ai_analysis',
        'team_members', 'solution_timeline', 'final_implementation',
        'results_achieved', 'business_impact', 'company_testimonial',
        'lessons_learned', 'total_verified_hours', 'reputation_points_awarded',
        'is_demo', 'is_published',
    ];

    protected $casts = [
        'team_members'              => 'array',
        'is_demo'                   => 'boolean',
        'is_published'              => 'boolean',
        'total_verified_hours'      => 'integer',
        'reputation_points_awarded' => 'integer',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
