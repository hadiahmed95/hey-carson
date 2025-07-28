<?php

namespace App\Repositories;

use App\Events\CacheInvalidation;
use App\Events\SendEmail;
use App\Mail\Client\NewProjectInitiatedByExpertMail;
use App\Mail\Client\ProjectCreatedMail;
use App\Mail\Client\ProjectMatchedMail as ProjectClientMatchedMail;
use App\Mail\Client\RegistrationRequestClientMail;
use App\Mail\Expert\ProjectAvaliableMail;
use App\Mail\Expert\ProjectFinishedMail;
use App\Mail\Expert\ProjectMatchedMail as ProjectExpertMatchedMail;
use App\Models\AdminSetting;
use App\Models\Assignment;
use App\Models\Banner;
use App\Models\ClientFund;
use App\Models\Event;
use App\Models\ExpertFund;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectFiles;
use App\Models\Request;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use App\Models\UserEvent;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ProjectRepository
{
    /**
     * Create a new message, or upload file to the S3
     *
     * @param $data
     * @param $user
     * @return void
     */
    public function create($data, $user): void
    {
        $roleId = $user->role_id;

        $projectData = [
            'click_id'              => $data['click_id'],
            'name'                  => $data['title'],
            'description'           => $data['description'],
            'urgent'                => $data['urgent'] ?? 0,
            'preferred_expert_id'   => $data['preferred_expert_id'] ?? null,
            'company_type'          => $user->company_type ?? 'eCommerce Brand',
            'is_additional_experts' => $data['is_additional_experts'] ?? 0,
            'additional_experts'      => $data['additional_experts'] ?? null,
        ];

        if ($roleId === Role::CLIENT) {
            $projectData['client_id'] = $user->id;
            $projectData['url'] = $data['url'];
            $projectData['is_additional_experts'] = $data['is_additional_experts'] ?? 0;
            $projectData['additional_experts'] = $data['additional_experts'] ?? null;
        }

        if ($roleId === Role::EXPERT) {
            if (isset($data['client_id'])) {
                $projectData['client_id'] = $data['client_id'];
                $projectData['url'] = $data['url'];
            } else if(isset($data['client_email'])) {
                $client = User::where('email', $data['client_email'])->first();
                if ($client) {
                    $projectData['client_id'] = $client->id;
                    $projectData['url'] = $client->url;
                } else {
                    $projectData['external_client_email'] = $data['client_email'];
                }
            }
        }

        $project = Project::create($projectData);

        if ($project->referral) {
            $project->referral->increment('total_projects_submitted');
        }

        CacheInvalidation::dispatch('cache_duration_key', CacheService::PROJECTS_COUNT);

        if ($roleId === Role::EXPERT && ($project->client_id || $project->external_client_email)) {

            $expertToAssign = !$project->preferred_expert_id ? $user->id : $data['preferred_expert_id'];
            $this->assignPreferred($project, $expertToAssign, $roleId);


            if ($project->client_id) {
                SendEmail::dispatch(
                    $project->client,
                    new NewProjectInitiatedByExpertMail($project->client, $project->activeAssignment->expert, $project)
                );
            } elseif ($project->external_client_email) {
                $clientEmail = $data['client_email'];
                $clientName = $data['client_name'];
                SendEmail::dispatch(
                    $project->external_client_email,
                    new RegistrationRequestClientMail($project->activeAssignment->expert, $project, $clientName, $clientEmail)
                );
            }
        } elseif ($roleId === Role::CLIENT) {
            if (isset($data['preferred_expert_id'])) {
                $this->assignPreferred($project, $data['preferred_expert_id']);
            } else {
                $moderateProjects = AdminSetting::query()
                    ->where('type', 'moderate_projects')
                    ->first()
                    ->value;

                if (!$moderateProjects) {
                    $this->moveToAvailable($project);
                }
            }
        }

        if (isset($data['files'])) {
            $this->uploadFiles($project, $data['files']);
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

        $requests = Request::query()
            ->where('client_id', $user->id)
            ->with(['project', 'expert.profile', 'expert.reviews'])
            ->latest()
            ->when($limit, fn($q) => $q->take($limit))
            ->get();


        $requests->each(function ($request) {
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
        });

        $expertIds = $requests->pluck('project')
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

        $requests->each(function ($request) use ($additionalExpertProfiles) {
            $project = $request->project;

            if ($project && is_array($project->additional_experts)) {
                $project->additional_expert_profiles = collect($project->additional_experts)
                    ->map(function ($id) use ($additionalExpertProfiles, $request) {
                        $expert = $additionalExpertProfiles->get($id);

                        if ($expert && $request->type === 'Quote Request') {
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

        return $requests;
    }

    /**
     * @param Project $project
     * @param int $expertId
     * @param mixed $user
     * @param float $rate
     * @param string $comment
     * @param float|null $communication
     * @param float|null $quality
     * @param string|null $recommendation
     * @param float|null $timeToStart
     * @param float|null $valueForMoney
     * @param string|null $valueRange
     * @return JsonResponse
     */
    public function complete(Project $project,
                             int $expertId,
                             mixed $user,
                             float $rate = -1,
                             string $comment = '',
                             ?float $communication = null,
                             ?float $quality = null,
                             ?string $recommendation = null,
                             ?float $timeToStart = null,
                             ?float $valueForMoney = null,
                             ?string $valueRange = null): JsonResponse
    {
        if ($project->status === 'completed') {
            return response()->json(['message' => 'OK']);
        } elseif ($project->client_id === $user->id) {
            $project->update(['status' => 'completed']);

            CacheInvalidation::dispatch('cache_duration_key', CacheService::COMPLETED_PROJECT_COUNT);

            $project->refresh();
            $expert = User::find($expertId);

            if ($rate !== -1 && $comment !== '') {
                Review::create([
                    'client_id'      => $user->id,
                    'project_id'     => $project->id,
                    'expert_id'      => $expert->id,
                    'rate'           => $rate,
                    'comment'        => $comment,
                    'communication'  => $communication,
                    'quality'        => $quality,
                    'recommendation' => $recommendation,
                    'timeToStart'    => $timeToStart,
                    'valueForMoney'  => $valueForMoney,
                    'valueRange'     => $valueRange
                ]);

                CacheInvalidation::dispatch('cache_key', CacheService::REVIEWS . '_' . $expert->id);
            }

            $project->messages()->where('type', 'banner')
                ->where('banner_id', 5)
                ->update(['content' => 'completed']);

            $project->messages()->create([
                'type' => 'banner',
                'user_type' => 'client',
                'banner_id' => Banner::SUCCESS_CLIENT_COMPLETED,
                'seen' => 1
            ]);

            UserEvent::create([
                'user_id' => $expert->id,
                'project_id' => $project->id,
                'event_id' => Event::EXPERT_PROJECT_FINISHED
            ]);

            CacheInvalidation::dispatch('user_events', $expert->id);
            $this->depositToExpert($project, $expert);

            SendEmail::dispatch($expert, new ProjectFinishedMail($expert, $user, $project));

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    /**
     * @param Project $project
     * @param User $expert
     * @return void
     */
    private function depositToExpert(Project $project, User $expert): void
    {
        $total = Payment::query()
            ->whereIn('status', ['succeeded', 'prepaid'])
            ->where('project_id', $project->id)
            ->sum('total');

        $expertFunds = ExpertFund::query()
            ->where('user_id', $expert->id);

        if ($expertFunds->latest()->first()) {
            $amount = $total * (1 - $expertFunds->latest()->first()->admin_fee);

            /**
             * Check Expert Level
             */
            $levelEarnings = $expertFunds->sum('total');

            if ($levelEarnings >= 75000 && $levelEarnings < 150000) {
                $expertLevel = 2;
                $adminFee = 0.30;
            } elseif ($levelEarnings >= 150000 && $levelEarnings < 250000) {
                $expertLevel = 3;
                $adminFee = 0.25;
            } elseif ($levelEarnings >= 250000 && $levelEarnings < 1000000) {
                $expertLevel = 4;
                $adminFee = 0.18;
            } else {
                $expertLevel = $expertFunds->latest()->first()->expert_level;
                $adminFee = $expertFunds->latest()->first()->admin_fee;
            }
        } else {
            $expertLevel = 1;
            $adminFee = 0.35;

            $amount = $total * (1 - $adminFee);
        }

        ExpertFund::create([
            'user_id' => $expert->id,
            'amount' => $amount,
            'total' => $total,
            'expert_level' => $expertLevel,
            'admin_fee' => $adminFee
        ]);

        CacheInvalidation::dispatch('cache_key', CacheService::TOTAL_EARNINGS . '_' . $expert->id);
        CacheInvalidation::dispatch('cache_key', CacheService::TOTAL_BALANCE . '_' . $expert->id);
        CacheInvalidation::dispatch('cache_duration_key', CacheService::EXPERT_EARNING);
        CacheInvalidation::dispatch('cache_duration_key', CacheService::OUR_COMMISSION);
    }

    /**
     * @param Project $project
     * @param string|null $tabName
     * @param int $roleId
     * @return Project
     */
    public function show(Project $project, ?string $tabName, int $roleId): Project
    {
        if ($roleId === Role::EXPERT) {
            $this->handleClaimedStatus($project, \Auth::user());
        }

        return $this->loadProjectRelations($project, $tabName, $roleId);
    }

    /**
     * @param Project $project
     * @param string|null $tabName
     * @param int $roleId
     * @return Project
     */
    private function loadProjectRelations(Project $project, ?string $tabName, int $roleId): Project
    {
        return match ($tabName) {
            Project::TAB_CHAT            => $this->loadChatroomRelations($project, $roleId),
            Project::TAB_DESCRIPTION     => $project->load(['messages','projectFiles']),
            Project::TAB_INVOICES        => $project->load(['messages','invoices.offer', 'invoices.user']),
            default                      => $this->loadDefaultRelations($project, $roleId),
        };
    }

    /**
     * @param Project $project
     * @param int $roleId
     * @return Project
     */
    private function loadChatroomRelations(Project $project, int $roleId): Project
    {
        if ($roleId === Role::EXPERT) {
            $project = $project->load([
                'client',
                'activeAssignment',
                'messages.user',
                'messages.banner',
                'messages.offer',
                'messages.projectFile',
                'messages.reply',
                'messages.reply.user',
                'messages.reply.projectFile',
            ]);
            $this->attachPrepaidHours($project->client);
        } else if ($roleId === Role::CLIENT) {
            $project = $project->load([
                'client',
                'activeAssignment',
                'activeAssignment.expert',
                'activeAssignment.expert.profile',
                'messages.user',
                'messages.banner',
                'messages.offer',
                'messages.projectFile',
                'messages.reply',
                'messages.reply.user',
                'messages.reply.projectFile',
            ]);
        }

        return $project;
    }

    /**
     * @param Project $project
     * @param int $roleId
     * @return Project
     */
    private function loadDefaultRelations(Project $project, int $roleId): Project
    {
        if ($roleId === Role::EXPERT) {
            $project = $project->load([
                'client',
                'activeAssignment',
                'activeAssignment.offers',
            ]);
            $this->attachPrepaidHours($project->client);
        } else if ($roleId === Role::CLIENT) {
            $project = $project->load([
                'messages',
                'activeAssignment',
                'activeAssignment.offers',
                'activeAssignment.expert',
                'activeAssignment.expert.profile',
                'activeAssignment.expert.reviews',
                'activeAssignment.expert.activeAssignments.project',
                'review',
            ]);
        }

        return $project;
    }

    /**
     * @param User|null $client
     * @return void
     */
    private function attachPrepaidHours(?User $client): void
    {
        if (!$client) {
            return;
        }

        $clientFunds = ClientFund::query()
            ->where('user_id', $client->id);

        $prepaidHours = $clientFunds->sum('prepaid_hours');
        $usedHours = $clientFunds->sum('used_hours');

        $client->prepaid_hours = $prepaidHours - $usedHours;
    }

    /**
     * @param Project $project
     * @param User $user
     * @return void
     */
    private function handleClaimedStatus(Project $project, User $user): void
    {
        if ($project->status === Project::CLAIMED) {
            $this->removeAssignment($project, $user);
        }

        CacheInvalidation::dispatch('cache_key', CacheService::ACTIVE_ASSIGNMENTS . '_' . $user->id);
    }

    /**
     * @param Project $project
     * @param User $user
     * @return void
     */
    private function removeAssignment(Project $project, User $user): void
    {
        Assignment::query()->where('project_id', $project->id)
            ->where('expert_id', $user->id)
            ->where('is_active', true)
            ->whereDate('created_at', '<', Carbon::now()->subMinutes(5))
            ->delete();
    }

    /**
     * @param Project $project
     * @param $expertId
     * @return void
     */
    public function deactivateAssignment(Project $project, $expertId): void
    {
        $assignment = Assignment::where('expert_id', $expertId)
            ->where('project_id', $project->id)
            ->where('is_active', true)
            ->first();

        if ($assignment) {
            $assignment->update(['is_active' => false]);
        }
    }

    /**
     * @param Project $project
     * @param int $userId
     * @param int|null $roleId
     * @return void
     */
    public function assignPreferred(Project $project, int $userId, ?int $roleId = null): void
    {
        Assignment::create([
            'expert_id' => $userId,
            'project_id' => $project->id,
            'is_active' => true
        ]);

        CacheInvalidation::dispatch('cache_key', CacheService::ACTIVE_ASSIGNMENTS . '_' . $userId);

        $project->messages()->create([
            'type' => 'banner',
            'user_type' => 'client',
            'banner_id' => Banner::INFO_EXPERT_MATCHED,
            'seen' => 1
        ]);

        $project->refresh();

        UserEvent::create([
            'user_id' => $userId,
            'project_id' => $project->id,
            'event_id' => Event::EXPERT_PROJECT_MATCHED,
        ]);

        if ($project->client_id) {
            UserEvent::create([
                'user_id' => $project->client_id,
                'project_id' => $project->id,
                'event_id' => Event::CLIENT_PROJECT_MATCHED,
            ]);
        }

        CacheInvalidation::dispatch('user_events', $userId);
        if ($project->client_id) {
            CacheInvalidation::dispatch('user_events', $project->client_id);
        }

        SendEmail::dispatch(
            $project->activeAssignment->expert,
            new ProjectExpertMatchedMail($project->activeAssignment->expert, $project)
        );
        if ($project->client && $roleId !== Role::EXPERT) {
            SendEmail::dispatch(
                $project->client,
                new ProjectClientMatchedMail($project->client, $project->activeAssignment->expert, $project)
            );
        }

        if ($project->status !== 'in_progress') {
            $project->update(['status' => 'matched']);
        }
    }

    /**
     * @param Project $project
     * @return void
     */
    public function moveToAvailable(Project $project): void
    {
        $project->update(['status' => 'available']);
    }

    /**
     * Update the project status based on the offer statuses.
     *
     * @param Project $project
     */
    public function backToPreviousStatus(Project $project): void
    {
        if ($project->activeAssignment) {

            $paidOffersExist = Offer::where('assignment_id', $project->activeAssignment->id)
                ->where('status', 'paid')
                ->exists();

            $projectCompleted = Review::where('project_id', $project->id)
                ->exists();

            $newStatus = $projectCompleted ? Project::COMPLETED : ($paidOffersExist ? Project::IN_PROGRESS : Project::MATCHED);

            if ($project->status !== $newStatus) {
                $project->update(['status' => $newStatus]);
            }
        }
    }

    /**
     * @param Project $project
     * @param array $files
     * @return void
     */
    private function uploadFiles(Project $project, array $files): void
    {
        // TODO: remove loop and use batch insert
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $path = 'projectFile/'. $project->id;
            $file->storeAs('projectFile/'. $project->id, $filename, 's3');

            ProjectFiles::query()->create([
                'project_id' => $project->id,
                'name' => $filename,
                'url' => $path . '/' . $filename
            ]);
        }
    }

    /**
     * @param Collection $partnerUsersId
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function partnerProjects(Collection $partnerUsersId, $after, $before): \Illuminate\Database\Eloquent\Collection|array
    {
        $query = Project::query()
            ->join('users', 'users.id', '=', 'projects.client_id')
            ->whereIn('client_id', $partnerUsersId)
            ->select(\DB::raw("CONCAT_WS(' ', users.first_name, users.last_name) as client_name"), 'name', 'status', 'projects.created_at');

        if ($after) {
            $after = Carbon::parse($after);
            $query->where('projects.created_at', '>', $after)->orderBy('projects.created_at', 'asc');
        } else {
            $query->orderBy('projects.created_at', 'desc');
        }

        if ($before) {
            $before = Carbon::parse($before);
            $query->where('projects.created_at', '<', $before);
        }

        $projects = $query->limit(15)->get();

        if ($after) {
            $projects = $projects->reverse()->values();
        }

        return $projects;
    }

    public function partnertotalProjects(Collection $partnerUsersId): int
    {

        return Project::query()
            ->whereIn('client_id', $partnerUsersId)
            ->count();
    }

    public function getRemainingProjectsCount(Collection $partnerUsers, $after, $before, $projects, $totalProjectsCount): int
    {
        if ($after) {
            return Project::whereIn('client_id', $partnerUsers)
                ->where('projects.created_at', '>', $projects->first()->created_at)
                ->count();
        }

        if ($before) {
            return Project::whereIn('client_id', $partnerUsers)
                ->where('projects.created_at', '<', $projects->last()->created_at)
                ->count();
        }

        return $totalProjectsCount - $projects->count();
    }
}
