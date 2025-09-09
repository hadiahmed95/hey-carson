<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewRequestResource;
use App\Http\Resources\WrittenReviewResource;
use App\Models\Review;
use App\Models\ReviewRequest;
use Illuminate\Http\Request;
use App\Repositories\ReviewRequestRepository;
use Illuminate\Http\JsonResponse;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $expert = Auth::user();

        $writtenReviews = Review::query()
            ->where('expert_id', $expert->id)
            ->with(['client', 'expert', 'project'])
            ->get();


        return response()->json([
            'reviews' => WrittenReviewResource::collection($writtenReviews)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'expert_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string',
            'communication' => 'nullable|integer|min:1|max:5',
            'quality' => 'nullable|integer|min:1|max:5',
            'recommendation' => 'nullable|string|max:255',
            'timeToStart' => 'nullable|integer',
            'valueForMoney' => 'nullable|integer',
            'valueRange' => 'nullable|string|max:255',
        ]);

        $clientId = Auth::id();

        $existingReview = Review::query()
            ->where('client_id', $clientId)
            ->where('project_id', $request->project_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already submitted a review for this project.',
            ], 409);
        }


        $review = Review::query()->create([
            'expert_id' => $request->expert_id,
            'client_id' => $clientId,
            'project_id' => $request->project_id,
            'rate' => $request->rate,
            'comment' => $request->comment,
            'communication' => $request->communication,
            'quality' => $request->quality,
            'recommendation' => $request->recommendation,
            'timeToStart' => $request->timeToStart,
            'valueForMoney' => $request->valueForMoney,
            'valueRange' => $request->valueRange,
        ]);

        ReviewRequest::query()
            ->where('expert_id', $request->expert_id)
            ->where('project_id', $request->project_id)
            ->update(['is_client_reviewed' => true]);

        return response()->json([
            'message' => 'Review submitted successfully.',
            'review' => $review
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $review = Review::query()
            ->where('id', $id)
            ->where('client_id', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'message' => 'Review not found or you are not authorized to edit this review.',
            ], 404);
        }

        $validated = $request->validate([
            'rate' => 'nullable|numeric|min:1|max:5',
            'comment' => 'nullable|string',
            'communication' => 'nullable|integer|min:1|max:5',
            'quality' => 'nullable|integer|min:1|max:5',
            'recommendation' => 'nullable|string|max:255',
            'timeToStart' => 'nullable|integer',
            'valueForMoney' => 'nullable|integer',
            'valueRange' => 'nullable|string|max:255',
            'expert_response' => 'nullable|string'
        ]);

        $review->fill($validated);

        $review->is_edited = true;
        $review->save();

        return response()->json([
            'message' => 'Review updated successfully.',
            'review' => $review
        ]);
    }

    /**
     * @param ReviewRequestRepository $repository
     * @return JsonResponse
     */
    public function reviewRequests(ReviewRequestRepository $repository): JsonResponse
    {
        $client = Auth::user();

        $reviewRequests = $repository->getReviewRequests($client->id);

        return response()->json([
            'pending_review_requests' => ReviewRequestResource::collection($reviewRequests),
        ]);
    }
}
