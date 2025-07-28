<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateAnswerRequest extends FormRequest
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
            'content'       => 'required|string|min:1',
            'question_id'   => 'required|integer|exists:questions,id',
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
            'content.required'      => 'The content field is required.',
            'content.string'        => 'The content field must be a string.',
            'content.min'           => 'The content field must be at least 1 character.',
            'question_id.required'  => 'The question ID field is required.',
            'question_id.integer'   => 'The question ID must be an integer.',
            'question_id.exists'    => 'The selected question ID does not exist.',
        ];
    }
}
