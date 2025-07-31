<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Hash;

class RequestRepository
{
    /**
     * @param HttpRequest $request
     * @param string $requestType
     * @param $user
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
            $additionalExperts = User::query()->where('role_id', 3)
                ->inRandomOrder()
                ->limit(3)
                ->pluck('id')
                ->toArray();
        }

        $project = Project::query()->create([
            'client_id'             => $user->id,
            'name'                  => $request->project_name,
            'preferred_expert_id'   => $preferred_expert_id ?: $request->preferred_expert_id,
            'url'                   => $request->store_url,
            'description'           => $request->project_description,
            'urgent'                => $request->boolean('is_urgent'),
            'is_additional_experts' => $additionalExperts && $request->boolean('send_to_more_experts'),
            'additional_experts'    => $additionalExperts ?: null,
            'created_at'            => now(),
            'updated_at'            => now(),
            'status_updated_at'     => now(),
        ]);

        Request::query()->create([
            'client_id'  => $user->id,
            'project_id' => $project->id,
            'expert_id'  => $preferred_expert_id ?: $request->preferred_expert_id,
            'type'       => $requestType,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($user) {
            return response()->json([
                'user'     => $user,
                'status'   => true,
                'message'  => 'User Logged In Successfully',
                'token'    => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => "User not created or existed"
            ], 500);
        }
    }
}
