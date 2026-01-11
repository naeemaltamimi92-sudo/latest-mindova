<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id',
        'skill_name',
        'category',
        'proficiency_level',
        'years_of_experience',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'years_of_experience' => 'decimal:1',
        ];
    }

    /**
     * Get the volunteer that owns this skill.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }
}
