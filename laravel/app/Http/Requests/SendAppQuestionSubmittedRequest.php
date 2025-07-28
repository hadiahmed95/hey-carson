<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendAppQuestionSubmittedRequest extends FormRequest
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
            'name'      => 'required|string|max:255',
            'app_url'   => 'required|url|max:255',
            'theme_url' => 'required|url|max:255',
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
            'email.required'        => 'Email is required',
            'email.email'           => 'Email must be a valid email address',
            'email.max'             => 'Email cannot exceed 255 characters',
            'name.required'         => 'Name is required',
            'name.string'           => 'Name must be a string',
            'name.max'              => 'Name cannot exceed 255 characters',
            'app_url.required'      => 'App URL is required',
            'app_url.url'           => 'App URL must be a valid URL',
            'app_url.max'           => 'App URL cannot exceed 255 characters',
            'theme_url.required'    => 'Theme URL is required',
            'theme_url.url'         => 'Theme URL must be a valid URL',
            'theme_url.max'         => 'Theme URL cannot exceed 255 characters',
        ];
    }
}
