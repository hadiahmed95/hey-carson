<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOfferRequest extends FormRequest
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
        return [
            'type'        => 'required|in:scope,offer',
            'hours'       => 'required|integer|min:1',
            'rate'        => 'required|numeric|min:1',
            'deadline'    => 'required|date|after_or_equal:' . date('Y-m-d'),
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
            'type.required'              => 'The type field is required.',
            'type.in'                    => 'The type must be either offer or scope.',
            'hours.required'             => 'The hours field is required.',
            'hours.integer'              => 'The hours field must be an integer.',
            'hours.min'                  => 'The hours field must be at least 1.',
            'rate.required'              => 'The hourly rate field is required.',
            'rate.numeric'               => 'The hourly rate field must be a number.',
            'rate.min'                   => 'The hourly rate field must be at least 1.',
            'deadline.required'          => 'The deadline field is required.',
            'deadline.date'              => 'The deadline must be a valid date.',
            'deadline.after_or_equal'    => 'The deadline must be the current date or the date after now.',
        ];
    }
}
