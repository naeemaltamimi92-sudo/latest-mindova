<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LinkedInAuthController extends Controller
{
    /**
     * Redirect to LinkedIn for authentication.
     */
    public function redirect()
    {
        // Block OAuth during maintenance mode
        if (SiteSetting::isMaintenanceMode()) {
            return redirect()->route('maintenance');
        }

        return Socialite::driver('linkedin')
            ->scopes(['openid', 'profile', 'email'])
            ->stateless()
            ->redirect();
    }

    /**
     * Handle LinkedIn callback.
     */
    public function callback()
    {
        try {
            $linkedInUser = Socialite::driver('linkedin')->stateless()->user();

            // Find or create user
            $user = User::where('linkedin_id', $linkedInUser->getId())->first();
            $isNewUser = false;

            if (!$user) {
                // Check if email already exists
                $user = User::where('email', $linkedInUser->getEmail())->first();

                if ($user) {
                    // Link LinkedIn account to existing user
                    $user->update([
                        'linkedin_id' => $linkedInUser->getId(),
                        'linkedin_profile_url' => $linkedInUser->user['publicProfileUrl'] ?? null,
                    ]);
                } else {
                    // Block new user registration during maintenance mode
                    if (SiteSetting::isMaintenanceMode()) {
                        return redirect()->route('maintenance')
                            ->with('error', __('Registration is disabled during maintenance mode.'));
                    }

                    // Create new user
                    $user = User::create([
                        'name' => $linkedInUser->getName(),
                        'email' => $linkedInUser->getEmail(),
                        'linkedin_id' => $linkedInUser->getId(),
                        'linkedin_profile_url' => $linkedInUser->user['publicProfileUrl'] ?? null,
                        'email_verified_at' => now(),
                        'user_type' => 'volunteer', // Default to volunteer
                        'is_active' => true,
                    ]);
                    $isNewUser = true;
                }
            }

            // Check if maintenance mode is enabled and user is not admin (existing users)
            if (SiteSetting::isMaintenanceMode() && !$user->isAdmin()) {
                return redirect()->route('login')
                    ->with('error', __('The platform is currently under maintenance. Only administrators can access at this time.'));
            }

            // Log the user in
            Auth::login($user, true);

            // Create API token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // Store token in session for web use
            session(['api_token' => $token]);

            // Regenerate session to prevent fixation attacks
            request()->session()->regenerate();

            // Check if user has completed profile
            if ($user->isVolunteer() && !$user->volunteer) {
                return redirect()->route('complete-profile')
                    ->with('success', 'Welcome! Please complete your profile and upload your CV to extract skills and experience.');
            }

            if ($user->isCompany() && !$user->company) {
                return redirect()->route('complete-profile')
                    ->with('success', 'Welcome! Please complete your company profile.');
            }

            // Redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Welcome back!');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('LinkedIn OAuth State Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'LinkedIn authentication session expired. Please try again.');
        } catch (\Exception $e) {
            \Log::error('LinkedIn OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'LinkedIn authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
