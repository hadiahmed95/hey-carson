<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendClaimProfileRequest extends FormRequest
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
            'email'     => 'required|email|max:255',
            'company'   => 'required|string|max:255',
            'website'   => 'required',
            'name'      => 'required|string|max:255',
            'developer' => 'required|string|max:255',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'        => 'Email is required.',
            'email.email'           => 'Email must be a valid email address.',
            'email.max'             => 'Email cannot exceed 255 characters.',
            'company.required'      => 'Company name is required.',
            'company.string'        => 'Company name must be a string.',
            'company.max'           => 'Company name cannot exceed 255 characters.',
            'website.required'      => 'Website is required.',
            'name.required'         => 'Name is required.',
            'name.string'           => 'Name must be a string.',
            'name.max'              => 'Name cannot exceed 255 characters.',
            'developer.required'    => 'Developer name is required.',
            'developer.string'      => 'Developer name must be a string.',
            'developer.max'         => 'Developer name cannot exceed 255 characters.',
        ];
    }
}
