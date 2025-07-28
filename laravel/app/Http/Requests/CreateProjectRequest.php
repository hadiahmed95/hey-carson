<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateProjectRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = Auth::user();

        $rules = [
            'click_id'              => 'nullable|string',
            'title'                 => 'required|string',
            'description'           => 'required|string',
            'urgent'                => 'nullable|boolean',
            'preferred_expert_id'   => 'nullable|numeric',
            'client_id'             => 'nullable|numeric|string',
            'files.*'               => 'nullable|file',
        ];

        if ($user->role_id === Role::CLIENT) {
            $rules['url'] = 'required|string';
        } elseif ($user->role_id === Role::EXPERT) {
            $rules['url'] = 'nullable|string|required_with:client_id';
            $rules['client_name'] = 'nullable|string|required_without:client_id';
            $rules['client_email'] = 'nullable|email|required_without:client_id|required_with:client_name';
        }

        return $rules;
    }

    /**
     * Customize the validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'url.required'                => 'The URL field is required.',
            'url.string'                  => 'The URL field must be a string.',
            'title.required'              => 'The title field is required.',
            'title.string'                => 'The title field must be a string.',
            'description.required'        => 'The description field is required.',
            'client_email.required'        => 'The email field is required.',
            'description.string'          => 'The description field must be a string.',
            'urgent.boolean'              => 'The urgent field must be a boolean value.',
            'preferred_expert_id.numeric' => 'The preferred expert ID must be a numeric value.',
            'files.*.file'                => 'Each file must be a valid file.',
        ];
    }
}
