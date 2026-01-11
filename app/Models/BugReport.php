<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'issue_type',
        'description',
        'current_page',
        'screenshot',
        'blocked_user',
        'user_agent',
        'status',
    ];

    protected $casts = [
        'blocked_user' => 'boolean',
    ];

    /**
     * Get the user who reported the bug.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted issue type for display.
     */
    public function getIssueTypeLabel(): string
    {
        return match($this->issue_type) {
            'bug' => 'Bug',
            'ui_ux_issue' => 'UI / UX Issue',
            'confusing_flow' => 'Confusing Flow',
            'something_didnt_work' => "Something Didn't Work",
            default => ucfirst(str_replace('_', ' ', $this->issue_type)),
        };
    }

    /**
     * Scope for critical bugs (blocked user).
     */
    public function scopeCritical($query)
    {
        return $query->where('blocked_user', true);
    }

    /**
     * Scope for new reports.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}
