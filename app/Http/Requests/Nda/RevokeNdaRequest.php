<?php

namespace App\Http\Requests\Nda;

use Illuminate\Foundation\Http\FormRequest;

class RevokeNdaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function failedAuthorization()
    {
        abort(403, __('Unauthorized'));
    }

    public function rules(): array
    {
        return [
            'reason' => 'required|string|max:1000',
        ];
    }
}
