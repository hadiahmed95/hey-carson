<?php

namespace App\Console\Commands;

use App\Services\ExpertUserService;
use Illuminate\Console\Command;

class MarkExpertUnavailable extends Command
{
    private ExpertUserService $expertUserService;

    public function __construct(ExpertUserService $expertUserService)
    {
        parent::__construct();
        $this->expertUserService = $expertUserService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expert:unavailable {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command marks an expert as unavailable';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $expertId = $this->argument('id');
        try {
            $this->expertUserService->markUnavailable($expertId);
            $this->info("Expert ID {$expertId} has been marked as unavailable.");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
