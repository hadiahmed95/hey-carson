<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendReviewInviteRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'theme_name' => 'required|string|max:255',
            'developer_email' => 'required|email|max:255',
            'developer_name' => 'required|string|max:255',
            'theme_url' => 'required|url|max:255',
            'description' => 'nullable|string|max:1000',
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
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'theme_name.required' => 'Theme name is required.',
            'developer_email.required' => 'Developer email is required.',
            'developer_email.email' => 'Please provide a valid developer email address.',
            'developer_name.required' => 'Developer name is required.',
            'theme_url.required' => 'Theme URL is required.',
            'theme_url.url' => 'Please provide a valid URL for the theme.',
            'description.string' => 'Description must be a string.',
            'description.max' => 'Description may not be greater than 1000 characters.',
        ];
    }
}
