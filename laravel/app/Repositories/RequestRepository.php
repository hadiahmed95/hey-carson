<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Request as LeadRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class RequestRepository
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {}

    /**
     *
     * @param HttpRequest $request
     * @param string $requestType
     * @param null $user
     * @return JsonResponse
     */
    public function createRequest(HttpRequest $request, string $requestType, $user = null): JsonResponse
    {
        //Todo: Has to validate the expert here(if it has invalid slug then throw appropriate error on the frontend)
        $preferred_expert_id = null;
        if($request->has('expert_slug')) {
            $preferred_expert = User::query()->where('role_id', 3)
                ->where('is_disable', false)
                ->whereRaw("LOWER(CONCAT(first_name, '-', last_name)) LIKE ?", ['%' . $request->expert_slug . '%'])
                ->whereHas('profile', function($query) {
                    $query->where('status', 'active');
                })
                ->first();


            if (!$preferred_expert) {
                return response()->json([
                    'errors' => [
                        'expert_slug' => ['The expert slug in the url is invalid or not available.']
                    ]
                ], 422);
            }

            $preferred_expert_id = $preferred_expert->id;
        }

        if (!$user) {
            $user = User::query()->create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'url'           => $request->store_url,
                'password'      => Hash::make($request->password),
                'shopify_plan'  => $request->shopify_plan,
                'role_id'       => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $additionalExperts = null;

        if ($request->has('send_to_more_experts') && $request->boolean('send_to_more_experts')) {
            $additionalExperts = User::query()->where('role_id', Role::EXPERT)
                ->where('usertype', User::PAID)
                ->inRandomOrder()
                ->limit(3)
                ->pluck('id')
                ->toArray();
        }

        $project = Project::create([
            'client_id'             => $user->id,
            'name'                  => $request->project_name,
            'preferred_expert_id'   => $preferred_expert_id ?: $request->preferred_expert_id,
            'url'                   => $request->store_url,
            'description'           => $request->project_description,
            'urgent'                => $request->boolean('is_urgent'),
            'is_additional_experts' => $additionalExperts && $request->boolean('send_to_more_experts'),
            'additional_experts'    => $additionalExperts ?: null,
            'status'                => $requestType === LeadRequest::MATCHED ? Project::MATCHED : Project::PENDING_MATCH,
            'created_at'            => now(),
            'updated_at'            => now(),
            'status_updated_at'     => now(),
        ]);

        LeadRequest::query()->create([
            'client_id'  => $user->id,
            'project_id' => $project->id,
            'expert_id'  => $preferred_expert_id ?: $request->preferred_expert_id,
            'type'       => $requestType,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!$project->additional_experts) {
            $this->projectRepository->assignPreferred($project, $project->preferred_expert_id);
        }

        if ($user) {
            return response()->json([
                'user'     => $user,
                'status'   => true,
                'message'  => 'Request created Successfully',
                'token'    => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => "User not created or existed"
            ], 500);
        }
    }

    /**
     * Get project requests for the logged-in client.
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getRequestsForClient(?int $limit = null): Collection
    {
        $user = \Auth::user();

        $leadRequests = LeadRequest::query()
            ->where('client_id', $user->id)
            ->with(['project.activeAssignment.offers', 'expert.profile', 'expert.reviews'])
            ->latest()
            ->when($limit, fn($q) => $q->take($limit))
            ->get();


        $leadRequests->each(function ($request) {
            $expert = $request->expert;

            if ($expert && $expert->relationLoaded('reviews')) {
                $reviews = $expert->reviews;

                $expert->reviews_stat = [
                    'rating' => round($reviews->avg('rate') ?? 0, 2),
                    'reviews_count' => $reviews->count(),
                ];
            }

            if ($request->type === LeadRequest::QUOTE_REQUEST && $expert && $request->project->status === Project::PENDING_MATCH) {
                $expert->load(['quotes' => function ($q) use ($request) {
                    $q->where('project_id', $request->project_id);
                }]);
            }
        });

        $expertIds = $leadRequests->pluck('project')
            ->filter()
            ->flatMap(fn($project) => is_array($project->additional_experts) ? $project->additional_experts : [])
            ->unique()
            ->values();

        $additionalExpertProfiles = User::query()
            ->whereIn('id', $expertIds)
            ->with(['profile', 'reviews'])
            ->get()
            ->map(function ($expert) {
                $reviews = $expert->reviews;

                $expert->reviews_stat = [
                    'rating' => round($reviews->avg('rate') ?? 0, 2),
                    'reviews_count' => $reviews->count(),
                ];

                return $expert;
            })
            ->keyBy('id');

        $leadRequests->each(function ($request) use ($additionalExpertProfiles) {
            $project = $request->project;

            if ($project && is_array($project->additional_experts) && $project->status === Project::PENDING_MATCH) {
                $project->additional_expert_profiles = collect($project->additional_experts)
                    ->map(function ($id) use ($additionalExpertProfiles, $request) {
                        $expert = $additionalExpertProfiles->get($id);

                        if ($expert && $request->type === LeadRequest::QUOTE_REQUEST) {
                            $expert->load(['quotes' => function ($q) use ($request) {
                                $q->where('project_id', $request->project_id);
                            }]);
                        }

                        return $expert;
                    })
                    ->filter()
                    ->values();
            }
        });

        return $leadRequests;
    }

    /**
     * @param int $requestId
     * @return LeadRequest|null
     */
    public function getClientRequestWithQuotes(int $requestId): LeadRequest|null
    {
        $leadRequest = LeadRequest::where('id', $requestId)
            ->with([
                'project.activeAssignment.offers',
                'project.invoices',
                'project.history',
                'expert.profile',
                'expert.reviews'
            ])
            ->first();

        if (!$leadRequest) {
            return null;
        }

        $expert = $leadRequest->expert;

        if ($expert && $expert->relationLoaded('reviews')) {
            $reviews = $expert->reviews;

            $expert->reviews_stat = [
                'rating' => round($reviews->avg('rate') ?? 0, 2),
                'reviews_count' => $reviews->count(),
            ];
        }

        if ($leadRequest->type === LeadRequest::QUOTE_REQUEST && $expert && $leadRequest->project->status === Project::PENDING_MATCH) {
            $expert->load(['quotes' => function ($q) use ($leadRequest) {
                $q->where('project_id', $leadRequest->project_id);
            }]);
        }

        return $leadRequest;
    }
}
