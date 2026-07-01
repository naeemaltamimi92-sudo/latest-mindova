<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthService
{
    private Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new TOTP secret key.
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Build the QR code (as inline SVG) a user scans to link their
     * authenticator app to the given secret.
     */
    public function generateQrCodeSvg(User $user, string $secret): string
    {
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        return (new \BaconQrCode\Writer($renderer))->writeString($qrCodeUrl);
    }

    /**
     * Verify a 6-digit TOTP code against a secret.
     */
    public function verifyCode(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Generate a fresh batch of one-time recovery codes.
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = Str::upper(Str::random(4) . '-' . Str::random(4));
        }
        return $codes;
    }

    /**
     * Persist a confirmed 2FA secret + recovery codes to the user.
     */
    public function enableForUser(User $user, string $secret, array $recoveryCodes): void
    {
        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Disable 2FA for a user.
     */
    public function disableForUser(User $user): void
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }

    /**
     * Replace a user's recovery codes with a freshly generated batch.
     */
    public function regenerateRecoveryCodes(User $user): array
    {
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ]);

        return $recoveryCodes;
    }

    /**
     * Get the user's currently valid recovery codes.
     */
    public function getRecoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
    }

    /**
     * Check a submitted recovery code and, if valid, consume it (remove it
     * from the user's remaining codes so it can't be reused).
     */
    public function consumeRecoveryCodeIfValid(User $user, string $code): bool
    {
        $recoveryCodes = $this->getRecoveryCodes($user);

        if (!in_array($code, $recoveryCodes, true)) {
            return false;
        }

        $remaining = array_values(array_filter($recoveryCodes, fn ($c) => $c !== $code));

        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($remaining)),
        ]);

        return true;
    }
}
