<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'commercial_register',
        'industry',
        'website',
        'description',
        'logo_path',
        'total_challenges_submitted',
    ];

    protected function casts(): array
    {
        return [
            'total_challenges_submitted' => 'integer',
        ];
    }

    /**
     * Get the user that owns the company profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all challenges submitted by this company.
     */
    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}
