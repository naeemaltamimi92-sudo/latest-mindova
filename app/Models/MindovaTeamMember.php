<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class MindovaTeamMember extends Model
{
    use SoftDeletes;

    protected $table = 'mindova_team_members';

    protected $fillable = [
        'user_id',
        'role_id',
        'email',
        'name',
        'temporary_password',
        'password_changed',
        'is_active',
        'invited_at',
        'activated_at',
        'last_login_at',
        'invited_by',
    ];

    protected $hidden = [
        'temporary_password',
    ];

    protected $casts = [
        'password_changed' => 'boolean',
        'is_active' => 'boolean',
        'invited_at' => 'datetime',
        'activated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the user associated with this team member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role of this team member.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(MindovaRole::class, 'role_id');
    }

    /**
     * Get the team member who invited this member.
     */
    public function invitedByMember(): BelongsTo
    {
        return $this->belongsTo(MindovaTeamMember::class, 'invited_by');
    }

    /**
     * Get team members invited by this member.
     */
    public function invitedMembers(): HasMany
    {
        return $this->hasMany(MindovaTeamMember::class, 'invited_by');
    }

    /**
     * Get audit logs for this team member.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(MindovaAuditLog::class, 'team_member_id');
    }

    /**
     * Check if member has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return $this->role->hasPermission($permissionSlug);
    }

    /**
     * Check if member has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return $this->role->hasAnyPermission($permissionSlugs);
    }

    /**
     * Check if this member can manage another member.
     */
    public function canManage(MindovaTeamMember $member): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Can't manage self
        if ($this->id === $member->id) {
            return false;
        }

        return $this->role->canManageRole($member->role);
    }

    /**
     * Check if member is Owner.
     */
    public function isOwner(): bool
    {
        return $this->role->isOwner();
    }

    /**
     * Check if member is Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    /**
     * Verify temporary password.
     */
    public function verifyTemporaryPassword(string $password): bool
    {
        if (!$this->temporary_password) {
            return false;
        }

        return Hash::check($password, $this->temporary_password);
    }

    /**
     * Mark password as changed and clear temporary password.
     */
    public function markPasswordChanged(): void
    {
        $this->update([
            'password_changed' => true,
            'temporary_password' => null,
            'activated_at' => $this->activated_at ?? now(),
        ]);
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Deactivate team member.
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Activate team member.
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Find team member by email.
     */
    public static function findByEmail(string $email): ?self
    {
        return static::where('email', $email)->first();
    }

    /**
     * Find team member by user ID.
     */
    public static function findByUserId(int $userId): ?self
    {
        return static::where('user_id', $userId)->first();
    }

    /**
     * Get the owner team member.
     */
    public static function getOwner(): ?self
    {
        $ownerRole = MindovaRole::findBySlug('owner');
        if (!$ownerRole) {
            return null;
        }

        return static::where('role_id', $ownerRole->id)->first();
    }

    /**
     * Check if an owner exists.
     */
    public static function ownerExists(): bool
    {
        return static::getOwner() !== null;
    }
}
