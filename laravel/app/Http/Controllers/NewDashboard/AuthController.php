<?php

namespace App\Http\Controllers\NewDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\ExpertOnlineStatus;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password are required to continue.',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match our records.',
                ], 401);
            }

            $user = User::where('email', $request->email)
                        ->with('role', 'profile', 'serviceCategories')
                        ->first();

            return response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function loginAsUser(Request $request, User $user): JsonResponse {
        try {
            // First, logout the current authenticated user
            $currentUser = Auth::user();
            if ($currentUser) {
                Auth::logout();
            }

            Auth::login($user);

            // Load necessary relationships based on user role
            if ($user->role_id == Role::EXPERT) {
                $user->load('serviceCategories');
            }
            $user->load('profile', 'role');

            // Create new token for the target user
            $token = $user->createToken("API TOKEN")->plainTextToken;

            $response = response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'Successfully logged in as user',
                'token' => $token,
            ], 200);

            // Clear any existing cookies
            Cookie::queue(Cookie::forget('shopexperts_session'));

            return $response;

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to login as user: ' . $th->getMessage()
            ], 500);
        }
    }
}
