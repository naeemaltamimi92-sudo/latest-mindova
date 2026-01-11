<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'volunteer_id',
        'vote_type',
    ];

    /**
     * Get the idea this vote belongs to.
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * Get the volunteer who cast this vote.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }
}
