<?php

namespace App\Http\Middleware;

use App\Models\Challenge;
use App\Models\ChallengeNdaSigning;
use App\Models\NdaAgreement;
use App\Models\Volunteer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureChallengeNdaSigned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue.');
        }

        // SKIP NDA checks for companies and admins - they don't need to sign NDAs
        if ($user->isCompany() || $user->isAdmin()) {
            return $next($request);
        }

        // NDA checks ONLY apply to volunteers
        $volunteer = Volunteer::where('user_id', $user->id)->first();

        // Skip general NDA check if no NDA template exists
        $activeGeneralNda = NdaAgreement::getActiveGeneralNda();
        if ($activeGeneralNda && (!$volunteer || !$volunteer->general_nda_signed)) {
            return redirect()->route('nda.general')
                ->with('warning', 'Please sign the general NDA first.');
        }

        // Get challenge from route parameter
        $challenge = $request->route('challenge');

        // If challenge is an ID, load the model
        if (!$challenge instanceof Challenge) {
            $challenge = Challenge::findOrFail($challenge);
        }

        // Check if challenge requires NDA
        if (!$challenge->requires_nda) {
            return $next($request);
        }

        // Skip challenge NDA check if no NDA template exists
        $activeChallengeNda = NdaAgreement::getActiveChallengeNda();
        if (!$activeChallengeNda) {
            return $next($request);
        }

        // Check if challenge-specific NDA is signed
        if (!ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
            return redirect()->route('nda.challenge', $challenge)
                ->with('warning', 'Please sign the challenge-specific NDA to view full details.');
        }

        return $next($request);
    }
}
