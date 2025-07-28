<?php

namespace App\Console\Commands;

use App\Events\CacheInvalidation;
use App\Events\SendEmail;
use App\Mail\Client\ProjectPaymentMail as ProjectClientPaymentMail;
use App\Mail\Expert\ProjectPaymentMail;
use App\Mail\Expert\ProjectPaymentScopeMail;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Project;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Console\Command;

class ChangeProjectStatusToInProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:change-project-status-to-in-progress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(To fix Lautaro Issue) Payment was made, but the project status is still pending payment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //This command is specifically used to fix Erick's issue
        //https://heycarson.slack.com/archives/C06L1QWANAC/p1749565872708889

        $project = Project::find(1283);
        $offer  = Offer::find(832);
        $client = User::find(971);

        $this->update($client, $project, $offer);
        dump('Successfully ran the command');

    }

    public function update(User $user, Project $project, Offer $offer)
    {
        if ($project->client_id === $user->id) {
            $offer->update(['status' => 'paid']);
            dump('Updated Offer status');

            $project->messages()->create([
                'type' => 'banner',
                'user_type' => 'client',
                'banner_id' => $offer->type === 'offer' ? Banner::SUCCESS_CLIENT_OFFER : Banner::SUCCESS_CLIENT_SCOPE,
                'seen' => 1
            ]);

            dump('Created payment banner in the chat');
            $project->update(['status' => 'in_progress']);

            dump('Updated project status');
            $expertId = $project->activeAssignment->expert->id;

            UserEvent::create([
                'user_id' => $expertId,
                'project_id' => $project->id,
                'event_id' => $offer->type === 'offer' ? Event::EXPERT_PROJECT_PAYMENT_OFFER : Event::EXPERT_PROJECT_PAYMENT_SCOPE,
            ]);

            dump('Created notification for expert');

            UserEvent::create([
                'user_id' => $project->client_id,
                'project_id' => $project->id,
                'event_id' => Event::CLIENT_PROJECT_PAYMENT,
            ]);

            dump('Created notification for client');

            CacheInvalidation::dispatch('user_events', $expertId);
            CacheInvalidation::dispatch('user_events', $project->client_id);

            dump('Cleared Cache');

            if ($offer->type === 'offer') {
                SendEmail::dispatch($project->activeAssignment->expert, new ProjectPaymentMail($project->activeAssignment->expert, $user, $project));
            } else {
                SendEmail::dispatch($project->activeAssignment->expert, new ProjectPaymentScopeMail($project->activeAssignment->expert, $user, $project));
            }
            SendEmail::dispatch($user, new ProjectClientPaymentMail($user, $project));

            dump('Sent emails to client and expert');

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }
}
