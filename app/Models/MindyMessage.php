<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MindyMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role',
        'content',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the user this message belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
