<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Volunteer>
 */
class VolunteerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['user_type' => 'volunteer']),
            'field' => 'Technology',
            'experience_level' => 'Mid',
            'general_nda_signed' => false,
            'reputation_score' => 50,
            'availability_hours_per_week' => 20,
        ];
    }
}
