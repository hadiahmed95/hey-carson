<?php

namespace App\Console\Commands;

use App\Events\SendEmail;
use App\Mail\Client\ProjectCompletedReminderMail as ProjectClientCompletedReminderMail;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProjectCompletedReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:complete:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily email reminders to clients for projects that have been completed by the expert '
    . 'but have not been approved or declined within 72 hours.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Project::with(['activeAssignment.expert', 'client'])
            ->where('status', 'expert_completed')
            ->whereBetween('status_updated_at', [Carbon::now()->subHours(72), Carbon::now()->subHours(24)])
            ->chunk(100, function ($projects) {
                $projects->each(function ($project) {

                    $assignment = $project->activeAssignment;
                    $expert = $assignment ? $assignment->expert : null;
                    $client = $project->client;
                    $projectMarkAsCompletedDateByExpert = Carbon::parse($project->status_updated_at);
                    $currentDate = Carbon::now();
                    $daysSinceProjectCompletedByExpert = $projectMarkAsCompletedDateByExpert->diffInDays($currentDate);
                    $remainingDays = max(0, 3 - $daysSinceProjectCompletedByExpert);
                    if ($expert && $client) {
                        SendEmail::dispatch($client, new ProjectClientCompletedReminderMail($client, $expert, $project, $remainingDays));
                    }
                });
            });
    }
}
