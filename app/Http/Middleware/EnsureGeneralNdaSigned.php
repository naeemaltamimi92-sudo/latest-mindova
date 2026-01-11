<?php

namespace App\Http\Middleware;

use App\Models\NdaAgreement;
use App\Models\Volunteer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureGeneralNdaSigned
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

        if (!$volunteer) {
            return redirect()->route('complete-profile')
                ->with('error', 'Please complete your volunteer registration first.');
        }

        // Skip NDA check if no NDA template exists in the system
        // This allows the platform to function without pre-configured NDAs
        $activeNda = NdaAgreement::getActiveGeneralNda();
        if (!$activeNda) {
            return $next($request);
        }

        if (!$volunteer->general_nda_signed) {
            return redirect()->route('nda.general')
                ->with('warning', 'Please sign the general NDA to continue.');
        }

        return $next($request);
    }
}
