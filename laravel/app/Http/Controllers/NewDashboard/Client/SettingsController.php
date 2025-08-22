<?php

namespace App\Http\Controllers\NewDashboard\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateClientProfileRequest;
use App\Services\ClientProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ApiErrorException;

class SettingsController extends Controller
{
    /**
     * @param ClientProfileService $clientProfileService
     */
    public function __construct(
        private ClientProfileService $clientProfileService
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user()->load('savedCards');

        return response()->json(['user' => $user]);
    }

    /**
     * Update the authenticated user's profile
     * @param UpdateClientProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateClientProfileRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $updatedUser = $this->clientProfileService->updateProfile($user, $request->validated());

            $updatedUser->load('savedCards');

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $updatedUser
            ]);

        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred'
            ], 500);
        }
    }
}
