<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaveCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'payment_id' => [
                'required',
                'string',
                'regex:/^pm_[a-zA-Z0-9_]+$/', // Stripe payment method ID format
            ],
            'last_digits' => [
                'required',
                'string',
                'digits:4',
            ],
            'card_type' => [
                'required',
                'string',
                'in:visa,mastercard,amex,discover,diners,jcb,unionpay',
            ],
            'exp_date' => [
                'required',
                'string',
                'regex:/^\d{4}\/\d{2}$/', // Format: YYYY/MM
                'date_format:Y/m',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payment_id.required' => 'Payment method ID is required.',
            'payment_id.regex' => 'Invalid payment method ID format.',
            'last_digits.required' => 'Card last 4 digits are required.',
            'last_digits.digits' => 'Last digits must be exactly 4 digits.',
            'card_type.required' => 'Card type is required.',
            'card_type.in' => 'Invalid card type. Supported types: visa, mastercard, amex, discover, diners, jcb, unionpay.',
            'exp_date.required' => 'Expiration date is required.',
            'exp_date.regex' => 'Expiration date must be in YYYY/MM format.',
            'exp_date.date_format' => 'Invalid expiration date format.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate expiration date is not in the past
            if ($this->has('exp_date')) {
                $expDate = $this->input('exp_date');
                $currentDate = now()->format('Y/m');

                if ($expDate < $currentDate) {
                    $validator->errors()->add('exp_date', 'The card has expired.');
                }
            }
        });
    }
}
