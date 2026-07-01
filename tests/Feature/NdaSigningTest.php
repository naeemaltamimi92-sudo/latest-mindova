<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\ChallengeNdaSigning;
use App\Models\NdaAgreement;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NdaSigningTest extends TestCase
{
    use RefreshDatabase;

    private function seedGeneralNda(): void
    {
        NdaAgreement::create([
            'title' => 'General NDA',
            'type' => 'general',
            'content' => 'Test NDA content',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
        ]);
    }

    private function seedChallengeNda(): void
    {
        NdaAgreement::create([
            'title' => 'Challenge NDA',
            'type' => 'challenge_specific',
            'content' => 'Test challenge NDA content',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
        ]);
    }

    public function test_volunteer_can_sign_general_nda_when_active_agreement_exists(): void
    {
        $this->seedGeneralNda();

        $user = User::factory()->state(['user_type' => 'volunteer'])->create();
        $volunteer = Volunteer::factory()->create([
            'user_id' => $user->id,
            'general_nda_signed' => false,
        ]);

        $response = $this->actingAs($user)->post(route('nda.general.sign'), [
            'full_name' => $user->name,
            'agree' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue($volunteer->fresh()->general_nda_signed);
        $this->assertNotNull($volunteer->fresh()->general_nda_signed_at);
    }

    public function test_general_nda_signing_shows_error_when_no_active_agreement_exists(): void
    {
        // Regression test for the bug this session fixed: NdaAgreement
        // used to be read from a file on disk that never actually
        // reached production, so this path returned an unhelpful
        // "contact support" error for every volunteer. It's now
        // database-backed via a migration that seeds an active row by
        // default (see NdaSigningTest's other cases) - so to exercise
        // the "genuinely no active NDA" branch here, explicitly clear
        // it rather than skip seeding, since seeding happens
        // automatically as part of the schema migration.
        NdaAgreement::where('type', 'general')->delete();

        $user = User::factory()->state(['user_type' => 'volunteer'])->create();
        Volunteer::factory()->create([
            'user_id' => $user->id,
            'general_nda_signed' => false,
        ]);

        $response = $this->actingAs($user)->post(route('nda.general.sign'), [
            'full_name' => $user->name,
            'agree' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_volunteer_cannot_sign_general_nda_twice(): void
    {
        $this->seedGeneralNda();

        $user = User::factory()->state(['user_type' => 'volunteer'])->create();
        Volunteer::factory()->create([
            'user_id' => $user->id,
            'general_nda_signed' => true,
        ]);

        $response = $this->actingAs($user)->post(route('nda.general.sign'), [
            'full_name' => $user->name,
            'agree' => '1',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('info');
    }

    public function test_volunteer_can_sign_challenge_specific_nda(): void
    {
        $this->seedGeneralNda();
        $this->seedChallengeNda();

        $user = User::factory()->state(['user_type' => 'volunteer'])->create();
        Volunteer::factory()->create([
            'user_id' => $user->id,
            'general_nda_signed' => true,
        ]);
        $challenge = Challenge::factory()->create(['requires_nda' => true]);

        $response = $this->actingAs($user)->post(route('nda.challenge.sign', $challenge), [
            'full_name' => $user->name,
            'agree' => '1',
        ]);

        $response->assertRedirect(route('challenges.show', $challenge));
        $this->assertTrue(
            ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)
        );
    }

    public function test_challenge_nda_requires_general_nda_signed_first(): void
    {
        $this->seedChallengeNda();

        $user = User::factory()->state(['user_type' => 'volunteer'])->create();
        Volunteer::factory()->create([
            'user_id' => $user->id,
            'general_nda_signed' => false,
        ]);
        $challenge = Challenge::factory()->create(['requires_nda' => true]);

        $response = $this->actingAs($user)->post(route('nda.challenge.sign', $challenge), [
            'full_name' => $user->name,
            'agree' => '1',
        ]);

        $response->assertRedirect(route('nda.general'));
    }
}
