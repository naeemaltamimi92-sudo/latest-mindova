<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MindovaPermission extends Model
{
    protected $table = 'mindova_permissions';

    protected $fillable = [
        'name',
        'slug',
        'group',
        'description',
    ];

    /**
     * Get roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            MindovaRole::class,
            'mindova_role_permissions',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * Get permission by slug.
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get permissions grouped by their group.
     */
    public static function getGrouped(): array
    {
        return static::all()
            ->groupBy('group')
            ->map(fn($permissions) => $permissions->keyBy('slug'))
            ->toArray();
    }

    /**
     * Get all permission groups.
     */
    public static function getGroups(): array
    {
        return static::distinct('group')->pluck('group')->toArray();
    }
}
