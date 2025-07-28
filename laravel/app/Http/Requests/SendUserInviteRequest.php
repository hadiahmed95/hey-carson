<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendUserInviteRequest extends FormRequest
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
            'workspace' => 'required|string|max:255',
            'url'       => 'required|url|max:255',
            'inviter'   => 'required|string|max:255',
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
            'email.email'           => 'Please provide a valid email address.',
            'workspace.required'    => 'Workspace is required.',
            'url.required'          => 'URL is required.',
            'url.url'               => 'Please provide a valid URL.',
            'inviter.required'      => 'Inviter is required.',
        ];
    }
}
