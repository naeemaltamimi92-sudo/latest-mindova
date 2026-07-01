<?php

namespace Tests\Feature;

use App\Models\FeedbackItem;
use App\Models\FeedbackVote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FeedbackBoardTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $type = 'volunteer'): User
    {
        return User::factory()->state(['user_type' => $type])->create();
    }

    // --- Submission ---------------------------------------------------

    public function test_guest_cannot_submit_feedback(): void
    {
        $response = $this->post(route('feedback.store'), [
            'title' => 'Add dark mode',
            'description' => str_repeat('Would love a dark theme option. ', 2),
            'type' => 'idea',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertSame(0, FeedbackItem::count());
    }

    public function test_authenticated_user_can_submit_valid_feedback(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('feedback.store'), [
            'title' => 'Add dark mode',
            'description' => str_repeat('Would love a dark theme option for the dashboard. ', 2),
            'type' => 'feature_request',
            'category' => 'UI',
        ]);

        $item = FeedbackItem::first();
        $response->assertRedirect(route('feedback.show', $item));
        $this->assertNotNull($item);
        $this->assertSame($user->id, $item->user_id);
        $this->assertSame('open', $item->status);
        $this->assertSame(0, $item->votes_count);
    }

    public function test_submission_fails_validation_with_missing_fields(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post(route('feedback.store'), [
            'title' => '',
            'description' => 'too short',
            'type' => 'not_a_real_type',
        ]);

        $response->assertSessionHasErrors(['title', 'description', 'type']);
        $this->assertSame(0, FeedbackItem::count());
    }

    // --- Voting ---------------------------------------------------------

    public function test_authenticated_user_can_upvote_and_toggle_it_off(): void
    {
        $author = $this->makeUser();
        $voter = $this->makeUser();
        $item = FeedbackItem::factory()->create(['user_id' => $author->id]);

        // First vote
        $this->actingAs($voter)->post(route('feedback.vote', $item))->assertRedirect();
        $this->assertSame(1, $item->fresh()->votes_count);
        $this->assertDatabaseHas('feedback_votes', ['user_id' => $voter->id, 'feedback_item_id' => $item->id]);

        // Toggling again removes the vote
        $this->actingAs($voter)->post(route('feedback.vote', $item))->assertRedirect();
        $this->assertSame(0, $item->fresh()->votes_count);
        $this->assertDatabaseMissing('feedback_votes', ['user_id' => $voter->id, 'feedback_item_id' => $item->id]);
    }

    public function test_guest_cannot_vote(): void
    {
        $item = FeedbackItem::factory()->create();

        $response = $this->post(route('feedback.vote', $item));

        $response->assertRedirect(route('login'));
        $this->assertSame(0, $item->fresh()->votes_count);
    }

    public function test_a_user_cannot_double_vote_even_by_repeated_requests(): void
    {
        $voter = $this->makeUser();
        $item = FeedbackItem::factory()->create();

        // Two rapid votes: first creates, second toggles it off (not a double-vote).
        $this->actingAs($voter)->post(route('feedback.vote', $item));
        $this->assertSame(1, FeedbackVote::where('feedback_item_id', $item->id)->count());

        // The DB-level unique constraint is the second line of defense:
        // a raw insert bypassing app logic must fail, not silently duplicate.
        $this->expectException(\Illuminate\Database\QueryException::class);
        DB::table('feedback_votes')->insert([
            'user_id' => $voter->id,
            'feedback_item_id' => $item->id,
            'created_at' => now(),
        ]);
    }

    // --- Comments ---------------------------------------------------------

    public function test_authenticated_user_can_comment_on_an_item(): void
    {
        $user = $this->makeUser();
        $item = FeedbackItem::factory()->create();

        $response = $this->actingAs($user)->post(route('feedback.comments.store', $item), [
            'body' => 'This would help our team a lot!',
        ]);

        $response->assertRedirect(route('feedback.show', $item));
        $this->assertDatabaseHas('feedback_comments', [
            'user_id' => $user->id,
            'feedback_item_id' => $item->id,
            'body' => 'This would help our team a lot!',
        ]);
    }

    // --- Ownership / edit / delete ---------------------------------------------------------

    public function test_owner_can_edit_their_own_feedback_item(): void
    {
        $owner = $this->makeUser();
        $item = FeedbackItem::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->put(route('feedback.update', $item), [
            'title' => 'Updated title',
            'description' => $item->description,
            'type' => $item->type,
        ]);

        $response->assertRedirect(route('feedback.show', $item));
        $this->assertSame('Updated title', $item->fresh()->title);
    }

    public function test_a_non_owner_cannot_edit_someone_elses_feedback_item(): void
    {
        $owner = $this->makeUser();
        $intruder = $this->makeUser();
        $item = FeedbackItem::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($intruder)->put(route('feedback.update', $item), [
            'title' => 'Hijacked title',
            'description' => $item->description,
            'type' => $item->type,
        ]);

        $response->assertForbidden();
        $this->assertNotSame('Hijacked title', $item->fresh()->title);
    }

    // --- Admin status changes ---------------------------------------------------------

    public function test_admin_can_change_a_feedback_items_status(): void
    {
        $admin = $this->makeUser('admin');
        $item = FeedbackItem::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.feedback.updateStatus', $item), [
            'status' => 'planned',
        ]);

        $response->assertRedirect();
        $this->assertSame('planned', $item->fresh()->status);
    }

    public function test_non_admin_cannot_change_a_feedback_items_status(): void
    {
        $user = $this->makeUser();
        $item = FeedbackItem::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('admin.feedback.updateStatus', $item), [
            'status' => 'planned',
        ]);

        $response->assertForbidden();
        $this->assertSame('open', $item->fresh()->status);
    }

    public function test_admin_can_mark_an_item_as_a_duplicate(): void
    {
        $admin = $this->makeUser('admin');
        $original = FeedbackItem::factory()->create();
        $duplicate = FeedbackItem::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.feedback.markDuplicate', $duplicate), [
            'duplicate_of_id' => $original->id,
        ]);

        $response->assertRedirect();
        $this->assertSame($original->id, $duplicate->fresh()->duplicate_of_id);
    }
}
