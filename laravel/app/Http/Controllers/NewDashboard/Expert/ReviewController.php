<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequestRequest;
use App\Models\Review;
use App\Models\ReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $review = Review::query()
            ->where('id', $id)
            ->where('expert_id', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'message' => 'Review not found or you are not authorized to edit this review.',
            ], 404);
        }

        $validated = $request->validate([
            'expert_response' => 'nullable|string',
        ]);

        $review->fill($validated);
        $review->save();

        return response()->json([
            'message' => 'Review updated successfully.',
            'review' => $review
        ]);
    }

    /**
     * Store a newly created review request in storage.
     */
    public function store(StoreReviewRequestRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $message = str_replace(
                ['[Client Name]', '[Project Name]'],
                [$request->client_full_name, $request->project_name],
                $request->message
            );
            $reviewRequest = ReviewRequest::create([
                'expert_id' => $request->expert_id,
                'client_full_name' => $request->client_full_name,
                'client_email' => $request->client_email,
                'client_company_name' => $request->client_company_name,
                'client_company_website' => $request->client_company_website,
                'project_id' => $request->project_id,
                'hired_on_shopexperts' => $request->hired_on_shopexperts ?? false,
                'repeated_client' => $request->repeated_client ?? false,
                'is_client_reviewed' => $request->is_client_reviewed ?? false,
                'project_value_range' => $request->project_value_range,
                'message' => $message,
            ]);

            DB::commit();

            // Log the successful creation
            Log::info('Review request created successfully', [
                'review_request_id' => $reviewRequest->id,
                'expert_id' => $reviewRequest->expert_id,
                'client_name' => $reviewRequest->client_full_name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review request created successfully.',
                'data' => [
                    'id' => $reviewRequest->id,
                    'expert_id' => $reviewRequest->expert_id,
                    'client_full_name' => $reviewRequest->client_full_name,
                    'client_company_name' => $reviewRequest->client_company_name,
                    'project_id' => $reviewRequest->project_id,
                    'hired_on_shopexperts' => $reviewRequest->hired_on_shopexperts,
                    'repeated_client' => $reviewRequest->repeated_client,
                    'is_client_reviewed' => $reviewRequest->is_client_reviewed,
                    'project_value_range' => $reviewRequest->project_value_range,
                    'message' => $reviewRequest->message,
                    'created_at' => $reviewRequest->created_at,
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Failed to create review request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create review request. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
