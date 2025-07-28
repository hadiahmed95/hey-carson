<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendReviewPublishedRequest extends FormRequest
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
            'email'             => 'required|email|max:255',
            'theme_name'        => 'required|string|max:255',
            'developer_name'    => 'required|string|max:255',
            'login_url'         => 'required|url|max:255',
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
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'theme_name.required' => 'Theme name is required.',
            'developer_name.required' => 'Developer name is required.',
            'login_url.required' => 'Login URL is required.',
            'login_url.url' => 'Login URL must be a valid URL.',
        ];
    }

}
