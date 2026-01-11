<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class MindovaAuditLog extends Model
{
    protected $table = 'mindova_audit_logs';

    protected $fillable = [
        'team_member_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the team member who performed this action.
     */
    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(MindovaTeamMember::class, 'team_member_id');
    }

    /**
     * Log an action.
     */
    public static function log(
        string $action,
        ?MindovaTeamMember $teamMember = null,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        $request = request();

        return static::create([
            'team_member_id' => $teamMember?->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Log team member creation.
     */
    public static function logTeamMemberCreated(MindovaTeamMember $member, MindovaTeamMember $createdBy): self
    {
        return static::log(
            'team_member.created',
            $createdBy,
            'team_member',
            $member->id,
            null,
            [
                'email' => $member->email,
                'name' => $member->name,
                'role' => $member->role->name,
            ],
            "Team member '{$member->name}' was invited with role '{$member->role->name}'"
        );
    }

    /**
     * Log team member role change.
     */
    public static function logRoleChange(MindovaTeamMember $member, MindovaRole $oldRole, MindovaRole $newRole, MindovaTeamMember $changedBy): self
    {
        return static::log(
            'team_member.role_changed',
            $changedBy,
            'team_member',
            $member->id,
            ['role' => $oldRole->name, 'role_id' => $oldRole->id],
            ['role' => $newRole->name, 'role_id' => $newRole->id],
            "Role changed from '{$oldRole->name}' to '{$newRole->name}' for '{$member->name}'"
        );
    }

    /**
     * Log team member deactivation.
     */
    public static function logDeactivation(MindovaTeamMember $member, MindovaTeamMember $deactivatedBy): self
    {
        return static::log(
            'team_member.deactivated',
            $deactivatedBy,
            'team_member',
            $member->id,
            ['is_active' => true],
            ['is_active' => false],
            "Team member '{$member->name}' was deactivated"
        );
    }

    /**
     * Log team member activation.
     */
    public static function logActivation(MindovaTeamMember $member, MindovaTeamMember $activatedBy): self
    {
        return static::log(
            'team_member.activated',
            $activatedBy,
            'team_member',
            $member->id,
            ['is_active' => false],
            ['is_active' => true],
            "Team member '{$member->name}' was activated"
        );
    }

    /**
     * Log team member deletion.
     */
    public static function logDeletion(MindovaTeamMember $member, MindovaTeamMember $deletedBy): self
    {
        return static::log(
            'team_member.deleted',
            $deletedBy,
            'team_member',
            $member->id,
            [
                'email' => $member->email,
                'name' => $member->name,
                'role' => $member->role->name,
            ],
            null,
            "Team member '{$member->name}' was removed"
        );
    }

    /**
     * Log login.
     */
    public static function logLogin(MindovaTeamMember $member): self
    {
        return static::log(
            'team_member.login',
            $member,
            'team_member',
            $member->id,
            null,
            null,
            "Team member '{$member->name}' logged in"
        );
    }

    /**
     * Log logout.
     */
    public static function logLogout(MindovaTeamMember $member): self
    {
        return static::log(
            'team_member.logout',
            $member,
            'team_member',
            $member->id,
            null,
            null,
            "Team member '{$member->name}' logged out"
        );
    }

    /**
     * Log password change.
     */
    public static function logPasswordChange(MindovaTeamMember $member): self
    {
        return static::log(
            'team_member.password_changed',
            $member,
            'team_member',
            $member->id,
            null,
            null,
            "Team member '{$member->name}' changed their password"
        );
    }

    /**
     * Get logs for a specific entity.
     */
    public static function getForEntity(string $entityType, int $entityId)
    {
        return static::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent logs.
     */
    public static function getRecent(int $limit = 50)
    {
        return static::with('teamMember')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get action label for display.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'team_member.created' => 'Member Invited',
            'team_member.role_changed' => 'Role Changed',
            'team_member.deactivated' => 'Member Deactivated',
            'team_member.activated' => 'Member Activated',
            'team_member.deleted' => 'Member Removed',
            'team_member.login' => 'Login',
            'team_member.logout' => 'Logout',
            'team_member.password_changed' => 'Password Changed',
            default => ucwords(str_replace(['_', '.'], ' ', $this->action)),
        };
    }

    /**
     * Get action color for display.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'team_member.created' => 'green',
            'team_member.deleted', 'team_member.deactivated' => 'red',
            'team_member.role_changed' => 'yellow',
            'team_member.activated' => 'blue',
            'team_member.login', 'team_member.logout' => 'gray',
            'team_member.password_changed' => 'purple',
            default => 'gray',
        };
    }
}
