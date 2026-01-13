<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'volunteer_id',
        'content',
        'ai_quality_score',
        'ai_relevance_score',
        'ai_feasibility_score',
        'ai_feedback',
        'ai_score',
        'final_score',
        'community_votes_up',
        'community_votes_down',
        'total_score',
        'status',
        'is_spam',
        'spam_reason',
    ];

    protected function casts(): array
    {
        return [
            'ai_quality_score' => 'decimal:2',
            'ai_relevance_score' => 'decimal:2',
            'ai_feasibility_score' => 'decimal:2',
            'ai_score' => 'decimal:2',
            'final_score' => 'decimal:2',
            'community_votes_up' => 'integer',
            'community_votes_down' => 'integer',
            'total_score' => 'decimal:2',
            'ai_feedback' => 'array',
            'is_spam' => 'boolean',
        ];
    }

    /**
     * Get the challenge this idea belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the volunteer who submitted this idea.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Get all votes for this idea.
     */
    public function votes()
    {
        return $this->hasMany(IdeaVote::class);
    }

    /**
     * Scope to filter out spam ideas.
     */
    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }
}
