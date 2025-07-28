<?php

namespace App\Console\Commands;

use App\Services\ExpertUserService;
use Illuminate\Console\Command;

class MarkExpertEnable extends Command
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
    protected $signature = 'expert:enable {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command enable the expert user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $expertId = $this->argument('id');
        try {
            $this->expertUserService->markEnable($expertId);
            $this->info("Expert ID {$expertId} has been enabled successfully.");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
