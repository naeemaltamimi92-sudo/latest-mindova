<?php

namespace Database\Factories;

use App\Models\Workstream;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        $workstream = Workstream::factory()->create();

        return [
            'workstream_id' => $workstream->id,
            'challenge_id' => $workstream->challenge_id,
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'required_skills' => ['PHP'],
            'required_experience_level' => 'Mid',
            'expected_output' => fake()->sentence(),
            'acceptance_criteria' => ['Meets requirements'],
            'estimated_hours' => 10,
            'complexity_score' => 5,
            'status' => 'pending',
        ];
    }
}
