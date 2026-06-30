<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class LinkedInAuthController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  OAuth entry-point
    // ─────────────────────────────────────────────────────────────

    public function redirect(Request $request)
    {
        // Store intent so the callback knows what to do
        if ($request->filled('intent')) {
            session(['linkedin_intent' => $request->input('intent')]);
        }

        if (SiteSetting::isMaintenanceMode()) {
            return redirect()->route('maintenance');
        }

        return Socialite::driver('linkedin-openid')->stateless()->redirect();
    }

    // ─────────────────────────────────────────────────────────────
    //  OAuth callback  (handles both login and connect intents)
    // ─────────────────────────────────────────────────────────────

    public function callback()
    {
        try {
            $linkedInUser = Socialite::driver('linkedin-openid')->stateless()->user();

            $intent = session('linkedin_intent', 'login');
            session()->forget('linkedin_intent');

            // ── CONNECT intent: link LinkedIn to an already-authenticated account ──
            if ($intent === 'connect' && Auth::check()) {
                return $this->handleConnect($linkedInUser);
            }

            // ── LOGIN / REGISTER intent ──
            return $this->handleLogin($linkedInUser);

        } catch (\Exception $e) {
            \Log::error('LinkedIn OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'LinkedIn authentication failed. Please try again.');
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  Connect LinkedIn to existing authenticated account
    //  Route: GET /profile/linkedin/connect
    // ─────────────────────────────────────────────────────────────

    public function connect()
    {
        session(['linkedin_intent' => 'connect']);
        return redirect()->route('auth.linkedin.redirect');
    }

    // ─────────────────────────────────────────────────────────────
    //  Disconnect LinkedIn from account
    //  Route: POST /profile/linkedin/disconnect
    // ─────────────────────────────────────────────────────────────

    public function disconnect(Request $request)
    {
        $user = $request->user();

        if (!$user->hasPassword()) {
            return back()->with('error', 'Set a password first before disconnecting LinkedIn — otherwise you cannot log in.');
        }

        $user->update(['linkedin_id' => null]);

        return back()->with('success', 'LinkedIn account disconnected.');
    }

    // ─────────────────────────────────────────────────────────────
    //  Save/update LinkedIn profile URL
    //  Route: POST /profile/linkedin/url
    // ─────────────────────────────────────────────────────────────

    public function updateLinkedInUrl(Request $request)
    {
        $request->validate([
            'linkedin_profile_url' => 'nullable|url|max:255',
        ]);

        $request->user()->update([
            'linkedin_profile_url' => $request->input('linkedin_profile_url') ?: null,
        ]);

        return back()->with('success', 'LinkedIn profile URL updated.');
    }

    // ─────────────────────────────────────────────────────────────
    //  Logout
    // ─────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // ─────────────────────────────────────────────────────────────
    //  Private helpers
    // ─────────────────────────────────────────────────────────────

    private function handleConnect($linkedInUser)
    {
        $user = Auth::user();

        // Make sure this LinkedIn account isn't already tied to a different Mindova account
        $taken = User::where('linkedin_id', $linkedInUser->getId())
                     ->where('id', '!=', $user->id)
                     ->exists();

        if ($taken) {
            return redirect()->route('profile.edit')
                ->with('error', 'This LinkedIn account is already linked to another Mindova account.');
        }

        $user->update(['linkedin_id' => $linkedInUser->getId()]);

        // Import LinkedIn avatar only if the user doesn't have a photo yet
        $this->importAvatar($user, $linkedInUser->getAvatar());

        return redirect()->route('profile.edit')
            ->with('success', 'LinkedIn account connected! Your profile is now verified.')
            ->with('active_tab', 'account');
    }

    private function handleLogin($linkedInUser)
    {
        $user = User::where('linkedin_id', $linkedInUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $linkedInUser->getEmail())->first();

            if ($user) {
                // Existing email account — link the LinkedIn ID
                $user->update(['linkedin_id' => $linkedInUser->getId()]);
            } else {
                // Brand-new user
                if (SiteSetting::isMaintenanceMode()) {
                    return redirect()->route('maintenance')
                        ->with('error', __('Registration is disabled during maintenance mode.'));
                }

                $user = User::create([
                    'name'        => $linkedInUser->getName(),
                    'email'       => $linkedInUser->getEmail(),
                    'linkedin_id' => $linkedInUser->getId(),
                    'user_type'   => 'volunteer',
                    'is_active'   => true,
                ]);
                $user->forceFill(['email_verified_at' => now()])->save();
            }
        }

        if (SiteSetting::isMaintenanceMode() && ! $user->isAdmin()) {
            return redirect()->route('login')
                ->with('error', __('The platform is currently under maintenance.'));
        }

        Auth::login($user, true);

        $token = $user->createToken('auth_token')->plainTextToken;
        session(['api_token' => $token]);
        request()->session()->regenerate();

        // Import LinkedIn avatar if the user has no photo yet
        $this->importAvatar($user->fresh(), $linkedInUser->getAvatar());

        if ($user->isVolunteer() && ! $user->volunteer) {
            return redirect()->route('complete-profile')
                ->with('success', 'Welcome! Please complete your profile.');
        }

        if ($user->isCompany() && ! $user->company) {
            return redirect()->route('complete-profile')
                ->with('success', 'Welcome! Please complete your company profile.');
        }

        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }

    /**
     * Download the LinkedIn profile picture and store it as the volunteer's photo.
     * Only runs when the user has no photo yet — never overwrites an existing one.
     */
    private function importAvatar(User $user, ?string $avatarUrl): void
    {
        if (! $avatarUrl || ! $user->isVolunteer() || ! $user->volunteer) {
            return;
        }

        if ($user->volunteer->profile_picture) {
            return; // already has one — don't overwrite
        }

        try {
            $response = Http::timeout(10)->get($avatarUrl);

            if (! $response->successful()) {
                return;
            }

            $ext      = 'jpg';
            $filename = 'profile_' . $user->id . '_li_' . time() . '.' . $ext;
            Storage::disk('public')->put('profile_pictures/' . $filename, $response->body());

            $user->volunteer->update(['profile_picture' => 'profile_pictures/' . $filename]);

        } catch (\Exception) {
            // Avatar import is a convenience feature — fail silently
        }
    }
}
