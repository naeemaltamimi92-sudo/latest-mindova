<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgencyPortal extends Model
{
    protected $fillable = [
        'user_id', 'agency_name', 'slug',
        'logo_path', 'primary_color', 'secondary_color',
        'custom_domain', 'is_active',
        'talent_slots', 'client_slots',
        'description', 'contact_email', 'website',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'talent_slots' => 'integer',
        'client_slots' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hireRequests(): HasMany
    {
        return $this->hasMany(HireRequest::class);
    }

    // -------------------------------------------------------------------------
    // Accessors / Helpers
    // -------------------------------------------------------------------------

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    public function getPortalUrlAttribute(): string
    {
        return $this->custom_domain
            ? 'https://' . $this->custom_domain
            : route('agency.portal', ['slug' => $this->slug]);
    }

    public function activeHires(): int
    {
        return $this->hireRequests()->where('status', 'converted')->count();
    }

    public function pendingHires(): int
    {
        return $this->hireRequests()->where('status', 'pending')->count();
    }
}
