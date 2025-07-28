<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkProjectComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete projects submitted for review by the expert that have not been approved or '
                            . 'declined by the client within 72 hours.';

    /**
     * Execute the console command.
     * @param ProjectRepository $projectRepository
     */
    public function handle(ProjectRepository $projectRepository): void
    {
        Project::with(['activeAssignment.expert', 'client'])
            ->where('status', '=', 'expert_completed')
            ->where('status_updated_at', '<', Carbon::now()->subHours(72))
            ->chunk(100, function ($projects) use ($projectRepository) {
                foreach ($projects as $project) {
                    $project->setIsJob(true);
                    if ($project->activeAssignment && $project->activeAssignment->expert) {
                        $projectRepository->complete(
                            $project,
                            $project->activeAssignment->expert->id,
                            $project->client
                        );
                    }
                }
            });
    }

}
