<?php

namespace App\Observers;

use App\Events\CacheInvalidation;
use App\Models\Event;
use App\Models\Project;
use App\Models\User;
use App\Models\UserEvent;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
//        TODO: use queue
        if ($user->click_id) {
            app('referral_api_public')->createCustomer(
                $user->first_name . ' '. $user->last_name,
                $user->email,
                $user->click_id
            );
        }

        $this->updateProjectsForClient($user);

    }

    /**
     * Update projects for a user by setting the client_id and clearing the external_client_email.
     *
     * This method retrieves all projects where the external_client_email matches the user's email,
     * updates the client_id to the user's id, and removes the external_client_email.
     *
     * @param User $user
     * @return void
     */
    protected function updateProjectsForClient(User $user): void
    {
        $projects = Project::where('external_client_email', $user->email)->get();

        foreach ($projects as $project) {
            $project->client_id = $user->id;
            $project->external_client_email = null;
            $project->url = $user->url;
            $project->save();
        }
    }
}
