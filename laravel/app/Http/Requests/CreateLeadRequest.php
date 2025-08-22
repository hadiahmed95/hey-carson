<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users can create lead requests
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'store_url'             => 'required|url|max:255',
            'project_name'          => 'required|string|max:255',
            'project_description'   => 'required|string|min:10',
            'preferred_expert_id'   => 'required|exists:users,id',
            'is_urgent'             => 'boolean',
            'send_to_more_experts'  => 'boolean',
            // TODO: Add attachment validation rules when needed
            // 'attachments'        => 'array|max:5',
            // 'attachments.*'      => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'store_url.required'            => 'Store URL is required.',
            'store_url.url'                 => 'Please provide a valid store URL.',
            'store_url.max'                 => 'Store URL cannot exceed 255 characters.',
            'project_name.required'         => 'Project name is required.',
            'project_name.max'              => 'Project name cannot exceed 255 characters.',
            'project_description.required'  => 'Project description is required.',
            'project_description.min'       => 'Project description must be at least 10 characters.',
            'preferred_expert_id.required'  => 'Please select a preferred expert.',
            'preferred_expert_id.exists'    => 'The selected expert does not exist.',
            'is_urgent.boolean'             => 'Urgent flag must be true or false.',
            'send_to_more_experts.boolean'  => 'Send to more experts flag must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'store_url'             => 'store URL',
            'project_name'          => 'project name',
            'project_description'   => 'project description',
            'preferred_expert_id'   => 'preferred expert',
            'is_urgent'             => 'urgent status',
            'send_to_more_experts'  => 'send to more experts',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string boolean values to actual booleans if needed
        $this->merge([
            'is_urgent' => $this->boolean('is_urgent'),
            'send_to_more_experts' => $this->boolean('send_to_more_experts'),
        ]);
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization(): void
    {
        abort(401, 'You must be logged in to create a lead request.');
    }
}
