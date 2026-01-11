<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MindovaRole extends Model
{
    protected $table = 'mindova_roles';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
        'is_system',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_system' => 'boolean',
    ];

    /**
     * Get team members with this role.
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(MindovaTeamMember::class, 'role_id');
    }

    /**
     * Get permissions for this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            MindovaPermission::class,
            'mindova_role_permissions',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Owner has all permissions
        if ($this->slug === 'owner') {
            return true;
        }

        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Check if role has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        if ($this->slug === 'owner') {
            return true;
        }

        return $this->permissions()->whereIn('slug', $permissionSlugs)->exists();
    }

    /**
     * Check if this role can manage another role.
     */
    public function canManageRole(MindovaRole $role): bool
    {
        return $this->level > $role->level;
    }

    /**
     * Get role by slug.
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Check if role is Owner.
     */
    public function isOwner(): bool
    {
        return $this->slug === 'owner';
    }

    /**
     * Check if role is Admin.
     */
    public function isAdmin(): bool
    {
        return $this->slug === 'admin';
    }
}
