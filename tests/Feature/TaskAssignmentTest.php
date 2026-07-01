<?php

namespace Tests\Feature;

use App\Jobs\MatchVolunteersToTasks;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private function makeVolunteer(): array
    {
        $user = User::factory()->state(['user_type' => 'volunteer'])->create();
        $volunteer = Volunteer::factory()->create([
            'user_id' => $user->id,
            'general_nda_signed' => true,
        ]);

        return [$user, $volunteer];
    }

    public function test_volunteer_can_accept_an_invited_assignment(): void
    {
        [$user, $volunteer] = $this->makeVolunteer();
        $task = Task::factory()->create(['estimated_hours' => 5]);
        $assignment = TaskAssignment::factory()->create([
            'volunteer_id' => $volunteer->id,
            'task_id' => $task->id,
            'invitation_status' => 'invited',
        ]);

        $response = $this->actingAs($user)->post(route('assignments.accept', $assignment));

        $response->assertRedirect(route('tasks.show', $task->id));
        $assignment->refresh();
        $this->assertSame('accepted', $assignment->invitation_status);
        $this->assertNotNull($assignment->responded_at);
        $this->assertSame('assigned', $task->fresh()->status);
    }

    public function test_volunteer_cannot_accept_someone_elses_assignment(): void
    {
        [$user] = $this->makeVolunteer();
        [, $otherVolunteer] = $this->makeVolunteer();
        $assignment = TaskAssignment::factory()->create([
            'volunteer_id' => $otherVolunteer->id,
            'invitation_status' => 'invited',
        ]);

        $response = $this->actingAs($user)->post(route('assignments.accept', $assignment));

        $response->assertStatus(403);
        $this->assertSame('invited', $assignment->fresh()->invitation_status);
    }

    public function test_volunteer_cannot_accept_a_second_task_while_one_is_active(): void
    {
        [$user, $volunteer] = $this->makeVolunteer();

        // Already has an active task
        TaskAssignment::factory()->create([
            'volunteer_id' => $volunteer->id,
            'invitation_status' => 'in_progress',
        ]);

        $newAssignment = TaskAssignment::factory()->create([
            'volunteer_id' => $volunteer->id,
            'invitation_status' => 'invited',
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('assignments.accept', $newAssignment));

        $response->assertStatus(422);
        $this->assertSame('invited', $newAssignment->fresh()->invitation_status);
    }

    public function test_volunteer_can_reject_an_invited_assignment_and_rematching_is_queued(): void
    {
        Queue::fake();

        [$user, $volunteer] = $this->makeVolunteer();
        $assignment = TaskAssignment::factory()->create([
            'volunteer_id' => $volunteer->id,
            'invitation_status' => 'invited',
        ]);

        $response = $this->actingAs($user)->postJson(route('assignments.decline', $assignment), [
            'reason' => 'Not available',
        ]);

        $response->assertOk();
        $this->assertSame('declined', $assignment->fresh()->invitation_status);
        Queue::assertPushed(MatchVolunteersToTasks::class);
    }

    public function test_volunteer_can_complete_an_in_progress_assignment(): void
    {
        [$user, $volunteer] = $this->makeVolunteer();
        $assignment = TaskAssignment::factory()->create([
            'volunteer_id' => $volunteer->id,
            'invitation_status' => 'in_progress',
        ]);

        // complete() is only exposed via the Sanctum API, not routes/web.php.
        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/assignments/{$assignment->id}/complete", [
                'actual_hours' => 8,
            ]);

        $response->assertOk();
        $assignment->refresh();
        $this->assertSame('completed', $assignment->invitation_status);
        $this->assertNotNull($assignment->completed_at);
        $this->assertEquals(8, $assignment->actual_hours);
    }

    public function test_completing_an_assignment_not_in_progress_is_rejected(): void
    {
        [$user, $volunteer] = $this->makeVolunteer();
        $assignment = TaskAssignment::factory()->create([
            'volunteer_id' => $volunteer->id,
            'invitation_status' => 'accepted',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/assignments/{$assignment->id}/complete", [
                'actual_hours' => 8,
            ]);

        $response->assertStatus(422);
        $this->assertSame('accepted', $assignment->fresh()->invitation_status);
    }
}
