<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'linkedin_id',
        'linkedin_profile_url',
        'is_active',
        'whatsapp_opt_in',
        'whatsapp_number',
        'whatsapp_opted_in_at',
        'whatsapp_opted_out_at',
        'locale',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'whatsapp_opt_in' => 'boolean',
            'whatsapp_opted_in_at' => 'datetime',
            'whatsapp_opted_out_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'password_changed_at' => 'datetime',
        ];
    }

    /**
     * Get the volunteer profile if user is a volunteer.
     */
    public function volunteer()
    {
        return $this->hasOne(Volunteer::class);
    }

    /**
     * Get the company profile if user is a company.
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get all certificates for this user.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'user_id');
    }

    /**
     * Get all notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get all WhatsApp notifications for this user.
     */
    public function whatsappNotifications()
    {
        return $this->hasMany(WhatsAppNotification::class);
    }

    /**
     * Check if user is a volunteer.
     */
    public function isVolunteer(): bool
    {
        return $this->user_type === 'volunteer';
    }

    /**
     * Check if user is a company.
     */
    public function isCompany(): bool
    {
        return $this->user_type === 'company';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if user has opted in for WhatsApp notifications.
     */
    public function hasWhatsAppEnabled(): bool
    {
        return $this->whatsapp_opt_in && !empty($this->whatsapp_number);
    }

    /**
     * Enable WhatsApp notifications.
     */
    public function enableWhatsApp(string $phoneNumber): void
    {
        $this->update([
            'whatsapp_opt_in' => true,
            'whatsapp_number' => $phoneNumber,
            'whatsapp_opted_in_at' => now(),
            'whatsapp_opted_out_at' => null,
        ]);
    }

    /**
     * Disable WhatsApp notifications.
     */
    public function disableWhatsApp(): void
    {
        $this->update([
            'whatsapp_opt_in' => false,
            'whatsapp_opted_out_at' => now(),
        ]);
    }

    /**
     * Check if two-factor authentication is enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Get the number of remaining recovery codes.
     */
    public function getRecoveryCodesCount(): int
    {
        if (!$this->two_factor_recovery_codes) {
            return 0;
        }

        try {
            $codes = json_decode(decrypt($this->two_factor_recovery_codes), true);
            return is_array($codes) ? count($codes) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Check if user has a password set (for OAuth-only users).
     */
    public function hasPassword(): bool
    {
        // Use getAttributes to get raw value, bypassing the 'hashed' cast
        $rawPassword = $this->getAttributes()['password'] ?? null;
        return !empty($rawPassword) && $rawPassword !== null;
    }
}
