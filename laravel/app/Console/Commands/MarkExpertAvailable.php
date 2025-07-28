<?php

namespace App\Console\Commands;

use App\Services\ExpertUserService;
use Illuminate\Console\Command;

class MarkExpertAvailable extends Command
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
    protected $signature = 'expert:available {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command marks an expert as available';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $expertId = $this->argument('id');
        try {
            $this->expertUserService->markAvailable($expertId);
            $this->info("Expert ID {$expertId} has been marked as available.");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
