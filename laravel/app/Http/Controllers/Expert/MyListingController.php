<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyListingController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $expert = Auth::user();
        $personalAndBusinessDetails = $expert->with('profile')->get();

        //Todo: Need to fetch packaged_services, customer_stories, and faq

        return response()->json([
            'personal_and_business_details' => $personalAndBusinessDetails,
            'packaged_services'             => [],
            'customer_stories'              => [],
            'faq'                           => [],
        ]);
    }
}
