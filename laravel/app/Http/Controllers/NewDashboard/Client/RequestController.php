<?php

namespace App\Http\Controllers\NewDashboard\Client;

use App\Http\Controllers\Controller;

use App\Models\Request;
use App\Repositories\RequestRepository;
use Illuminate\Http\Request as HttpRequest;
use App\Repositories\ProjectRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    /**
     * @param ProjectRepository $projectRepo
     * @return JsonResponse
     */
    public function requests(ProjectRepository $projectRepo): JsonResponse
    {
        $requests = $projectRepo->getRequestsForClient();

        return response()->json([
            'requests' => $requests
        ]);
    }

    /**
     * @param HttpRequest $request
     * @param RequestRepository $requestRepository
     * @return JsonResponse
     */
    public function getMatched(HttpRequest $request, RequestRepository $requestRepository): JsonResponse
    {
        //Todo: Need to catch here attachments also
        $validator = Validator::make($request->all(), [
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|email|max:255',
            'password'              => 'required|max:255',
            'store_url'             => 'required|url|max:255',
            'store_name'            => 'required|string|max:255', //Todo: Need to save the store name somewhere
            'shopify_plan'          => 'required|string|max:255',
            'project_name'          => 'required|string|max:255',
            'project_description'   => 'required|string|min:10',
            'is_urgent'             => 'boolean',
            'expert_slug'           => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return $requestRepository->createRequest($request, 'Matched');
    }

    /**
     * @param HttpRequest $request
     * @param RequestRepository $requestRepository
     * @return JsonResponse
     */
    public function freeQuote(HttpRequest $request, RequestRepository $requestRepository): JsonResponse
    {
        //Todo: Need to catch here attachments also
        $validator = Validator::make($request->all(), [
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|email|max:255',
            'password'              => 'required|max:255',
            'store_url'             => 'required|url|max:255',
            'store_name'            => 'required|string|max:255', //Todo: Need to save the store name somewhere
            'shopify_plan'          => 'required|string|max:255',
            'project_name'          => 'required|string|max:255',
            'project_description'   => 'required|string|min:10',
            'preferred_expert_id'   => 'required|exists:users,id',
            'is_urgent'             => 'boolean',
            'send_to_more_experts'  => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return $requestRepository->createRequest($request, 'Quote Request');
    }

    /**
     * @param HttpRequest $request
     * @param RequestRepository $requestRepository
     * @return JsonResponse
     */
    public function create(HttpRequest $request, RequestRepository $requestRepository): JsonResponse
    {
        //Todo: Need to catch here attachments also
        $validator = Validator::make($request->all(), [
            'store_url'             => 'required|url|max:255',
            'project_name'          => 'required|string|max:255',
            'project_description'   => 'required|string|min:10',
            'preferred_expert_id'   => 'required|exists:users,id',
            'is_urgent'             => 'boolean',
            'send_to_more_experts'  => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return $requestRepository->createRequest($request, 'Quote Request', \Auth::user());
    }

    /**
     * @param $requestId
     * @return JsonResponse
     */
    public function request($requestId): JsonResponse
    {
        $request = $this->getClientRequestWithQuotes($requestId);
        return response()->json([
            'request' => $request,
        ]);
    }

    public function getClientRequestWithQuotes(int $requestId)
    {
        $request = Request::query()
            ->where('id', $requestId)
            ->with([
                'project.activeAssignment.offers',
                'project.invoices',
                'project.history',
                'expert.profile',
                'expert.reviews'
            ])
            ->first();

        if (!$request) {
            return null;
        }

        $expert = $request->expert;

        if ($expert && $expert->relationLoaded('reviews')) {
            $reviews = $expert->reviews;

            $expert->reviews_stat = [
                'rating' => round($reviews->avg('rate') ?? 0, 2),
                'reviews_count' => $reviews->count(),
            ];
        }

        if ($request->type === 'Quote Request' && $expert) {
            $expert->load(['quotes' => function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            }]);
        }

        return $request;
    }

}
