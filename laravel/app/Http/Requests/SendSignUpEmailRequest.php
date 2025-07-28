<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSignUpEmailRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_url' => 'nullable|url|max:255',
            'program_slug' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'company_name.string' => 'Company name must be a string.',
            'company_url.url' => 'Company URL must be a valid URL.',
        ];
    }
}
