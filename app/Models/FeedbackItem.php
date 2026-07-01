<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackItem extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPES = ['problem', 'idea', 'feature_request'];
    public const STATUSES = ['open', 'under_review', 'planned', 'in_progress', 'done', 'declined'];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'category',
        'status',
        'votes_count',
        'duplicate_of_id',
    ];

    protected function casts(): array
    {
        return [
            'votes_count' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(FeedbackVote::class);
    }

    public function comments()
    {
        return $this->hasMany(FeedbackComment::class)->latest('created_at');
    }

    public function duplicateOf()
    {
        return $this->belongsTo(self::class, 'duplicate_of_id');
    }

    public function hasVoteFrom(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->votes->contains('user_id', $user->id);
    }
}
