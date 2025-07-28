<?php

namespace App\Observers;

use App\Events\CacheInvalidation;
use App\Events\SendEmail;
use App\Mail\Expert\ProjectAvailableAgainMail;
use App\Mail\Expert\ProjectAvaliableMail;
use App\Models\Assignment;
use App\Models\Event;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\User;
use App\Models\Role;
use App\Models\UserEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectObserver
{
    /**
     * Handle the Project "creating" event.
     */
    public function creating(Project $project): void
    {
        if (!$project->status_updated_at) {
            $project->status_updated_at = Carbon::now();
        }
    }

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        try {
            if (!$project->client) {
                return;
            }
            if ($project->client->click_id) {
                app('referral_api_admin')->submitProject($project->client->partner_id, $project->client->program_id);
            }

            $user = Auth::user() ?? $project->client;

            $userId = $user->id;
            $userFullName = $user->first_name . ' ' . $user->last_name;
            $clientFullName = $project->client?->first_name . ' ' . $project->client?->last_name;

            if ($user->role_id === Role::EXPERT) {
                $action = ProjectHistory::getActionTitle(
                    ProjectHistory::ACTION_PROJECT_CREATED_BY_EXPERT,
                    null, $userFullName
                );
            } elseif ($user->role_id === Role::CLIENT) {
                $action = ProjectHistory::getActionTitle(
                    ProjectHistory::ACTION_PROJECT_CREATED_BY_CLIENT,
                    $clientFullName
                );
            }
            if (isset($action)) {
                ProjectHistory::create([
                    'project_id' => $project->id,
                    'changed_by_name' => $userFullName,
                    'changed_by_id' => $userId,
                    'role' => $user->role->name,
                    'action' => $action,
                ]);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return;
        }
    }

    /**
     * Handle the Project "updating" event.
     */
    public function updating(Project $project): void
    {
        if ($project->status !== $project->getOriginal('status')) {
            $project->status_updated_at = Carbon::now();
        }

        if ($project->external_client_email !== $project->getOriginal('external_client_email')) {
            if ($project->client->click_id) {
                app('referral_api_admin')->submitProject($project->client->partner_id, $project->client->program_id);
            }
        }
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        try {
            if (!$project->client) {
                return;
            }
            $originalStatus = $project->getOriginal('status');

            if ($project->status !== $originalStatus) {
                $clientName = $project->client ? $project->client->first_name . ' ' . $project->client->last_name : null;
                $expertName = $project->activeAssignment ? $project->activeAssignment->expert->first_name . ' ' . $project->activeAssignment->expert->last_name : null;

                $this->logProjectStatusHistory($project, $originalStatus, $clientName, $expertName);
            }

            if ($project->status !== $originalStatus && !$project->isDirty('status_updated_at')) {
                $project->update(['status_updated_at' => Carbon::now()]);
            }

            if ($project->status === 'available' && $originalStatus !== 'available' && $originalStatus !== 'claimed') {
                $eventId = ($originalStatus === 'matched')
                    ? Event::EXPERT_PROJECT_AVAILABLE_AGAIN
                    : Event::EXPERT_PROJECT_AVAILABLE;

                $emailClass = ($originalStatus === 'matched')
                    ? ProjectAvailableAgainMail::class
                    : ProjectAvaliableMail::class;

                $previouslyAssignedExpertIds = Assignment::query()
                    ->where('project_id', $project->id)
                    ->pluck('expert_id')
                    ->toArray();


                $users = User::query()
                    ->where('role_id', Role::EXPERT)
                    ->whereNotIn('id', $previouslyAssignedExpertIds)
                    ->whereHas('profile', fn($query) => $query->where('status', 'active'));


                $users->each(function (User $user) use ($project, $eventId, $emailClass) {
                    UserEvent::create([
                        'user_id' => $user->id,
                        'project_id' => $project->id,
                        'event_id' => $eventId,
                    ]);

                    SendEmail::dispatch($user, new $emailClass($user, $project));
                    CacheInvalidation::dispatch('user_events', $user->id);
                });
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return;
        }
    }

    /**
     * Log the project history based on the status change.
     *
     * @param Project $project The project whose history is being logged.
     * @param string|null $originalStatus The original status of the project.
     * @param string|null $clientName The name of the client associated with the project.
     * @param string|null $expertName The name of the expert associated with the project.
     *
     * @return void
     */
    private function logProjectStatusHistory(Project $project, ?string $originalStatus, ?string $clientName, ?string $expertName): void
    {
        $user = Auth::user() ?? $project->client;
        $isJob = $project->getIsJob();
        $changedByName = $isJob ? 'System' : $user->first_name . ' ' . $user->last_name;
        $roleTitle = $isJob ? 'System' : $user->role->name;
        $changedById = $isJob ? -1 : $user->id;
        $hours = null;

        $action = $this->determineProjectAction($project, $originalStatus, $user->role_id, $project->status, $hours, $isJob);

        if ($action) {
            ProjectHistory::create([
                'project_id' => $project->id,
                'changed_by_name' => $changedByName,
                'changed_by_id' => $changedById,
                'role' => $roleTitle,
                'action' => ProjectHistory::getActionTitle($action, $clientName, $expertName, $changedByName, $hours),
            ]);
        }
    }

    /**
     * Determine the action based on the project status change.
     *
     * @param Project $project The project whose action is being determined.
     * @param string|null $originalStatus The original status of the project.
     * @param int $roleId The role ID of the user performing the action.
     * @param string $status The new status of the project.
     * @param int|null &$hours The number of hours related to the project (passed by reference).
     * @param bool|null $isJob Action performed by Job
     *
     * @return string|null The action string to be logged in the history.
     */
    private function determineProjectAction(Project $project, ?string $originalStatus, int $roleId, string $status, ?int &$hours, ?bool $isJob): ?string
    {
        if ($status === Project::AVAILABLE) {
            return $this->handleAvailableStatus($originalStatus, $roleId);
        } else if ($status === Project::MATCHED) {
            return $this->handleMatchedStatus($originalStatus, $roleId, $project, $hours);
        } else if ($status === Project::CLAIMED) {
            return ProjectHistory::ACTION_PROJECT_CLAIMED_BY_EXPERT;
        } else if ($status === Project::PENDING_PAYMENT) {
            return $this->handlePendingPaymentStatus($originalStatus, $project, $hours);
        } else if ($status === Project::IN_PROGRESS) {
            return $this->handleInProgressStatus($originalStatus, $project, $hours);
        } else if ($status === Project::EXPERT_COMPLETED) {
            return ProjectHistory::ACTION_PROJECT_EXPERT_COMPLETED;
        } else if ($status === Project::COMPLETED) {
            return $this->handleCompleteStatus($originalStatus, $isJob);
        }

        return null;
    }

    /**
     * Handle the action when the project status changes to 'AVAILABLE'.
     *
     * @param string|null $originalStatus The original status of the project.
     * @param int $roleId The role ID of the user performing the action.
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleAvailableStatus(?string $originalStatus, int $roleId): ?string
    {
        switch ($originalStatus) {
            case Project::MATCHED:
                return $this->handleAvailableFromMatched($roleId);
            case Project::PENDING_MATCH:
                return ProjectHistory::ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_ADMIN;
            case Project::CLAIMED:
                return $this->handleAvailableFromClaimed($roleId);
            default:
                return null;
        }
    }

    /**
     * Handle the action when the project status changes from 'MATCHED' to 'AVAILABLE'.
     *
     * @param int $roleId The role ID of the user performing the action.
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleAvailableFromMatched(int $roleId): ?string
    {
        if ($roleId === Role::ADMIN) {
            return ProjectHistory::ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_ADMIN;
        }
        if ($roleId === Role::EXPERT) {
            return ProjectHistory::ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_EXPERT;
        }
        return null;
    }

    /**
     * Handle the action when the project status changes from 'CLAIMED' to 'AVAILABLE'.
     *
     * @param int $roleId The role ID of the user performing the action.
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleAvailableFromClaimed(int $roleId): ?string
    {
        if ($roleId === Role::EXPERT) {
            return ProjectHistory::ACTION_PROJECT_MOVED_TO_AVAILABLE_BY_EXPERT;
        }
        return ProjectHistory::ACTION_PROJECT_AUTO_RELEASED;
    }

    /**
     * Handle the action when the project status changes to 'MATCHED'.
     *
     * @param string|null $originalStatus The original status of the project.
     * @param int $roleId The role ID of the user performing the action.
     * @param Project $project The project object to retrieve active assignment details.
     * @param int|null &$hours The number of hours related to the project (passed by reference).
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleMatchedStatus(?string $originalStatus, int $roleId, Project $project, ?int &$hours): ?string
    {
        switch ($originalStatus) {
            case Project::PENDING_MATCH:
                return $this->handleMatchedFromPendingMatch($roleId);
            case Project::AVAILABLE:
                return $this->handleMatchedFromAvailable($roleId);
            case Project::PENDING_PAYMENT:
                return $this->handleMatchedFromPendingPayment($project, $hours);
            case Project::CLAIMED:
                return ProjectHistory::ACTION_PROJECT_JOINED_BY_EXPERT;
            default:
                return null;
        }
    }

    /**
     * Handle the action when the project status changes from 'PENDING_MATCH' to 'MATCHED'.
     *
     * @param int $roleId The role ID of the user performing the action.
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleMatchedFromPendingMatch(int $roleId): ?string
    {
        if ($roleId === Role::ADMIN) {
            return ProjectHistory::ACTION_PROJECT_MATCHED_BY_ADMIN;
        }
        if ($roleId === Role::EXPERT) {
            return ProjectHistory::ACTION_PROJECT_JOINED_BY_EXPERT;
        }
        if ($roleId === Role::CLIENT) {
            return ProjectHistory::ACTION_CLIENT_ADDED_EXPERT;
        }
        return null;
    }

    /**
     * Handle the action when the project status changes from 'AVAILABLE' to 'MATCHED'.
     *
     * @param int $roleId The role ID of the user performing the action.
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleMatchedFromAvailable(int $roleId): ?string
    {
        if ($roleId === Role::ADMIN) {
            return ProjectHistory::ACTION_PROJECT_MATCHED_BY_ADMIN;
        }
        if ($roleId === Role::EXPERT) {
            return ProjectHistory::ACTION_PROJECT_MATCHED_BY_EXPERT;
        }
        return null;
    }

    /**
     * Handle the action when the project status changes from 'Pending_Payment' to 'MATCHED'.
     *
     * @param Project $project The project object to retrieve active assignment details.
     * @param int|null &$hours The number of hours related to the project (passed by reference).
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleMatchedFromPendingPayment(Project $project, ?int &$hours): ?string
    {
        $lastOffer = $project->activeAssignment ? $project->activeAssignment->offers->last() : null;
        $hours = $project->activeAssignment
            ? $project->activeAssignment->offers->last()?->hours ?? 0
            : 0;

        return match ($lastOffer?->status) {
            'decline' => ProjectHistory::ACTION_PROJECT_DECLINED_QUOTE,
            'expired' => match ($lastOffer?->type) {
                'scope' => ProjectHistory::ACTION_PROJECT_SCOPE_EXPIRED,
                'offer' => ProjectHistory::ACTION_PROJECT_QUOTE_EXPIRED,
            },
        };
    }

    /**
     * Handle the action when the project status changes to 'PENDING_PAYMENT'.
     *
     * @param string|null $originalStatus The original status of the project.
     * @param Project $project The project object to retrieve active assignment details.
     * @param int|null &$hours The number of hours related to the project (passed by reference).
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handlePendingPaymentStatus(?string $originalStatus, Project $project, ?int &$hours): ?string
    {
        if ($originalStatus === Project::MATCHED) {
            return ProjectHistory::ACTION_PROJECT_QUOTE_SENT;
        }

        if ($originalStatus === Project::IN_PROGRESS || $originalStatus === Project::COMPLETED) {
            $hours = $project->activeAssignment
                ? $project->activeAssignment->offers->where('status', 'send')->last()?->hours ?? 0
                : 0;
            return ProjectHistory::ACTION_PROJECT_SCOPE_ADDED;
        }

        return null;
    }

    /**
     * Handle the action when the project status changes to 'IN_PROGRESS'.
     *
     * @param string|null $originalStatus The original status of the project.
     * @param Project $project The project object to retrieve active assignment details.
     * @param int|null &$hours The number of hours related to the project (passed by reference).
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleInProgressStatus(?string $originalStatus, Project $project, ?int &$hours): ?string
    {
        if ($originalStatus === Project::PENDING_PAYMENT) {
            $lastOffer = $project->activeAssignment ? $project->activeAssignment->offers->last() : null;
            $hours = $project->activeAssignment
                ? $project->activeAssignment->offers->last()?->hours ?? 0
                : 0;

            return match ($lastOffer?->status) {
                'paid' => match ($lastOffer?->type) {
                    'scope' => ProjectHistory::ACTION_PROJECT_SCOPE_ACCEPTED,
                    'offer' => ProjectHistory::ACTION_PROJECT_ACCEPTED_QUOTE,
                },
                'decline' => ProjectHistory::ACTION_PROJECT_SCOPE_DECLINED,
                'expired' => match ($lastOffer?->type) {
                    'scope' => ProjectHistory::ACTION_PROJECT_SCOPE_EXPIRED,
                    'offer' => ProjectHistory::ACTION_PROJECT_QUOTE_EXPIRED,
                },
            };
        }

        if ($originalStatus === Project::EXPERT_COMPLETED) {
            return ProjectHistory::ACTION_PROJECT_REOPENED;
        }

        return null;
    }

    /**
     * Handle the action when the project status changes to 'Completed'.
     *
     * @param string|null $originalStatus The original status of the project.
     * @param bool|null $isJob Action performed by Job
     *
     * @return string|null The action string to be logged in the history.
     */
    private function handleCompleteStatus(?string $originalStatus, ?bool $isJob): ?string
    {
        if ($originalStatus === Project::EXPERT_COMPLETED) {
            return $isJob
                ? ProjectHistory::ACTION_AUTO_PROJECT_COMPLETED
                : ProjectHistory::ACTION_PROJECT_COMPLETED_BY_CLIENT;
        }

        if ($originalStatus === Project::PENDING_PAYMENT) {
            return ProjectHistory::ACTION_PROJECT_MOVED_BACK_TO_COMPLETED;
        }

        return null;
    }


}
