<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->sentence(6),
            'original_description' => fake()->paragraph(),
            'status' => 'active',
            'ai_analysis_status' => 'completed',
            'requires_nda' => false,
        ];
    }
}
