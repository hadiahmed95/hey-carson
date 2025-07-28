<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendAppReviewPublishedRequest extends FormRequest
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
            'app_name'          => 'required|string|max:255',
            'developer_name'    => 'required|string|max:255',
            'login_url'         => 'required|url|max:255',
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
            'email.required'            => 'Email is required.',
            'email.email'               => 'Please provide a valid email address.',
            'app_name.required'         => 'App name is required.',
            'developer_name.required'   => 'Developer name is required.',
            'login_url.required'        => 'Login URL is required.',
            'login_url.url'             => 'Please provide a valid URL for the login.',
        ];
    }
}
