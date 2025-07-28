<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SavedCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function show(Request $request)
    {
        /** @var User $user */
        $user = Auth::user()->load('savedCards');

        return response()->json(['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $validateUser = Validator::make($data, [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,'.$user->id.',id',
            'url' => 'sometimes|required|string',
            'company_type' => 'sometimes|required|string',
            'shopify_plan' => 'sometimes|required|string',
            'project_notifications' => 'sometimes|required|string|in:instant,daily',
            'new_messages' => 'sometimes|required|string|in:instant,daily',
            'timezone' => 'sometimes|required|string',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (isset($data['default_card_id'])) {
            $user->savedCards->each(function(SavedCard $savedCard) use ($data) {
                $savedCard->default = $savedCard->id === $data['default_card_id'];

                $savedCard->save();
            });
        } elseif (isset($data['remove_card'])) {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

            $card = SavedCard::query()->find($data['remove_card']);

            $stripe->paymentMethods->detach(
                $card->payment_id,
                []
            );

            $card->delete();
        } else {
            $user->update($data);
        }

        $user->refresh();

        return response()->json(['user' => $user]);
    }

    public function transactions()
    {
        $user = Auth::user();

        $transactions = Payment::query()->where('user_id', $user->id)
            ->whereIn('status', ['succeeded','prepaid'])->with(['project', 'offer'])
            ->latest('created_at')->paginate(15);

        return response()->json(['transactions' => $transactions]);
    }
}
