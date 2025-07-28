<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendConversionPendingRequest extends FormRequest
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
            'partner_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
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
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'partner_name.required' => 'Partner name is required.',
            'partner_name.string' => 'Partner name must be a string.',
            'partner_name.max' => 'Partner name cannot exceed 255 characters.',
            'customer_name.required' => 'Customer name is required.',
            'customer_name.string' => 'Customer name must be a string.',
            'customer_name.max' => 'Customer name cannot exceed 255 characters.',
        ];
    }
}
