<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Company;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $key = Str::transliterate(Str::lower($request->email) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key);
            return response()->json(['message' => 'These credentials do not match our records.'], 401);
        }

        if (! $user->is_active) {
            return response()->json(['message' => 'Your account has been deactivated.'], 403);
        }

        if (SiteSetting::isMaintenanceMode() && ! $user->isAdmin()) {
            return response()->json(['message' => 'Platform is under maintenance.'], 503);
        }

        RateLimiter::clear($key);

        $user->tokens()->where('name', 'mobile_token')->delete();
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'token'     => $token,
            'user'      => $this->formatUser($user),
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        if (SiteSetting::isMaintenanceMode()) {
            return response()->json(['message' => 'Registration is disabled during maintenance.'], 503);
        }

        $key = 'register|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many registration attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($key);

        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            'user_type'             => 'required|in:volunteer,company',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'user_type'         => $request->user_type,
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->formatUser($user),
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->where('name', 'mobile_token')->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $this->formatUser($request->user())]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $key = Str::transliterate(Str::lower($request->email) . '|forgot-password|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($key);

        $status = Password::sendResetLink($request->only('email'));

        return response()->json([
            'message' => $status === Password::RESET_LINK_SENT
                ? 'Password reset link sent to your email.'
                : 'Unable to send reset link.',
        ], $status === Password::RESET_LINK_SENT ? 200 : 422);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $key = 'reset-password|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($key);

        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                $user->tokens()->delete();
            }
        );

        return response()->json([
            'message' => $status === Password::PASSWORD_RESET
                ? 'Password reset successfully.'
                : 'Invalid token or email.',
        ], $status === Password::PASSWORD_RESET ? 200 : 422);
    }

    private function formatUser(User $user): array
    {
        $user->load(['volunteer.skills', 'company']);

        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'user_type'  => $user->user_type,
            'is_active'  => $user->is_active,
            'created_at' => $user->created_at,
            'volunteer'  => $user->volunteer ? [
                'id'                      => $user->volunteer->id,
                'profile_picture'         => $user->volunteer->profile_picture
                    ? asset('storage/' . $user->volunteer->profile_picture)
                    : null,
                'field'                   => $user->volunteer->field,
                'experience_level'        => $user->volunteer->experience_level,
                'years_of_experience'     => $user->volunteer->years_of_experience,
                'bio'                     => $user->volunteer->bio,
                'reputation_score'        => $user->volunteer->reputation_score,
                'trust_score'             => $user->volunteer->trust_score,
                'ai_analysis_status'      => $user->volunteer->ai_analysis_status,
                'general_nda_signed'      => $user->volunteer->general_nda_signed,
                'expert_available'        => $user->volunteer->expert_available,
                'total_tasks_completed'   => $user->volunteer->total_tasks_completed,
                'total_hours_contributed' => $user->volunteer->total_hours_contributed,
                'skills'                  => $user->volunteer->skills->map(fn($s) => [
                    'id'                => $s->id,
                    'skill_name'        => $s->skill_name,
                    'proficiency_level' => $s->proficiency_level,
                ])->values(),
            ] : null,
            'company' => $user->company ? [
                'id'                           => $user->company->id,
                'name'                         => $user->company->name,
                'logo'                         => $user->company->logo
                    ? asset('storage/' . $user->company->logo)
                    : null,
                'industry'                     => $user->company->industry,
                'total_challenges_submitted'   => $user->company->total_challenges_submitted,
            ] : null,
        ];
    }
}
