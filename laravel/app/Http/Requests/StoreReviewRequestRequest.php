<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'expert_id' => 'required|integer|exists:users,id,role_id,3',
            'client_full_name' => 'required|string|max:255',
            'client_email' => [
                'required',
                'email',
                'max:255',
                'exists:users,email,role_id,2'
            ],
            'client_company_name' => 'nullable|string|max:255',
            'client_company_website' => 'nullable|url|max:255',
            'project_id' => 'required|integer',
            'project_name' => 'nullable|string|max:255',
            'hired_on_shopexperts' => 'boolean',
            'repeated_client' => 'boolean',
            'is_client_reviewed' => 'boolean',
            'project_value_range' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'expert_id.required' => 'Expert ID is required.',
            'expert_id.exists' => 'The selected expert does not exist.',
            'client_full_name.required' => 'Client full name is required.',
            'client_full_name.max' => 'Client full name cannot exceed 255 characters.',
            'client_email.required' => 'Client email is required.',
            'client_email.email' => 'Please provide a valid email address.',
            'client_email.exists' => 'The client email must belong to a registered user with client role.',
            'client_company_website.url' => 'Please provide a valid website URL.',
            'project_id.required' => 'Project ID is required.',
            'project_value_range.in' => 'Please select a valid project value range.',
            'message.required' => 'Review message is required.',
            'message.min' => 'Review message must be at least 10 characters long.',
            'message.max' => 'Review message cannot exceed 5000 characters.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'expert_id' => 'expert',
            'client_full_name' => 'client name',
            'client_email' => 'client email',
            'client_company_name' => 'company name',
            'client_company_website' => 'company website',
            'project_id' => 'project',
            'hired_on_shopexperts' => 'hired on ShopExperts',
            'repeated_client' => 'repeat client status',
            'is_client_reviewed' => 'client review status',
            'project_value_range' => 'project value range',
            'message' => 'review message',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'hired_on_shopexperts' => $this->boolean('hired_on_shopexperts'),
            'repeated_client' => $this->boolean('repeated_client'),
            'is_client_reviewed' => $this->boolean('is_client_reviewed'),
        ]);
    }
}
