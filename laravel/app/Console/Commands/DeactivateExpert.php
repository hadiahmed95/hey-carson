<?php

namespace App\Console\Commands;

use App\Services\ExpertUserService;
use Illuminate\Console\Command;

class DeactivateExpert extends Command
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
    protected $signature = 'expert:deactivate {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command soft deletes an expert';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expertId = $this->argument('id');
        try {
            $this->expertUserService->deactivate($expertId);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
