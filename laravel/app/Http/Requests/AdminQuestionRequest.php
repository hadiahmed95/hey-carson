<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status'    => 'required|in:all,created,completed,declined',
            'search'    => 'nullable|string|max:255',
        ];
    }

    /**
     * Customize the validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'status.required' => 'The type field is required.',
            'status.in'       => 'The type field must be all, created, completed, or declined.',
            'search.string'   => 'The search field must be a string.',
            'search.max'      => 'The search field may not be greater than 255 characters.'
        ];
    }
}


