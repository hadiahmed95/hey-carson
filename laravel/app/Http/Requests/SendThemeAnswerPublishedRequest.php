<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendThemeAnswerPublishedRequest extends FormRequest
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
            'email'  => 'required|email',
            'name'   => 'required|string|max:255',
            'url'    => 'required|url',
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
            'email.required'    => 'email is required',
            'email.email'       => 'email must be a valid email address',
            'name.required'     => 'name is required',
            'name.string'       => 'name must be a string',
            'name.max'          => 'name cannot be longer than 255 characters',
            'url.required'      => 'url is required',
            'url.url'           => 'url must be a valid URL',
        ];
    }
}
