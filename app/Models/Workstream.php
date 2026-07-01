<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstream extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'title',
        'description',
        'order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    /**
     * Get the challenge that this workstream belongs to.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get all tasks for this workstream.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Percentage of this workstream's tasks marked completed. Uses the
     * already-loaded `tasks` relation rather than a query, so eager-load
     * it first to avoid N+1 when iterating multiple workstreams.
     */
    public function getProgressPercentageAttribute(): int
    {
        $total = $this->tasks->count();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->tasks->where('status', 'completed')->count() / $total) * 100);
    }
}
