<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendAppQuestionPublishedAuthorRequest extends FormRequest
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
            'name'  => 'required|string|max:255',
            'url'   => 'required|url',
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
            'email.required'    => 'Email is required',
            'email.email'       => 'Email must be a valid email address',
            'email.max'         => 'Email cannot exceed 255 characters',
            'name.required'     => 'Name is required',
            'name.string'       => 'Name must be a string',
            'name.max'          => 'Name cannot exceed 255 characters',
            'url.required'      => 'URL is required',
            'url.url'           => 'URL must be a valid URL',
        ];
    }
}
