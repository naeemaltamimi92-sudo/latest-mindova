<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    protected $table = 'credit_transactions';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'amount', 'balance_after', 'type',
        'description', 'related_type', 'related_id', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'integer',
            'balance_after' => 'integer',
            'created_at'   => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }
}
