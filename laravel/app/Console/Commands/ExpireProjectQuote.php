<?php

namespace App\Console\Commands;

use App\Models\Offer;
use App\Models\Project;
use App\Repositories\OfferRepository;
use App\Repositories\ProjectRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireProjectQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:expire:quote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire Project Quote';

    /**
     * Execute the console command.
     * @param OfferRepository $offerRepository
     * @param ProjectRepository $projectRepository
     */
    public function handle(OfferRepository $offerRepository, ProjectRepository $projectRepository): void
    {
        Offer::where('status', '=', 'send')
            ->where('status_updated_at', '<', Carbon::now()->subHours(120))
            ->chunkById(100, function ($offers) use ($offerRepository, $projectRepository) {

                $projectsToUpdate = [];

                foreach ($offers as $offer) {

                    $offerRepository->updateOfferStatusToExpired($offer);

                    if ($offer->assignment && $offer->assignment->project) {
                        $projectsToUpdate[$offer->assignment->project->id] = $offer->assignment->project;
                    }
                }
                foreach ($projectsToUpdate as $project) {
                    $project->setIsJob(true);
                    $projectRepository->backToPreviousStatus($project);
                }
            });
    }
}
