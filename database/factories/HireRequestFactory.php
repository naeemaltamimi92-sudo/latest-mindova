<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HireRequest>
 */
class HireRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_user_id' => User::factory()->state(['user_type' => 'company']),
            'volunteer_id' => Volunteer::factory(),
            'type' => 'project',
            'status' => 'pending',
            'position_title' => fake()->jobTitle(),
            'message' => fake()->paragraph(),
        ];
    }
}
