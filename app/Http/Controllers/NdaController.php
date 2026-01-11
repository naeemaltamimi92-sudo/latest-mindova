<?php

namespace App\Http\Controllers;

use App\Models\ChallengeNdaSigning;
use App\Models\NdaAgreement;
use App\Models\Challenge;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NdaController extends Controller
{
    /**
     * Show the general NDA for a new volunteer to sign.
     */
    public function showGeneralNda()
    {
        $user = Auth::user();

        // Check if user is a volunteer
        $volunteer = Volunteer::where('user_id', $user->id)->first();

        if (!$volunteer) {
            return redirect()->route('complete-profile')
                ->with('error', 'Please complete your volunteer registration first.');
        }

        // Check if already signed
        if ($volunteer->general_nda_signed) {
            return redirect()->route('dashboard')
                ->with('info', 'You have already signed the general NDA.');
        }

        // Get the active general NDA
        $nda = NdaAgreement::getActiveGeneralNda();

        if (!$nda) {
            Log::error('No active general NDA found in database');
            return redirect()->back()
                ->with('error', 'NDA agreement not available. Please contact support.');
        }

        return view('nda.general', compact('nda', 'volunteer'));
    }

    /**
     * Process the general NDA signature.
     */
    public function signGeneralNda(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'agree' => 'required|accepted',
        ]);

        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->id)->firstOrFail();

        // Check if already signed
        if ($volunteer->general_nda_signed) {
            return redirect()->route('dashboard')
                ->with('info', 'You have already signed the general NDA.');
        }

        $nda = NdaAgreement::getActiveGeneralNda();

        if (!$nda) {
            return redirect()->back()
                ->with('error', 'NDA agreement not available. Please contact support.');
        }

        // Update volunteer record
        $volunteer->update([
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => $nda->version,
        ]);

        Log::info('General NDA signed', [
            'user_id' => $user->id,
            'volunteer_id' => $volunteer->id,
            'nda_version' => $nda->version,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Thank you for signing the NDA. You can now participate in challenges.');
    }

    /**
     * Show the challenge-specific NDA for a volunteer to sign.
     */
    public function showChallengeNda(Challenge $challenge)
    {
        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->id)->firstOrFail();

        // Check if general NDA is signed
        if (!$volunteer->general_nda_signed) {
            return redirect()->route('nda.general')
                ->with('error', 'Please sign the general NDA first.');
        }

        // Check if challenge requires NDA
        if (!$challenge->requires_nda) {
            return redirect()->route('challenges.show', $challenge)
                ->with('info', 'This challenge does not require a separate NDA.');
        }

        // Check if already signed
        if (ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
            return redirect()->route('challenges.show', $challenge)
                ->with('info', 'You have already signed the NDA for this challenge.');
        }

        // Get the active challenge-specific NDA template
        $ndaTemplate = NdaAgreement::getActiveChallengeNda();

        if (!$ndaTemplate) {
            Log::error('No active challenge-specific NDA found in database');
            return redirect()->back()
                ->with('error', 'Challenge NDA not available. Please contact support.');
        }

        // Customize the NDA content with challenge-specific information
        $ndaContent = $this->customizeNdaContent(
            $ndaTemplate->content,
            $challenge
        );

        return view('nda.challenge', compact('challenge', 'ndaTemplate', 'ndaContent', 'volunteer'));
    }

    /**
     * Process the challenge-specific NDA signature.
     */
    public function signChallengeNda(Request $request, Challenge $challenge)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'agree' => 'required|accepted',
        ]);

        $user = Auth::user();
        $volunteer = Volunteer::where('user_id', $user->id)->firstOrFail();

        // Check if general NDA is signed
        if (!$volunteer->general_nda_signed) {
            return redirect()->route('nda.general')
                ->with('error', 'Please sign the general NDA first.');
        }

        // Check if challenge requires NDA
        if (!$challenge->requires_nda) {
            return redirect()->route('challenges.show', $challenge)
                ->with('info', 'This challenge does not require a separate NDA.');
        }

        // Check if already signed
        if (ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
            return redirect()->route('challenges.show', $challenge)
                ->with('info', 'You have already signed the NDA for this challenge.');
        }

        $ndaAgreement = NdaAgreement::getActiveChallengeNda();

        if (!$ndaAgreement) {
            return redirect()->back()
                ->with('error', 'Challenge NDA not available. Please contact support.');
        }

        DB::beginTransaction();
        try {
            // Generate signature hash
            $timestamp = now()->toDateTimeString();
            $signatureHash = ChallengeNdaSigning::generateSignatureHash(
                $user->id,
                $challenge->id,
                $timestamp
            );

            // Create the NDA signing record
            ChallengeNdaSigning::create([
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'nda_agreement_id' => $ndaAgreement->id,
                'signer_name' => $request->full_name,
                'signer_email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'signed_at' => $timestamp,
                'signature_hash' => $signatureHash,
                'is_valid' => true,
            ]);

            DB::commit();

            Log::info('Challenge NDA signed', [
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'nda_agreement_id' => $ndaAgreement->id,
                'signature_hash' => $signatureHash,
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('challenges.show', $challenge)
                ->with('success', 'Thank you for signing the NDA. You can now view the full challenge details.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to sign challenge NDA', [
                'user_id' => $user->id,
                'challenge_id' => $challenge->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to process NDA signature. Please try again.');
        }
    }

    /**
     * Check NDA signing status for a challenge (AJAX endpoint).
     */
    public function checkNdaStatus(Challenge $challenge)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'signed' => false,
                'requires_general_nda' => true,
            ]);
        }

        $volunteer = Volunteer::where('user_id', $user->id)->first();

        if (!$volunteer || !$volunteer->general_nda_signed) {
            return response()->json([
                'signed' => false,
                'requires_general_nda' => true,
            ]);
        }

        if (!$challenge->requires_nda) {
            return response()->json([
                'signed' => true,
                'requires_nda' => false,
            ]);
        }

        $hasSigned = ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id);

        return response()->json([
            'signed' => $hasSigned,
            'requires_nda' => true,
            'requires_general_nda' => false,
        ]);
    }

    /**
     * Customize the NDA content with challenge-specific information.
     */
    private function customizeNdaContent(string $content, Challenge $challenge): string
    {
        // Replace placeholders with actual challenge information
        $content = str_replace(
            '[CONFIDENTIALITY_LEVEL: To be specified at signing]',
            'CONFIDENTIALITY LEVEL: ' . strtoupper($challenge->confidentiality_level ?? 'standard'),
            $content
        );

        $customTerms = $challenge->nda_custom_terms
            ? $challenge->nda_custom_terms
            : 'No additional custom terms for this challenge.';

        $content = str_replace(
            '[CUSTOM_TERMS: To be specified if applicable]',
            $customTerms,
            $content
        );

        $content = str_replace(
            '[Challenge ID: Recorded at signing]',
            'Challenge ID: ' . $challenge->id,
            $content
        );

        return $content;
    }

    /**
     * Revoke an NDA signing (admin only).
     */
    public function revokeNda(Request $request, ChallengeNdaSigning $signing)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $signing->update([
            'is_valid' => false,
            'revoked_at' => now(),
            'revocation_reason' => $request->reason,
        ]);

        Log::warning('NDA signing revoked', [
            'signing_id' => $signing->id,
            'user_id' => $signing->user_id,
            'challenge_id' => $signing->challenge_id,
            'reason' => $request->reason,
            'revoked_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'NDA signing has been revoked.');
    }
}
