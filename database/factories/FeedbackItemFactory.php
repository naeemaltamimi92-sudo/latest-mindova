<?php

namespace Database\Factories;

use App\Models\FeedbackItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackItemFactory extends Factory
{
    protected $model = FeedbackItem::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(4),
            'type' => fake()->randomElement(FeedbackItem::TYPES),
            'category' => null,
            'status' => 'open',
            'votes_count' => 0,
        ];
    }
}
