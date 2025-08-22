<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Exceptions\ApiErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateExpertProfileRequest;
use App\Models\ExpertFund;
use App\Models\Payout;
use App\Models\SavedCard;
use App\Services\CacheService;
use App\Services\ExpertProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * @param ExpertProfileService $expertProfileService
     */
    public function __construct(
        private ExpertProfileService $expertProfileService
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $user = Auth::user()->load(['profile', 'savedCards']);

        return response()->json(['user' => $user]);
    }

    /**
     * Update the expert's profile
     * @param UpdateExpertProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateExpertProfileRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $updatedUser = $this->expertProfileService->updateExpertProfile(
                $user,
                $request->validated()
            );

            $updatedUser->load(['profile', 'savedCards']);

            return response()->json([
                'success' => true,
                'message' => $this->getSuccessMessage($request),
                'user' => $updatedUser // Using raw model since you mentioned no resource class
            ]);

        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (\Exception $e) {
            Log::error('Expert profile update failed', [
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

    private function getSuccessMessage($request): string
    {
        if ($request->has('profile')) {
            return 'Profile updated successfully';
        } elseif ($request->has('default_card_id')) {
            return 'Default card updated successfully';
        } elseif ($request->has('remove_card')) {
            return 'Card removed successfully';
        }

        return 'Profile updated successfully';
    }


//    public function update(Request $request)
//    {
//
//        if ($validateUser->fails()) {
//            return response()->json([
//                'status' => false,
//                'message' => 'Validation error',
//                'errors' => $validateUser->errors()
//            ], 401);
//        }
//
//        if (isset($data['profile'])) {
//            $user->profile->update($data['profile']);
//        } elseif (isset($data['default_card_id'])) {
//            $user->savedCards->each(function(SavedCard $savedCard) use ($data) {
//                $savedCard->default = $savedCard->id === $data['default_card_id'];
//
//                $savedCard->save();
//            });
//        } elseif (isset($data['remove_card'])) {
//            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
//
//            $card = SavedCard::query()->find($data['remove_card']);
//
//            $stripe->paymentMethods->detach(
//                $card->payment_id,
//                []
//            );
//
//            $card->delete();
//        }  else {
//            $user->update($data);
//        }
//
//        $user->refresh();
//
//        return response()->json(['user' => $user->load('profile')]);
//    }

    public function profile()
    {
        $user = Auth::user();
        $user->load([
            'profile',
            'reviews',
            'reviews.client',
            'reviews.project',
            'activeAssignments.project.client',
            'activeAssignments.project.activeAssignment.expert',
            'activeAssignments.project.activeAssignment.expert.profile']);
        return response()->json(['expert' => $user]);
    }
}
