<?php

namespace App\Console\Commands;

use App\Events\SendEmail;
use App\Mail\Client\ProjectOfferReminderMail as ProjectClientOfferReminderMail;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProjectOfferReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:offer:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily email reminders to clients for projects to which expert have added the offer '
    . 'but have not been approved or declined within 72 hours.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Offer::with(['assignment.project.client', 'assignment.expert'])
            ->where('status', 'send')
            ->whereBetween('status_updated_at', [Carbon::now()->subHours(72), Carbon::now()->subHours(24)])
            ->chunk(100, function ($offers) {
                $offers->each(function ($offer) {

                    $assignment = $offer->assignment;
                    $expert = $assignment ? $assignment->expert : null;
                    $client = $assignment ? $assignment->project->client : null;
                    $offerStatusUpdatedDate = Carbon::parse($offer->status_updated_at);
                    $currentDate = Carbon::now();
                    $daysSinceOfferCreated = $offerStatusUpdatedDate->diffInDays($currentDate);
                    $remainingDays = max(0, 5 - $daysSinceOfferCreated);

                    if ($expert && $client) {

                        SendEmail::dispatch(
                            $client,
                            new ProjectClientOfferReminderMail(
                                $client,
                                $expert,
                                $assignment->project,
                                $offer,
                                $remainingDays
                            )
                        );
                    }
                });
            });
    }
}
