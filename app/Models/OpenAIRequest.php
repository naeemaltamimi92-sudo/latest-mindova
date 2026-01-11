<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenAIRequest extends Model
{
    use HasFactory;

    protected $table = 'openai_requests';

    public $timestamps = false;

    protected $fillable = [
        'request_type',
        'model',
        'prompt',
        'response',
        'tokens_prompt',
        'tokens_completion',
        'tokens_total',
        'cost_usd',
        'duration_ms',
        'status',
        'error_message',
        'related_type',
        'related_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'tokens_prompt' => 'integer',
            'tokens_completion' => 'integer',
            'tokens_total' => 'integer',
            'cost_usd' => 'decimal:6',
            'duration_ms' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the related model (polymorphic).
     */
    public function related()
    {
        return $this->morphTo();
    }
}
