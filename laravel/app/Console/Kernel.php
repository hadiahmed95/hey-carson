<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\MigrateMerchantsAsClients::class,
        \App\Console\Commands\GetClientSourceCommand::class,
        \App\Console\Commands\ImportShopifyExpertsFromSheet::class,
        \App\Console\Commands\ChangeProjectStatusToInProgress::class,
        \App\Console\Commands\ArchiveProject::class,
        \App\Console\Commands\RestoreProject::class,
        \App\Console\Commands\ExpireProjectQuote::class,
        \App\Console\Commands\MarkExpertAvailable::class,
        \App\Console\Commands\MarkExpertUnavailable::class,
        \App\Console\Commands\MarkExpertDisable::class,
        \App\Console\Commands\MarkExpertEnable::class,
        \App\Console\Commands\UpdateInvalidPartnerUsers::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('project:release')->everySecond();
        $schedule->command('project:complete')->everyMinute();
        $schedule->command('project:complete:reminder')->daily();
        $schedule->command('project:expire:quote')->everyMinute();
        $schedule->command('project:offer:reminder')->daily();
        $schedule->command('calculate:response-times')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * @return array
     */
    protected function bootstrappers(): array
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }
}
