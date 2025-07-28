<?php

namespace App\Console\Commands;

use App\Services\ProjectService;
use Illuminate\Console\Command;

class RestoreProject extends Command
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
    protected $signature = 'project:restore {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command restores a project and its resources.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $projectId = $this->argument('id');

        try {
            $this->projectService->restore($projectId);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
