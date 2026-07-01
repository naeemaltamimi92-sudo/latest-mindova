<?php

namespace App\Http\Requests\Feedback;

use App\Models\FeedbackItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeedbackItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'required|string|min:20|max:3000',
            'type' => ['required', 'string', Rule::in(FeedbackItem::TYPES)],
            'category' => 'nullable|string|max:100',
        ];
    }
}
