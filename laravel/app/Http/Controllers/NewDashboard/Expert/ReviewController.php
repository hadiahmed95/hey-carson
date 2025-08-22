<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewWithUserAndProjectRequest;
use App\Models\Project;
use App\Models\Review;
use App\Models\ReviewRequest;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReviewController extends Controller
{
    protected UserRepository $userRepository;
    protected ProjectRepository $projectRepository;

    public function __construct(UserRepository $userRepository, ProjectRepository $projectRepository)
    {
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
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
     * Store a newly created review request with user and project creation if needed.
     */
    public function store(StoreReviewWithUserAndProjectRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = $this->handleUserCreation($request);

            $project = $this->handleProjectCreation($request, $user->id);

            $reviewRequest = $this->createReviewRequest($request, $user, $project);

            DB::commit();

            // Log the successful creation
            Log::info('Review request created successfully', [
                'review_request_id' => $reviewRequest->id,
                'expert_id' => $reviewRequest->expert_id,
                'client_name' => $reviewRequest->client_full_name,
                'user_id' => $user->id,
                'project_id' => $project->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review request created successfully.',
                'data' => [
                    'review_request' => [
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
                    ],
                    'user' => $this->userRepository->formatUserData($user),
                    'project' => $this->projectRepository->formatProjectData($project),
                ]
            ], ResponseAlias::HTTP_CREATED);

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
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle user creation or retrieval
     */
    private function handleUserCreation(StoreReviewWithUserAndProjectRequest $request): User
    {
        $existingUser = $this->userRepository->findByEmail($request->client_email);

        if ($existingUser) {
            return $existingUser;
        }

        $userData = [
            'full_name' => $request->client_full_name,
            'email' => $request->client_email,
            'company_name' => $request->client_company_name,
            'website' => $request->client_company_website,
            'is_hired_on_shopexperts' => $request->hired_on_shopexperts ?? true,
        ];

        return $this->userRepository->createUserForReview($userData);
    }

    /**
     * Handle project creation or retrieval
     */
    private function handleProjectCreation(StoreReviewWithUserAndProjectRequest $request, int $userId): ?Project
    {
        // If project_id is provided and exists, use existing project
        if ($request->project_id && $this->projectRepository->exists($request->project_id)) {
            return $this->projectRepository->findById($request->project_id);
        }

        $projectData = [
            'name' => $request->project_name,
            'client_id' => $userId,
            'description' => '',
            'status' => Project::FOR_REVIEWS,
        ];

        return $this->projectRepository->createProjectForReviews($projectData);
    }

    /**
     * Create review request
     */
    private function createReviewRequest(StoreReviewWithUserAndProjectRequest $request, $user, $project)
    {
        // Replace placeholders in message
        $message = str_replace(
            ['[Client Name]', '[Project Name]'],
            [$request->client_full_name, $request->project_name],
            $request->message
        );

        return ReviewRequest::create([
            'expert_id' => $request->expert_id,
            'client_full_name' => $request->client_full_name,
            'client_email' => $request->client_email,
            'client_company_name' => $request->client_company_name,
            'client_company_website' => $request->client_company_website,
            'project_id' => $project->id,
            'hired_on_shopexperts' => $request->hired_on_shopexperts ?? false,
            'repeated_client' => $request->repeated_client ?? false,
            'is_client_reviewed' => $request->is_client_reviewed ?? false,
            'project_value_range' => $request->project_value_range,
            'message' => $message,
        ]);
    }

}
