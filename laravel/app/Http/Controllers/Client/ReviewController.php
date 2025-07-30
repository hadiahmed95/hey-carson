<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $client = Auth::user();
        $reviews = $client->clientReviews()
            ->with('expert')
            ->get();

        return response()->json(['reviews' => $reviews]);
    }
}
