<?php

namespace Database\Factories;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workstream>
 */
class WorkstreamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'challenge_id' => Challenge::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => 'pending',
        ];
    }
}
