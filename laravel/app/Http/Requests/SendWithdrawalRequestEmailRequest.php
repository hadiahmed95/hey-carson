<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendWithdrawalRequestEmailRequest extends FormRequest
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
            'partner_name' => 'required|string|max:255',
            'amount' => 'required',
            'commission' => 'required',
            'date' => 'required',
            'paypal_email' => 'nullable|email|max:255',
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
            'partner_name.required' => 'Partner name is required.',
            'amount.required' => 'Amount is required.',
            'commission.required' => 'Commission is required.',
            'date.required' => 'Date is required.',
            'paypal_email.email' => 'Paypal email must be a valid email address.',
            'paypal_email.max' => 'Paypal email cannot be longer than 255 characters.',
        ];
    }
}
