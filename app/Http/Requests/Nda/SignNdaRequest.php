<?php

namespace App\Http\Requests\Nda;

use Illuminate\Foundation\Http\FormRequest;

class SignNdaRequest extends FormRequest
{
    /**
     * Route already requires an authenticated user (auth middleware);
     * no additional authorization is needed to sign.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'agree' => 'required|accepted',
        ];
    }
}
