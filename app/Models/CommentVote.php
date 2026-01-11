<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'vote_type',
    ];

    /**
     * Get the comment that owns the vote.
     */
    public function comment()
    {
        return $this->belongsTo(ChallengeComment::class, 'comment_id');
    }

    /**
     * Get the user that owns the vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
