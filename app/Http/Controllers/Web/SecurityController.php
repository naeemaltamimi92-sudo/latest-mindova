<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use PragmaRX\Google2FA\Google2FA;

class SecurityController extends Controller
{
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
        $google2fa = new Google2FA();

        // Generate secret key
        $secretKey = $google2fa->generateSecretKey();

        // Store secret temporarily in session (not in DB until confirmed)
        session(['2fa_secret' => $secretKey]);

        // Generate QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secretKey
        );

        // Generate QR code image using inline SVG
        $qrCode = $this->generateQRCodeSvg($qrCodeUrl);

        return response()->json([
            'success' => true,
            'secret' => $secretKey,
            'qr_code' => $qrCode,
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
        $google2fa = new Google2FA();
        $secret = session('2fa_secret');

        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => __('2FA setup session expired. Please try again.'),
            ], 400);
        }

        // Verify the code
        $valid = $google2fa->verifyKey($secret, $request->code);

        if (!$valid) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid verification code. Please try again.'),
            ], 400);
        }

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();

        // Save 2FA settings
        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => now(),
        ]);

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

        // Disable 2FA
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

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

        // Generate new recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ]);

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

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        // First, try to verify as a TOTP code
        if (strlen($request->code) === 6 && $google2fa->verifyKey($secret, $request->code)) {
            session(['2fa_verified' => true]);
            return response()->json([
                'success' => true,
                'message' => __('Verification successful.'),
            ]);
        }

        // If not a valid TOTP, check if it's a recovery code
        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (in_array($request->code, $recoveryCodes)) {
            // Remove used recovery code
            $recoveryCodes = array_values(array_filter($recoveryCodes, fn($code) => $code !== $request->code));

            $user->update([
                'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            ]);

            session(['2fa_verified' => true]);
            return response()->json([
                'success' => true,
                'message' => __('Verification successful. Recovery code used.'),
                'recovery_code_used' => true,
                'remaining_codes' => count($recoveryCodes),
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

        $recoveryCodes = [];
        if ($user->two_factor_recovery_codes) {
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        }

        return response()->json([
            'enabled' => (bool) $user->two_factor_confirmed_at,
            'confirmed_at' => $user->two_factor_confirmed_at?->format('F j, Y'),
            'recovery_codes_count' => count($recoveryCodes),
        ]);
    }

    /**
     * Generate recovery codes.
     */
    private function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = Str::upper(Str::random(4) . '-' . Str::random(4));
        }
        return $codes;
    }

    /**
     * Generate QR code as SVG.
     */
    private function generateQRCodeSvg(string $data): string
    {
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        $writer = new \BaconQrCode\Writer($renderer);

        return $writer->writeString($data);
    }
}
