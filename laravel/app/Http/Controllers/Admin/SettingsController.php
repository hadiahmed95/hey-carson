<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Models\AdminSetting;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function show(Request $request)
    {
        $type = $request->get('type');

        $setting = AdminSetting::query()->where('type', $type)->first();

        return response()->json([$type => $setting->value ?? true]);
    }

    public function update(Request $request)
    {
        $type = $request->get('type');
        $value = $request->get('value');

        AdminSetting::updateOrCreate(
            ['type' => $type],
            ['value' => $value]
        );

        return response()->json(['message' => 'OK']);
    }

    public function transactions()
    {
        $transactions = Payment::query()
            ->whereIn('status', ['succeeded','prepaid'])->with(['project', 'offer', 'user'])
            ->latest('created_at')->paginate(15);

        return response()->json(['transactions' => $transactions]);
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $validateUser = Validator::make($data, [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,'.$user->id.',id',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (isset($data['profile'])) {
            $user->profile->update($data['profile']);
        } else {
            $user->update($data);
        }

        $user->refresh();

        return response()->json(['user' => $user->load('profile')]);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $password = $request->get('password');

        $user->forceFill([
            'password' => Hash::make($password)
        ])->setRememberToken(Str::random(60));

        $user->save();

        $user->update(['password_changed', now()]);

        return response()->json(['message' => 'OK']);
    }
}
