<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionStatusRequest extends FormRequest
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
            'status'      => 'required|in:completed,declined',
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
            'status.required' => 'The status field is required.',
            'status.in'       => 'The type field must be either completed or declined.',
        ];
    }
}


