<?php

namespace App\Console\Commands;

use App\Services\ProjectService;
use Illuminate\Console\Command;

class ArchiveProject extends Command
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        parent::__construct();

        $this->projectService = $projectService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:archive {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command soft deletes a project and its resources';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $projectId = $this->argument('id');

        try {
            $this->projectService->archive($projectId);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
