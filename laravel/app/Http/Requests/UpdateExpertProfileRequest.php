<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateExpertProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        if ($this->has('profile')) {
            return [
                'profile' => 'required|array',
                'profile.country' => 'sometimes|required|string|max:255',
                'profile.about' => 'sometimes|required|string|max:2000',
                'profile.role' => 'sometimes|required|string|max:255',
                'profile.experience' => 'sometimes|required|string|max:255',
                'profile.availability' => 'sometimes|required|string|max:255',
                'profile.english_level' => 'sometimes|required|string|max:255',
                'profile.hourly_rate' => 'sometimes|required|numeric|min:0|max:999999.99',
                'profile.paypal_email' => 'sometimes|required|email|max:255',
                'profile.wise_email' => 'sometimes|required|email|max:255',
            ];
        }

        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => "sometimes|required|email|unique:users,email,{$userId},id",
            'url' => 'sometimes|required|string|url|max:255',
            'project_notifications' => 'sometimes|required|string|in:instant,daily',
            'new_messages' => 'sometimes|required|string|in:instant,daily',
            'timezone' => 'sometimes|required|string|timezone',
            'default_card_id' => 'sometimes|required|integer|exists:saved_cards,id',
            'remove_card' => 'sometimes|required|integer|exists:saved_cards,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already taken.',
            'url.url' => 'Please provide a valid URL.',
            'timezone.timezone' => 'Please provide a valid timezone.',
            'project_notifications.in' => 'Project notifications must be either instant or daily.',
            'new_messages.in' => 'Message notifications must be either instant or daily.',
            'profile.hourly_rate.numeric' => 'Hourly rate must be a valid number.',
            'profile.hourly_rate.min' => 'Hourly rate cannot be negative.',
            'profile.paypal_email.email' => 'Please provide a valid PayPal email address.',
            'profile.wise_email.email' => 'Please provide a valid Wise email address.',
        ];
    }
}
