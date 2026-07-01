<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'feedback_item_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feedbackItem()
    {
        return $this->belongsTo(FeedbackItem::class);
    }
}
