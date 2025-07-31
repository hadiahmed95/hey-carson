<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReleaseClaimedProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release projects that are not claimed within 5 minutes';

    /**
     * Execute the console command.
     * @param ProjectRepository $projectRepository
     */
    public function handle(ProjectRepository $projectRepository): void
    {
        Project::with(['activeAssignment.expert'])
            ->where('status', '=', 'claimed')
            ->where('status_updated_at', '<=', Carbon::now()->subMinutes(5))
            ->chunk(100, function ($projects) use ($projectRepository) {
                foreach ($projects as $project) {

                    $project->setIsJob(true);
                    if ($project->activeAssignment && $project->activeAssignment->expert) {
                        $projectRepository->deactivateAssignment($project, $project->activeAssignment->expert->id);
                        $projectRepository->moveToAvailable($project);
                    }
                }
            });
    }
}
