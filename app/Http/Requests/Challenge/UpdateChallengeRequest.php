<?php

namespace App\Http\Requests\Challenge;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateChallengeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to update this challenge.
     *
     * Throws directly (rather than returning false) so each failure
     * reason keeps its own JSON-vs-redirect response shape, matching
     * the controller's prior inline behavior exactly.
     */
    public function authorize(): bool
    {
        $challenge = $this->route('challenge');
        $user = $this->user();

        if (!$user->isCompany() || $user->company->id !== $challenge->company_id) {
            $message = 'You do not have permission to edit this challenge.';

            if ($this->expectsJson()) {
                throw new HttpResponseException(
                    response()->json(['success' => false, 'message' => $message], 403)
                );
            }

            abort(403, $message);
        }

        if (in_array($challenge->status, ['completed', 'delivered'])) {
            $message = 'This challenge cannot be edited because it has been completed.';

            throw new HttpResponseException(
                $this->expectsJson()
                    ? response()->json(['success' => false, 'message' => $message], 422)
                    : redirect()->route('challenges.show', $challenge)->with('error', $message)
            );
        }

        if ($challenge->tasks()->whereIn('status', ['in_progress', 'completed'])->exists()) {
            $message = 'This challenge cannot be edited because it has active or completed tasks.';

            throw new HttpResponseException(
                $this->expectsJson()
                    ? response()->json(['success' => false, 'message' => $message], 422)
                    : redirect()->route('challenges.show', $challenge)->with('error', $message)
            );
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100|max:5000',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif,zip,txt,csv',
            'remove_attachments' => 'nullable|string',
        ];
    }
}
