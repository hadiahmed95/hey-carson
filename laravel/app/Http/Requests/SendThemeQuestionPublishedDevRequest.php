<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendThemeQuestionPublishedDevRequest extends FormRequest
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
            'email'             => 'required|email',
            'name'              => 'required|string|max:255',
            'url'               => 'required|url',
            'theme_name'        => 'required|string|max:255',
            'partner_dash_url'  => 'required|url',
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
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot be longer than 255 characters',
            'url.required' => 'URL is required',
            'url.url' => 'The URL must be a valid URL',
            'theme_name.required' => 'Theme name is required',
            'theme_name.string' => 'Theme name must be a string',
            'theme_name.max' => 'Theme name cannot be longer than 255 characters',
            'partner_dash_url.required' => 'Partner dashboard URL is required',
            'partner_dash_url.url' => 'Partner dashboard URL must be a valid URL',
        ];
    }
}
