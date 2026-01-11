<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check rate limiting
        $throttleKey = Str::transliterate(Str::lower($request->email).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // Attempt to log the user in
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true, // Only allow active users to log in
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($throttleKey);

            // Check if maintenance mode is enabled and user is not admin
            if (SiteSetting::isMaintenanceMode() && !Auth::user()->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => __('The platform is currently under maintenance. Only administrators can access at this time.'),
                ]);
            }

            $request->session()->regenerate();

            // Create API token for the user
            $token = Auth::user()->createToken('auth_token')->plainTextToken;
            session(['api_token' => $token]);

            return redirect()->intended(route('dashboard'));
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($throttleKey);

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        // Revoke all tokens for the user
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear session data completely
        $request->session()->flush();

        return redirect('/')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
    }
}
