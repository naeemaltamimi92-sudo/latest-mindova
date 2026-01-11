<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_submission_id',
        'reviewer_id',
        'rating',
        'quality_score',
        'timeliness_score',
        'communication_score',
        'feedback',
        'decision',
        'revision_notes',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'quality_score' => 'integer',
            'timeliness_score' => 'integer',
            'communication_score' => 'integer',
        ];
    }

    /**
     * Get the work submission being reviewed.
     */
    public function workSubmission()
    {
        return $this->belongsTo(WorkSubmission::class);
    }

    /**
     * Get the reviewer (user).
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
