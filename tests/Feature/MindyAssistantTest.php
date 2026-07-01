<?php

namespace Tests\Feature;

use App\Models\MindyMessage;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use App\Models\Volunteer;
use App\Services\AI\MindyAssistantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MindyAssistantTest extends TestCase
{
    use RefreshDatabase;

    private function makeVolunteer(): array
    {
        $user = User::factory()->state(['user_type' => 'volunteer'])->create();
        $volunteer = Volunteer::factory()->create(['user_id' => $user->id]);

        return [$user, $volunteer];
    }

    public function test_volunteer_can_chat_with_mindy_and_messages_are_persisted(): void
    {
        [$user] = $this->makeVolunteer();

        $this->partialMock(MindyAssistantService::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRequestType')->andReturn('mindy_chat');
            $mock->shouldReceive('reply')->once()->andReturn(
                "I'm Mindy, Mindova's AI guide. Since your profile isn't complete yet, let's start there."
            );
        });

        $response = $this->actingAs($user)->postJson(route('mindy.chat'), [
            'message' => 'Who are you and what should I do first?',
            'current_page' => '/dashboard',
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertStringContainsString("Mindova's AI guide", $response->json('reply'));

        $this->assertSame(2, MindyMessage::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('mindy_messages', [
            'user_id' => $user->id,
            'role' => 'user',
            'content' => 'Who are you and what should I do first?',
        ]);
        $this->assertDatabaseHas('mindy_messages', [
            'user_id' => $user->id,
            'role' => 'assistant',
        ]);
    }

    public function test_chat_history_returns_prior_turns_in_order(): void
    {
        [$user] = $this->makeVolunteer();

        MindyMessage::create(['user_id' => $user->id, 'role' => 'user', 'content' => 'Hi', 'created_at' => now()->subMinutes(2)]);
        MindyMessage::create(['user_id' => $user->id, 'role' => 'assistant', 'content' => 'Hello!', 'created_at' => now()->subMinutes(1)]);

        $response = $this->actingAs($user)->getJson(route('mindy.history'));

        $response->assertOk();
        $messages = $response->json('messages');
        $this->assertCount(2, $messages);
        $this->assertSame('Hi', $messages[0]['content']);
        $this->assertSame('Hello!', $messages[1]['content']);
    }

    public function test_a_user_cannot_see_another_users_mindy_history(): void
    {
        [$userA] = $this->makeVolunteer();
        [$userB] = $this->makeVolunteer();

        MindyMessage::create(['user_id' => $userA->id, 'role' => 'user', 'content' => 'Only for A', 'created_at' => now()]);

        $response = $this->actingAs($userB)->getJson(route('mindy.history'));

        $response->assertOk();
        $this->assertCount(0, $response->json('messages'));
    }

    public function test_context_reflects_a_real_pending_task_invitation(): void
    {
        [$user, $volunteer] = $this->makeVolunteer();

        $task = Task::factory()->create();
        TaskAssignment::factory()->create([
            'task_id' => $task->id,
            'volunteer_id' => $volunteer->id,
            'invitation_status' => 'invited',
        ]);

        $capturedContext = null;
        $this->partialMock(MindyAssistantService::class, function ($mock) use (&$capturedContext) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRequestType')->andReturn('mindy_chat');
            $mock->shouldReceive('reply')->once()->andReturnUsing(
                function ($user, $context) use (&$capturedContext) {
                    $capturedContext = $context;
                    return 'Noted.';
                }
            );
        });

        $this->actingAs($user)->postJson(route('mindy.chat'), [
            'message' => 'What should I do next?',
        ])->assertOk();

        $this->assertNotNull($capturedContext);
        $this->assertSame('Community Member', $capturedContext['user_role']);
        $this->assertNotEmpty($capturedContext['pending_items']);
        $this->assertStringContainsString('task invitation', implode(' ', $capturedContext['pending_items']));
    }
}
