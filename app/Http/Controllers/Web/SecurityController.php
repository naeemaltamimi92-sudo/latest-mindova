<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecurityController extends Controller
{
    public function __construct(private readonly TwoFactorAuthService $twoFactor) {}

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $hadPassword = $user->hasPassword();
        $isAjax = $request->expectsJson();

        // Build validation rules based on whether user has a password
        $rules = [
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];

        $messages = [
            'password.required' => __('New password is required.'),
            'password.confirmed' => __('Password confirmation does not match.'),
            'password.min' => __('Password must be at least 8 characters.'),
        ];

        // Only require current password if user already has one (not OAuth-only users)
        if ($hadPassword) {
            $rules['current_password'] = ['required', 'string'];
            $messages['current_password.required'] = __('Current password is required.');
        }

        try {
            $validated = $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // Verify current password only if user has one
        if ($hadPassword) {
            if (!Hash::check($validated['current_password'] ?? '', $user->password)) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => __('The current password is incorrect.'),
                    ], 400);
                }
                return back()->withErrors(['current_password' => __('The current password is incorrect.')]);
            }
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        $successMessage = $hadPassword ? __('Password changed successfully.') : __('Password set successfully.');

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $successMessage);
    }

    /**
     * Enable Two-Factor Authentication - Step 1: Generate secret and QR code.
     */
    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();

        // Generate secret key
        $secretKey = $this->twoFactor->generateSecretKey();

        // Store secret temporarily in session (not in DB until confirmed)
        session(['2fa_secret' => $secretKey]);

        return response()->json([
            'success' => true,
            'secret' => $secretKey,
            'qr_code' => $this->twoFactor->generateQrCodeSvg($user, $secretKey),
        ]);
    }

    /**
     * Confirm Two-Factor Authentication - Step 2: Verify code and save.
     */
    public function confirmTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();
        $secret = session('2fa_secret');

        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => __('2FA setup session expired. Please try again.'),
            ], 400);
        }

        if (!$this->twoFactor->verifyCode($secret, $request->code)) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid verification code. Please try again.'),
            ], 400);
        }

        // Generate recovery codes and save 2FA settings
        $recoveryCodes = $this->twoFactor->generateRecoveryCodes();
        $this->twoFactor->enableForUser($user, $secret, $recoveryCodes);

        // Clear session
        session()->forget('2fa_secret');

        return response()->json([
            'success' => true,
            'message' => __('Two-Factor Authentication enabled successfully.'),
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    /**
     * Disable Two-Factor Authentication.
     */
    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => __('Incorrect password.'),
            ], 400);
        }

        $this->twoFactor->disableForUser($user);

        return response()->json([
            'success' => true,
            'message' => __('Two-Factor Authentication disabled successfully.'),
        ]);
    }

    /**
     * Regenerate recovery codes.
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => __('Incorrect password.'),
            ], 400);
        }

        if (!$user->two_factor_confirmed_at) {
            return response()->json([
                'success' => false,
                'message' => __('Two-Factor Authentication is not enabled.'),
            ], 400);
        }

        $recoveryCodes = $this->twoFactor->regenerateRecoveryCodes($user);

        return response()->json([
            'success' => true,
            'message' => __('Recovery codes regenerated successfully.'),
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    /**
     * Verify 2FA code during login.
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (!$user->two_factor_secret) {
            return response()->json([
                'success' => false,
                'message' => __('Two-Factor Authentication is not enabled.'),
            ], 400);
        }

        $secret = decrypt($user->two_factor_secret);

        // First, try to verify as a TOTP code
        if (strlen($request->code) === 6 && $this->twoFactor->verifyCode($secret, $request->code)) {
            session(['2fa_verified' => true]);
            return response()->json([
                'success' => true,
                'message' => __('Verification successful.'),
            ]);
        }

        // If not a valid TOTP, check if it's a recovery code
        if ($this->twoFactor->consumeRecoveryCodeIfValid($user, $request->code)) {
            session(['2fa_verified' => true]);
            return response()->json([
                'success' => true,
                'message' => __('Verification successful. Recovery code used.'),
                'recovery_code_used' => true,
                'remaining_codes' => count($this->twoFactor->getRecoveryCodes($user)),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Invalid verification code.'),
        ], 400);
    }

    /**
     * Get current 2FA status.
     */
    public function getTwoFactorStatus()
    {
        $user = Auth::user();

        return response()->json([
            'enabled' => (bool) $user->two_factor_confirmed_at,
            'confirmed_at' => $user->two_factor_confirmed_at?->format('F j, Y'),
            'recovery_codes_count' => count($this->twoFactor->getRecoveryCodes($user)),
        ]);
    }
}
