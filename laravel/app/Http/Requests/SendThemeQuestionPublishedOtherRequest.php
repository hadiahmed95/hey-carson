<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendThemeQuestionPublishedOtherRequest extends FormRequest
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
            'email'         => 'required|email',
            'name'          => 'required|string|max:255',
            'url'           => 'required|url',
            'theme_name'    => 'required|string|max:255',
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
            'email.email'           => 'The email must be a valid email address',
            'name.required'         => 'Name is required',
            'name.string'           => 'The name must be a valid string',
            'name.max'              => 'Name cannot be longer than 255 characters',
            'url.required'          => 'URL is required',
            'url.url'               => 'The URL must be a valid URL',
            'theme_name.required'   => 'Theme name is required',
            'theme_name.string'     => 'The theme name must be a valid string',
            'theme_name.max'        => 'Theme name cannot be longer than 255 characters',
        ];
    }
}
