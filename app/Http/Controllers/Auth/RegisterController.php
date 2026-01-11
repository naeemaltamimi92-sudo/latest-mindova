<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        // Block registration during maintenance mode
        if (SiteSetting::isMaintenanceMode()) {
            return redirect()->route('maintenance');
        }

        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        // Block registration during maintenance mode
        if (SiteSetting::isMaintenanceMode()) {
            return redirect()->route('maintenance')
                ->with('error', __('Registration is disabled during maintenance mode.'));
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:volunteer,company',
            'terms' => 'accepted',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $validated['user_type'],
            'email_verified_at' => now(), // Auto-verify for now
            'is_active' => true,
        ]);

        // Handle profile picture upload for volunteers
        if ($request->hasFile('profile_picture') && $validated['user_type'] === 'volunteer') {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_pictures', $filename, 'public');

            // Store the path in session to be used during profile completion
            session(['volunteer_profile_picture' => $path]);
        }

        // Log the user in
        Auth::login($user);

        // Redirect to profile completion
        return redirect()->route('complete-profile')
            ->with('success', 'Account created successfully! Please complete your profile.');
    }
}
