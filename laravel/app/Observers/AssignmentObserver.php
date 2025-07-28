<?php

namespace App\Observers;

use App\Models\Assignment;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignmentObserver
{
    /**
     * Handle the Assignment "created" event.
     */
    public function created(Assignment $assignment): void
    {
        try {
            $user = Auth::user() ?? $assignment->project->client;
            $roleId = $user->role_id;

            if ($roleId !== Role::ADMIN) {
                return;
            }

            $project = $assignment->project;

            $lastAssignment = Assignment::where('project_id', $project->id)
                ->where('is_active', false)
                ->latest('created_at')
                ->first();

            if (!$lastAssignment || $lastAssignment->expert_id === $assignment->expert_id) {
                return;
            }

            if ($assignment->created_at <= Carbon::now()->subSeconds(10)) {
                return;
            }

            $userFullName = "{$user->first_name} {$user->last_name}";
            $expertName = "{$assignment->expert->first_name} {$assignment->expert->last_name}";
            $userId = $user->id;

            ProjectHistory::create([
                'project_id' => $project->id,
                'changed_by_name' => $userFullName,
                'changed_by_id' => $userId,
                'role' => $user->role->name,
                'action' => ProjectHistory::getActionTitle(
                    ProjectHistory::ACTION_PROJECT_REASSIGNED_TO_ANOTHER_EXPERT_BY_ADMIN,
                    null,
                    $expertName,
                    $userFullName
                ),
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return;
        }
    }
}
