<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreeQuoteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|max:255',
            'store_url'             => 'required|url|max:255',
            'store_name'            => 'required|string|max:255',
            'shopify_plan'          => 'required|string|max:255',
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
            'first_name.required'           => 'First name is required.',
            'first_name.max'                => 'First name cannot exceed 255 characters.',
            'last_name.required'            => 'Last name is required.',
            'last_name.max'                 => 'Last name cannot exceed 255 characters.',
            'email.required'                => 'Email address is required.',
            'email.email'                   => 'Please provide a valid email address.',
            'email.unique'                  => 'This email address is already registered.',
            'password.required'             => 'Password is required.',
            'password.max'                  => 'Password cannot exceed 255 characters.',
            'store_url.required'            => 'Store URL is required.',
            'store_url.url'                 => 'Please provide a valid store URL.',
            'store_url.max'                 => 'Store URL cannot exceed 255 characters.',
            'store_name.required'           => 'Store name is required.',
            'store_name.max'                => 'Store name cannot exceed 255 characters.',
            'shopify_plan.required'         => 'Shopify plan is required.',
            'shopify_plan.max'              => 'Shopify plan cannot exceed 255 characters.',
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
            'first_name'            => 'first name',
            'last_name'             => 'last name',
            'email'                 => 'email address',
            'password'              => 'password',
            'store_url'             => 'store URL',
            'store_name'            => 'store name',
            'shopify_plan'          => 'Shopify plan',
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
}
