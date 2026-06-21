<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks access to the rest of the app once a user with 2FA enabled has
 * passed the password check, until they also pass the TOTP/recovery-code
 * challenge. Without this, enabling 2FA in account settings had no effect
 * on the actual login flow.
 */
class EnsureTwoFactorVerified
{
    protected array $exemptRouteNames = [
        'two-factor.challenge',
        'security.2fa.verify',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->two_factor_confirmed_at) {
            return $next($request);
        }

        if ($request->session()->get('2fa_verified')) {
            return $next($request);
        }

        if (in_array($request->route()?->getName(), $this->exemptRouteNames, true)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Two-factor verification required.',
            ], 423);
        }

        return redirect()->route('two-factor.challenge');
    }
}
