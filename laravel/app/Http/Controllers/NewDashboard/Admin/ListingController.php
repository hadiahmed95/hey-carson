<?php

namespace App\Http\Controllers\NewDashboard\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminListingRepository;
use App\Repositories\ExpertFundRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class ListingController extends Controller
{
    private ExpertFundRepository $expertFundRepository;
    private AdminListingRepository $adminListingRepository;

    public function __construct(
        ExpertFundRepository $expertFundRepository,
        AdminListingRepository $adminListingRepository
    ) {
        $this->expertFundRepository = $expertFundRepository;
        $this->adminListingRepository = $adminListingRepository;
    }

    public function getFilterOptions(): JsonResponse
    {
        try {
            $filterOptions = $this->adminListingRepository->getFilterOptions();
            return response()->json($filterOptions);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all(Request $request): JsonResponse
    {
        try {
            $expertsCount = $this->adminListingRepository->getExpertsCount($request);
            $experts = $this->adminListingRepository->getExperts($request);

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
}
