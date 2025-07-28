<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendAppQuestionPublishedDevRequest extends FormRequest
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
            'app_name'          => 'required|string|max:255',
            'partner_dash_url'  => 'required|url',
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
            'email.required'            => 'Email is required',
            'email.email'               => 'Email must be a valid email address',
            'name.required'             => 'Name is required',
            'name.string'               => 'Name must be a string',
            'name.max'                  => 'Name cannot exceed 255 characters',
            'url.required'              => 'URL is required',
            'url.url'                   => 'URL must be a valid URL',
            'app_name.required'         => 'App name is required',
            'app_name.string'           => 'App name must be a string',
            'app_name.max'              => 'App name cannot exceed 255 characters',
            'partner_dash_url.required' => 'Partner dashboard URL is required',
            'partner_dash_url.url'      => 'Partner dashboard URL must be a valid URL',
        ];
    }
}
