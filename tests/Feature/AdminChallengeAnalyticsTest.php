<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test: production runs on SQLite (not MySQL, despite local
 * dev defaulting to it), and these endpoints used to crash there with
 * "no such column: DAY" because TIMESTAMPDIFF()/DATE_FORMAT() are
 * MySQL-only SQL. The test suite's sqlite :memory: config makes this
 * exact class of bug catchable before it reaches production.
 */
class AdminChallengeAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['user_type' => 'admin']);
    }

    public function test_admin_challenges_index_loads_without_a_sql_error(): void
    {
        $admin = $this->makeAdmin();

        // A completed challenge exercises the completion-time average query.
        Challenge::factory()->create([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.challenges.index'));

        $response->assertOk();
    }

    public function test_admin_challenges_analytics_loads_without_a_sql_error(): void
    {
        $admin = $this->makeAdmin();

        Challenge::factory()->create([
            'status' => 'completed',
            'completed_at' => now(),
            'ai_analyzed_at' => now(),
            'field' => 'Technology',
            'score' => 5,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.challenges.analytics'));

        $response->assertOk();
    }
}
