<?php

namespace App\Http\Controllers\NewDashboard\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\ExpertFundRepository;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class ListingController extends Controller
{
    public function __construct(
        private ExpertFundRepository $expertFundRepository,
        private UserRepository $userRepository
    ) {}

    public function getFilterOptions(): JsonResponse
    {
        try {
            $filterOptions = $this->userRepository->getListingsFilterOptions();
            return response()->json($filterOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'search',
                'status',
                'role',
                'country',
                'city',
                'language',
                'userType',
                'expertType',
                'serviceCategory',
                'shopifyPlan',
                'hourlyRateMin',
                'hourlyRateMax',
                'page',
                'per_page'
            ]);

            $expertsCount = $this->userRepository->getExpertsCount($filters);
            $experts = $this->userRepository->getExperts($filters);

            $experts->getCollection()->transform(function ($expert) {
                $totalEarnings = $this->expertFundRepository->getTotalEarnings($expert->id);
                $expert->totalEarnings = $totalEarnings;

                $expert->services_offered = $expert->serviceCategories ? 
                    $expert->serviceCategories->pluck('name')->toArray() : [];

                $expert->display_name = $expert->first_name . ' ' . $expert->last_name;
                $expert->avatar_url = $expert->photo ? asset('storage/' . $expert->photo) : null;
                $expert->hourly_rate_formatted = '$' . number_format($expert->profile->hourly_rate ?? 0, 2);

                $expert->account_type = $expert->profile->expert_type ?? 'freelancer';
                $expert->company_type = $expert->company_type ?? 'Individual';

                $expert->status_info = [
                    'status' => $expert->profile->status ?? 'pending',
                    'updated_at' => $expert->updated_at ? $expert->updated_at->format('j M, Y') : 'N/A',
                ];

                $expert->stats = [
                    'total_reviews' => $expert->reviews ? $expert->reviews->count() : 0,
                    'average_rating' => $expert->reviews && $expert->reviews->count() > 0 ? 
                        round($expert->reviews->avg('rate'), 1) : 0,
                    'total_projects' => $expert->activeAssignments ? $expert->activeAssignments->count() : 0,
                ];

                return $expert;
            });

            return response()->json([
                'experts' => $experts,
                'experts_count' => $expertsCount
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, User $user): JsonResponse
    {
        try {
            $action = $request->get('action');
            
            if (!in_array($action, ['activate', 'deactivate'])) {
                return response()->json(['error' => 'Invalid action'], 400);
            }

            // Update the status
            $this->userRepository->updateExpertStatus($user->id, $action);
            
            $updatedExpert = User::with(['profile', 'serviceCategories', 'reviews', 'activeAssignments'])
                ->where('id', $user->id)
                ->first();
                
            $totalEarnings = $this->expertFundRepository->getTotalEarnings($updatedExpert->id);
            $updatedExpert->totalEarnings = $totalEarnings;

            $updatedExpert->services_offered = $updatedExpert->serviceCategories ? 
            $updatedExpert->serviceCategories->pluck('name')->toArray() : [];

            $updatedExpert->display_name = $updatedExpert->first_name . ' ' . $updatedExpert->last_name;
            $updatedExpert->avatar_url = $updatedExpert->photo ? asset('storage/' . $updatedExpert->photo) : null;
            $updatedExpert->hourly_rate_formatted = '$' . number_format($updatedExpert->profile->hourly_rate ?? 0, 2);

            $updatedExpert->account_type = $updatedExpert->profile->expert_type ?? 'freelancer';
            $updatedExpert->company_type = $updatedExpert->company_type ?? 'Individual';

            $updatedExpert->status_info = [
                'status' => $action === 'activate' ? 'active' : 'inactive',
                'updated_at' => now()->format('j M, Y'),
            ];

            $updatedExpert->stats = [
                'total_reviews' => $updatedExpert->reviews ? $updatedExpert->reviews->count() : 0,
                'average_rating' => $updatedExpert->reviews && $updatedExpert->reviews->count() > 0 ? 
                    round($updatedExpert->reviews->avg('rate'), 1) : 0,
                'total_projects' => $updatedExpert->activeAssignments ? $updatedExpert->activeAssignments->count() : 0,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'expert' => $updatedExpert
            ]);
            
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
