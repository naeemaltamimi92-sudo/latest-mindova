<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReputationHistory extends Model
{
    use HasFactory;

    protected $table = 'reputation_history';
    public $timestamps = false;

    protected $fillable = [
        'volunteer_id',
        'change_amount',
        'new_total',
        'reason',
        'related_type',
        'related_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'change_amount' => 'decimal:2',
            'new_total' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the volunteer this history belongs to.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Get the related model (polymorphic).
     */
    public function related()
    {
        return $this->morphTo();
    }
}
