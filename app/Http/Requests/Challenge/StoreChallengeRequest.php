<?php

namespace App\Http\Requests\Challenge;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreChallengeRequest extends FormRequest
{
    public const PUBLISH_COST = 20;

    /**
     * Determine if the user is authorized to submit a challenge.
     *
     * Throws directly (rather than returning false) so the exact
     * pre-existing redirect + flash message is preserved per failure
     * reason, matching the controller's prior inline behavior.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user->isCompany()) {
            throw new HttpResponseException(
                redirect()->route('dashboard')->with('error', 'Only companies can submit challenges')
            );
        }

        if (!$user->canAfford(self::PUBLISH_COST)) {
            throw new HttpResponseException(
                redirect()->route('challenges.create')->with(
                    'error',
                    "You need " . self::PUBLISH_COST . " credits to publish a challenge. Your balance: {$user->credits} credits. Purchase credits to continue."
                )
            );
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100|max:5000',
        ];
    }
}
