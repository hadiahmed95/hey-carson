<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateClientProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => "sometimes|required|email|unique:users,email,{$userId},id",
            'url' => 'sometimes|required|string|url|max:255',
            'company_type' => 'sometimes|required|string|max:255',
            'shopify_plan' => 'sometimes|required|string|max:255',
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
        ];
    }
}
