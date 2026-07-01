<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskAssignment>
 */
class TaskAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'volunteer_id' => Volunteer::factory(),
            'invitation_status' => 'invited',
            'ai_match_score' => 80,
            'invited_at' => now(),
        ];
    }
}
