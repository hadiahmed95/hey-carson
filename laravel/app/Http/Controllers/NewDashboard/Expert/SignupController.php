<?php

namespace App\Http\Controllers\NewDashboard\Expert;

use App\Events\CacheInvalidation;
use App\Events\SendEmail;
use App\Mail\Client\RegisterClientAndProjectMail;
use App\Mail\RegisterMail;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SignupController
{
    public function register(Request $request, ProjectRepository $projectRepository): JsonResponse
    {
        try {
            $data = $request->all();

            $validateUser = Validator::make($data, [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'url' => 'nullable|string',
                'country' => 'required|string',
                'about' => 'nullable|string',
                'role' => 'required|string',
                'company_type' => 'required|string',
                'shopify_plan' => 'nullable|string',
                'partner_link_directory' => 'nullable|string',
                'linkedIn_url' => 'required|url',
                'min_project_budget' => 'required|string',
                'services' => 'required|array|min:1',
                'services.*' => 'string'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please ensure all form fields are filled correctly before proceeding.',
                    'errors' => $validateUser->errors()
                ], 200);
            }

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt('dummy-password'), // You may want to update this
                'url' => $data['url'] ?? null,
                'company_type' => $data['company_type'],
                'shopify_plan' => $data['shopify_plan'] ?? null,
                'role_id' => 3,
            ]);

            $user->profile()->create([
                'role' => $data['role'],
                'country' => $data['country'],
                'url' => $data['url'] ?? "",
                'about' => $data['about'] ?? "",
                'partner_link_directory' => $data['partner_link_directory'] ?? null,
                'linkedIn_url' => $data['linkedIn_url'],
                'min_project_budget' => $data['min_project_budget'],
                'experience' => '',
                'availability' => '',
                'eng_level' => '',
            ]);

            $serviceIds = ServiceCategory::whereIn('name', $data['services'])->pluck('id')->toArray();
            $user->serviceCategories()->sync($serviceIds);

            SendEmail::dispatch($user, new RegisterMail($user));

            return response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'User Created Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
