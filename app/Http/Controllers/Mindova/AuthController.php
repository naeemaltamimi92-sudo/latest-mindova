<?php

namespace App\Http\Controllers\Mindova;

use App\Http\Controllers\Controller;
use App\Models\MindovaTeamMember;
use App\Models\MindovaAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        // Clear the logout flag when visiting login page (allows re-login)
        session()->forget('mindova_logout_flag');

        // If already logged in to Mindova admin, redirect to dashboard
        if (session('mindova_team_member_id')) {
            return redirect()->route('mindova.dashboard');
        }

        // Check if user is logged in via main site and has a linked team member
        if (auth()->check()) {
            $user = auth()->user();
            $teamMember = MindovaTeamMember::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($teamMember && $teamMember->is_active) {
                // Link user_id if not already linked (for email-matched members)
                if (!$teamMember->user_id) {
                    $teamMember->update(['user_id' => $user->id]);
                }

                // Auto-login to Mindova admin
                session(['mindova_team_member_id' => $teamMember->id]);
                $teamMember->updateLastLogin();
                MindovaAuditLog::logLogin($teamMember);

                // Redirect based on password change status
                if (!$teamMember->password_changed) {
                    return redirect()->route('mindova.password.change');
                }

                return redirect()->route('mindova.dashboard');
            }
        }

        return view('mindova.auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $teamMember = MindovaTeamMember::where('email', $validated['email'])->first();

        if (!$teamMember) {
            return back()->withErrors(['email' => __('Invalid credentials.')]);
        }

        if (!$teamMember->is_active) {
            return back()->withErrors(['email' => __('Your account has been deactivated.')]);
        }

        // Check password (either temporary or user's linked account password)
        $passwordValid = false;

        // First check temporary password
        if ($teamMember->temporary_password && Hash::check($validated['password'], $teamMember->temporary_password)) {
            $passwordValid = true;
        }

        // If user is linked, check their account password
        if (!$passwordValid && $teamMember->user_id) {
            $user = $teamMember->user;
            if ($user && Hash::check($validated['password'], $user->password)) {
                $passwordValid = true;
            }
        }

        if (!$passwordValid) {
            return back()->withErrors(['email' => __('Invalid credentials.')]);
        }

        // Set session
        session(['mindova_team_member_id' => $teamMember->id]);

        // Update last login
        $teamMember->updateLastLogin();

        // Log the login
        MindovaAuditLog::logLogin($teamMember);

        // Redirect based on password change status
        if (!$teamMember->password_changed) {
            return redirect()->route('mindova.password.change');
        }

        return redirect()->route('mindova.dashboard');
    }

    /**
     * Show password change form.
     */
    public function showPasswordChangeForm()
    {
        $teamMember = MindovaTeamMember::find(session('mindova_team_member_id'));

        if (!$teamMember) {
            return redirect()->route('mindova.login');
        }

        return view('mindova.auth.change-password', [
            'teamMember' => $teamMember,
            'isFirstTime' => !$teamMember->password_changed,
        ]);
    }

    /**
     * Handle password change.
     */
    public function changePassword(Request $request)
    {
        $teamMember = MindovaTeamMember::find(session('mindova_team_member_id'));

        if (!$teamMember) {
            return redirect()->route('mindova.login');
        }

        $rules = [
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ];

        // If not first time, require current password
        if ($teamMember->password_changed) {
            $rules['current_password'] = ['required', 'string'];
        }

        $validated = $request->validate($rules);

        // Verify current password if required
        if ($teamMember->password_changed) {
            $passwordValid = false;

            // Check user's password
            if ($teamMember->user_id && $teamMember->user) {
                $passwordValid = Hash::check($validated['current_password'], $teamMember->user->password);
            }

            if (!$passwordValid) {
                return back()->withErrors(['current_password' => __('Current password is incorrect.')]);
            }
        }

        // Update password - need to link to user or create user
        if ($teamMember->user_id && $teamMember->user) {
            $teamMember->user->update([
                'password' => Hash::make($validated['password']),
            ]);
        } else {
            // Check if user already exists with this email
            $user = \App\Models\User::where('email', $teamMember->email)->first();

            if ($user) {
                // Link to existing user and update password
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            } else {
                // Create a new user account
                $user = \App\Models\User::create([
                    'name' => $teamMember->name,
                    'email' => $teamMember->email,
                    'password' => Hash::make($validated['password']),
                    'user_type' => 'admin',
                ]);
            }

            $teamMember->update(['user_id' => $user->id]);
        }

        // Mark password as changed
        $teamMember->markPasswordChanged();

        // Log the password change
        MindovaAuditLog::logPasswordChange($teamMember);

        return redirect()->route('mindova.dashboard')
            ->with('success', __('Password changed successfully.'));
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        $teamMember = MindovaTeamMember::find(session('mindova_team_member_id'));

        if ($teamMember) {
            MindovaAuditLog::logLogout($teamMember);
        }

        // Clear all Mindova session data
        session()->forget('mindova_team_member_id');

        // Logout from main site authentication as well
        if (auth()->check()) {
            auth()->logout();
        }

        // Invalidate the session completely
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect to home page with success message
        return redirect('/')
            ->with('success', __('You have been logged out successfully.'));
    }
}
