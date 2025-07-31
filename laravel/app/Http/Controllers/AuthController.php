<?php

namespace App\Http\Controllers;

use App\Notifications\ResetPasswordLink;
use App\Repositories\RequestRepository;
use App\Events\ExpertOnlineStatus;
use Illuminate\Support\Facades\Cookie;
use App\Events\CacheInvalidation;
use App\Events\SendEmail;
use App\Mail\Client\RegisterClientAndProjectMail;
use App\Mail\RegisterMail;
use App\Models\ClientFund;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request, ProjectRepository $projectRepository): JsonResponse
    {
        try {
            $data = $request->all();

            if (!$data['new_dashboard'] && $recaptchaError = $this->validateRecaptcha($data)) {
                return $recaptchaError;
            }

            if ($data['user_type'] === 'client') {
                if (isset($data['new_project'])) {

                    $customMessages = [
                        'first_name.required' => 'Please type in your first name.',
                        'last_name.required' => 'Please type in your last name.',
                        'email.required' => 'Please type in your email address.',
                        'email.email' => 'Please provide a valid email address.',
                        'email.unique' => 'This email address is already in use.',
                        'password.required' => 'Please type in a password for your account.',
                        'password.min' => 'Please pick a strong password for your account.',
                        'confirm_password.required' => 'Please confirm your password.',
                        'confirm_password.same' => "The inputted passwords don't match.",
                        'url.required' => 'Please type in your online store address.',
                        'country.required' => 'Please select your country.',
                        'about.required' => 'Tell us something about yourself.',
                        'description.required' => 'Please write a brief for your project',
                        'name.required' => 'Please pick a title for your project',
//                        'company_type.required' => 'Please select an option',
                        'shopify_plan.required' => 'Please select an option',
                    ];

                    $validateUser = Validator::make($data['client'], [
                        'click_id'              => 'nullable|string',
                        'partner_id'            => 'nullable|string',
                        'program_id'            => 'nullable|string',
                        'first_name'            => 'required|string',
                        'last_name'             => 'required|string',
                        'url'                   => 'required|string',
                        'title'                 => 'required|string',
                        'description'           => 'required|string',
//                        'company_type'          => 'required|string',
                        'urgent'                => 'nullable|boolean',
                        'preferred_expert_id'   => 'nullable|numeric',
                        'ref_id'                => 'nullable|string',
                        'email'                 => 'required|email|unique:users,email',
                        'password'              => 'required|string|min:8',
                        'confirm_password'      => 'required|string|same:password',
                        'files.*'               => 'nullable|file',
                        'shopify_plan'          => 'required|string',
                        'is_additional_experts'   => 'nullable|boolean',
                        'additional_experts'      => 'nullable|array',
                        'type'                  => 'nullable|string',
                    ], $customMessages);

                    if ($validateUser->fails()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Please ensure all form fields are filled correctly before proceeding.',
                            'errors' => $validateUser->errors()
                        ], 401);
                    }

                    $user = User::create([
                        'click_id'              => $data['click_id'] ?? null,
                        'partner_id'            => $data['partner_id'] ?? null,
                        'source'                => isset($data['partner_id']) ? $data['partner_name'] : 'Website Direct',
                        'program_id'            => $data['program_id'] ?? null,
                        'first_name'            => $data['client']['first_name'],
                        'last_name'             => $data['client']['last_name'],
                        'email'                 => $data['client']['email'],
                        'password'              => Hash::make($data['client']['password']),
                        'url'                   => $data['client']['url'],
                        'company_type'          => $data['client']['company_type'],
                        'shopify_plan'          => $data['client']['shopify_plan'],
                        'role_id'               => 2,
                    ]);

                    $projectData = [
                        'click_id'              => $data['click_id'] ?? null,
                        'title'                 => $data['client']['title'],
                        'description'           => $data['client']['description'],
                        'url'                   => $data['client']['url'],
                        'urgent'                => $data['client']['urgent'] ?? 0,
                        'preferred_expert_id'   => $data['client']['preferred_expert_id'] ?? null,
                        'files'                 => $data['files'] ?? [],
                        'is_additional_experts'   => $data['is_additional_experts'] ?? 0,
                        'additional_experts'      => $data['additional_experts'] ?? null,
                    ];

                    $requestData = [
                        'type' => $data['client']['type'] ?? null,

                    ];

                    CacheInvalidation::dispatch('cache_duration_key', CacheService::CLIENTS_COUNT);

                    $projectRepository->create($projectData, $user);

                    SendEmail::dispatch($user, new RegisterClientAndProjectMail());

                    return response()->json([
                        'user' => $user,
                        'status' => true,
                        'message' => 'User Created Successfully',
                        'token' => $user->createToken("API TOKEN")->plainTextToken,
                    ], 200);
                } else {
                    $validateUser = Validator::make($data['client'], [
                        'first_name'    => 'required|string',
                        'last_name'     => 'required|string',
                        'url'           => 'required|string',
                        'email'         => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                        'password'      => 'required|string|min:8',
                    ]);

                    if ($validateUser->fails()) {
                        $errors = $validateUser->errors()->toArray();
                        $emailError = $errors['email'][0] ?? null;

                        if ($emailError) {
                            return response()->json([
                                'status' => false,
                                'message' => $emailError,
                                'errors' => $validateUser->errors()
                            ], 422);
                        } else {
                            return response()->json([
                                'status' => false,
                                'message' => 'Please ensure all form fields are filled correctly before proceeding.',
                                'errors' => $validateUser->errors()
                            ], 422);
                        }
                    }

                    $user = User::create([
                        'first_name' => $data['client']['first_name'],
                        'last_name' => $data['client']['last_name'],
                        'email' => $data['client']['email'],
                        'password' => Hash::make($data['client']['password']),
                        'url' => $data['client']['url'],
                        'role_id' => 2,
                    ]);

                    CacheInvalidation::dispatch('cache_duration_key', CacheService::CLIENTS_COUNT);

                    SendEmail::dispatch($user, new RegisterMail($user));

                    return response()->json([
                        'user' => $user,
                        'status' => true,
                        'message' => 'User Created Successfully',
                        'token' => $user->createToken("API TOKEN")->plainTextToken,
                    ], 200);
                }
            } elseif ($data['user_type'] === 'expert') {
                $validateUser = Validator::make($data['expert'], [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:8',

                    'url' => 'required|string',

                    'country' => 'required|string',
                    'about' => 'required|string',

                    'role' => 'required|string',
                    'experience' => 'required|string',
                    'availability' => 'required|string',
                    'english_level' => 'required|string',
                    'hourly_rate' => 'required|numeric',
                    'expert_type' => 'nullable|string',
                    'agency_name' => 'nullable|string',
                    'partner_tier' => 'nullable|string',
                    'partner_link_directory' => 'nullable|string',
                    'linkedIn_url' => 'nullable|string',
                    'min_project_budget' => 'nullable|string',
                    'services' => 'nullable|array',
                ]);

                if ($validateUser->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Please ensure all form fields are filled correctly before proceeding.',
                        'errors' => $validateUser->errors()
                    ], 401);
                }

                $user = User::create([
                    'first_name' => $data['expert']['first_name'],
                    'last_name' => $data['expert']['last_name'],
                    'email' => $data['expert']['email'],
                    'password' => $data['expert']['password'],
                    'url' => $data['expert']['url'],
                    'role_id' => 3,
                ]);

                $user->profile()->create([
                    'role' => $data['expert']['role'],
                    'experience' => $data['expert']['experience'],
                    'availability' => $data['expert']['availability'],
                    'eng_level' => $data['expert']['english_level'],
                    'hourly_rate' => $data['expert']['hourly_rate'],
                    'country' => $data['expert']['country'],
                    'url' => $data['expert']['url'],
                    'about' => $data['expert']['about'],
                    'expert_type' => $data['expert']['expert_type'] ?? "freelancer",
                    'agency_name' => $data['expert']['agency_name'] ?? null,
                    'partner_tier' => $data['expert']['partner_tier'] ?? null,
                    'partner_link_directory' => $data['expert']['partner_link_directory'] ?? null,
                    'linkedIn_url' => $data['expert']['linkedIn_url'] ?? null,
                    'min_project_budget' => $data['expert']['min_project_budget'] ?? null,
                ]);

                if (!empty($data['expert']['services'])) {
                    $user->serviceCategories()->sync($data['expert']['services']);
                }

                CacheInvalidation::dispatch('cache_duration_key', CacheService::EXPERTS_COUNT);

                SendEmail::dispatch($user, new RegisterMail($user));

                return response()->json([
                    'user' => $user,
                    'status' => true,
                    'message' => 'User Created Successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Authorization error',
                    'errors' => 'Unauthorized Register'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    protected function validateRecaptcha(array $data): ?JsonResponse
    {
        $validator = Validator::make($data, [
            'recaptcha_token' => 'required|recaptcha',
        ], [
            'recaptcha_token.required' => 'Please complete the reCAPTCHA verification.',
            'recaptcha_token.recaptcha' => 'Security check failed. Please verify you\'re not a robot.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Security verification required',
                'errors' => $validator->errors()
            ], 401);
        }

        return null;
    }

    public function checkData(Request $request)
    {
        try {
            $data = $request->all();

            $customMessages = [
                'first_name.required' => 'Please type in your first name.',
                'last_name.required' => 'Please type in your last name.',
                'email.required' => 'Please type in your email address.',
                'email.email' => 'Please provide a valid email address.',
                'email.unique' => 'This email address is already in use.',
                'password.required' => 'A password is required.',
                'password.min' => 'Please pick a strong password for your account.',
                'confirm_password.required' => 'Please confirm your password.',
                'confirm_password.same' => "The inputted passwords don't match.",
                'url.required' => 'Please type in your online store address.',
                'country.required' => 'Please select your country.',
                'about.required' => 'Tell us something about yourself.',
                'description.required' => 'Please write a brief for your project',
                'name.required' => 'Please pick a title for your project',
            ];

            $validateUser = Validator::make($data, [
                'first_name' => 'sometimes|required|string',
                'last_name' => 'sometimes|required|string',
                'email' => 'sometimes|required|email|unique:users,email',
                'password' => 'sometimes|required|string|min:8',

                'url' => 'sometimes|required|string',

                'country' => 'sometimes|required|string',
                'about' => 'sometimes|required|string',

                'description' => 'sometimes|required|string',
                'name' => 'sometimes|required|string',
            ], $customMessages);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please ensure all form fields are filled correctly before proceeding.',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            return response()->json([
                'message' => 'OK',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password are required to continue.',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::whereHas('role', function ($builder) use ($request) {
                $builder->where('name', $request->get('role'));
            })->where('email', $request->email);

            if ($request->get('role') === 'expert') {
                $user = $user->whereHas('profile', function ($builder) {
                    $builder->where('status', 'active');
                })->with('profile' , 'serviceCategories')->first();

                broadcast(new ExpertOnlineStatus($user->generateUserSlug(), true));
            } else {
                $user = $user->first();
            }

            if ($user) {
                return response()->json([
                    'user' => $user,
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Email & Password does not match with our record."
                ], 500);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            broadcast(new ExpertOnlineStatus($user->generateUserSlug(), false));

            if (Auth::guard('sanctum')->check()) {
                Auth::user()->tokens->each(function ($token) {
                    $token->delete();
                });
            } else {
                Auth::logout();
            }

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out and user status updated to offline.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error logging out user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email'         => 'required|email',
            'is_new_dash'   => 'nullable|boolean'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $token = Password::broker()->createToken($user);
        $isNewDashboard = $request->boolean('is_new_dash');

        $user->notify(new ResetPasswordLink($token, $isNewDashboard));

        return response()->json(['status' => 'Password reset link sent.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                $user->update(['password_changed', now()]);

//                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['error' => [__($status)]], 500);
    }

    public function authCheck()
    {
        if (Auth::user()) {
            return response()->json([
                'user' => Auth::user(),
                'message' => 'OK'
            ]);
        } else {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }

    /**
     * @return JsonResponse
     */
    public function switchToOldDashboard(): JsonResponse
    {
        $user = Auth::user();

        $token = $user->createToken('sso_token')->plainTextToken;
        $redirectUrl = env('OLD_DASH') . "/sso-login?token=" . urlencode($token);

        return response()->json([
            'url' => $redirectUrl,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function switchToNewDashboard(): JsonResponse
    {
        $user = Auth::user();

        $token = $user->createToken('sso_token')->plainTextToken;
        $redirectUrl = env('NEW_DASH') . "/sso-login?token=" . urlencode($token);

        return response()->json(['url' => $redirectUrl]);
    }

    public function expertList(Request $request)
    {
        $search = (string)$request->get('search');
        $fetchAll = filter_var($request->get('all'), FILTER_VALIDATE_BOOLEAN);

        $expertsQuery = User::query()->where('role_id', 3)->where('is_disable', false)->where(function ($query) use ($search) {
            $search = '%' . $search . '%';

            $query->where('first_name', 'like', $search);
        })->whereHas('profile', function($query) {
            $query->where('status', 'active');
        })->with('profile')->latest();

        if (!$fetchAll) {
            $expertsQuery->limit(15);
        }

        $experts = $expertsQuery->get();

        return response()->json(['experts' => $experts]);
    }

    public function clientList(Request $request)
    {
        $search = (string) $request->get('search');

        $clients = User::query()
            ->where('role_id', Role::CLIENT)
            ->where(function ($query) use ($search) {
                $searchParts = explode(' ', $search);

                if (!empty($searchParts[0])) {
                    $query->where('first_name', 'like', '%' . $searchParts[0] . '%')
                        ->orWhere('last_name', 'like', '%' . $searchParts[0] . '%');
                }

                if (isset($searchParts[count($searchParts) - 1]) && !empty($searchParts[count($searchParts) - 1])) {
                    $lastName = array_pop($searchParts);
                    $query->where('last_name', 'like', '%' . $lastName . '%');
                }

                if (count($searchParts) > 0) {
                    $firstName = implode(' ', $searchParts);
                    $query->where('first_name', 'like', '%' . $firstName . '%');
                }
            })
            ->with('profile')
            ->limit(15)
            ->latest()
            ->get();

        return response()->json(['clients' => $clients]);
    }

    /**
     * @return JsonResponse
     */
    public function hours(): JsonResponse
    {
        $user = Auth::user();

        $hours = Cache::remember(CacheService::HOURS . '_' . $user->id, CacheService::TTL_ONE_HOUR, function() use ($user) {
            $clientFunds = ClientFund::query()
                ->where('user_id', $user->id);

            $prepaidHours = $clientFunds->sum('prepaid_hours');
            $usedHours = $clientFunds->sum('used_hours');
            return $prepaidHours - $usedHours;
        });

        return response()->json(['hours' => $hours]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function loginAsUser(Request $request, User $user): JsonResponse
    {
        try {
            if ($user->role_id == Role::EXPERT){
                $user->load('serviceCategories');
            }
            $user->load('profile');
            $response = response()->json([
                'user' => $user,
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);

            Cookie::queue(Cookie::forget('shopexperts_session'));

            return $response;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginAs(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:expert,client'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Create token for the target user
        $token = $user->createToken('login-as-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('profile'),
            'token' => $token,
            'message' => 'Login successful'
        ]);
    }
}
