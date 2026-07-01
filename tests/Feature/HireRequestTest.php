<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\HireRequest;
use App\Models\HiringRecord;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HireRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_volunteer_can_accept_a_pending_hire_request_and_a_hiring_record_is_created(): void
    {
        $companyUser = User::factory()->state(['user_type' => 'company'])->create();
        $volunteerUser = User::factory()->state(['user_type' => 'volunteer'])->create();
        $volunteer = Volunteer::factory()->create([
            'user_id' => $volunteerUser->id,
            'reputation_score' => 300,
            'trust_score' => 95.5,
        ]);

        $company = \App\Models\Company::factory()->create();
        $challenge = \App\Models\Challenge::factory()->create(['company_id' => $company->id]);

        Certificate::create([
            'user_id' => $volunteerUser->id,
            'challenge_id' => $challenge->id,
            'role' => 'Contributor',
            'contribution_summary' => 'Delivered verified work on this challenge.',
            'company_confirmed' => true,
            'is_revoked' => false,
            'issued_at' => now(),
        ]);

        $hireRequest = HireRequest::factory()->create([
            'company_user_id' => $companyUser->id,
            'volunteer_id' => $volunteer->id,
            'status' => 'pending',
            'type' => 'project',
            'position_title' => 'Backend Engineer',
        ]);

        $response = $this->actingAs($volunteerUser)
            ->post(route('hire-requests.accept', $hireRequest));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $hireRequest->refresh();
        $this->assertSame('converted', $hireRequest->status);
        $this->assertNotNull($hireRequest->responded_at);

        $this->assertDatabaseHas('hiring_records', [
            'hire_request_id' => $hireRequest->id,
            'volunteer_id' => $volunteer->id,
            'company_user_id' => $companyUser->id,
            'position_title' => 'Backend Engineer',
        ]);

        $record = HiringRecord::where('hire_request_id', $hireRequest->id)->first();
        $this->assertEquals(300, $record->reputation_stars_at_hire);
        $this->assertEquals(95.5, $record->trust_score_at_hire);
    }

    public function test_volunteer_cannot_accept_someone_elses_hire_request(): void
    {
        $companyUser = User::factory()->state(['user_type' => 'company'])->create();
        [$owner, $intruder] = User::factory()->count(2)->state(['user_type' => 'volunteer'])->create();
        $ownerVolunteer = Volunteer::factory()->create(['user_id' => $owner->id]);
        Volunteer::factory()->create(['user_id' => $intruder->id]);

        $hireRequest = HireRequest::factory()->create([
            'company_user_id' => $companyUser->id,
            'volunteer_id' => $ownerVolunteer->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($intruder)
            ->post(route('hire-requests.accept', $hireRequest));

        $response->assertStatus(403);
        $this->assertSame('pending', $hireRequest->fresh()->status);
        $this->assertDatabaseMissing('hiring_records', ['hire_request_id' => $hireRequest->id]);
    }

    public function test_volunteer_cannot_accept_an_already_answered_hire_request(): void
    {
        $companyUser = User::factory()->state(['user_type' => 'company'])->create();
        $volunteerUser = User::factory()->state(['user_type' => 'volunteer'])->create();
        $volunteer = Volunteer::factory()->create(['user_id' => $volunteerUser->id]);

        $hireRequest = HireRequest::factory()->create([
            'company_user_id' => $companyUser->id,
            'volunteer_id' => $volunteer->id,
            'status' => 'declined',
        ]);

        $response = $this->actingAs($volunteerUser)
            ->post(route('hire-requests.accept', $hireRequest));

        $response->assertStatus(422);
        $this->assertDatabaseMissing('hiring_records', ['hire_request_id' => $hireRequest->id]);
    }
}
