<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionRequest extends FormRequest
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
            'content' => 'required|string|min:1|max:65535',
        ];
    }


    /**
     * Customize the validation error messages.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'content.required'  => 'The content field is required.',
            'content.string'    => 'The content field must be a string.',
            'content.min'       => 'The content field must be at least 1 character.',
            'content.max'       => 'The content field may not be greater than 65,535 characters.',
        ];
    }
}
