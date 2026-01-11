<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'user_id',
        'content',
        'ai_score',
        'ai_score_status',
        'ai_analysis',
        'ai_scored_at',
    ];

    protected function casts(): array
    {
        return [
            'ai_score' => 'integer',
            'ai_scored_at' => 'datetime',
        ];
    }

    /**
     * Get the challenge this comment belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the user who created this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all votes for this comment.
     */
    public function votes()
    {
        return $this->hasMany(CommentVote::class, 'comment_id');
    }

    /**
     * Get the vote score (upvotes - downvotes).
     */
    public function getVoteScoreAttribute(): int
    {
        $upvotes = $this->votes()->where('vote_type', 'upvote')->count();
        $downvotes = $this->votes()->where('vote_type', 'downvote')->count();
        return $upvotes - $downvotes;
    }

    /**
     * Get the user's vote type for this comment.
     */
    public function getUserVote($userId): ?string
    {
        $vote = $this->votes()->where('user_id', $userId)->first();
        return $vote ? $vote->vote_type : null;
    }

    /**
     * Check if AI scoring is complete.
     */
    public function hasAIScore(): bool
    {
        return $this->ai_score_status === 'completed' && $this->ai_score !== null;
    }

    /**
     * Check if AI scoring is pending.
     */
    public function isAIScoringPending(): bool
    {
        return $this->ai_score_status === 'pending';
    }
}
