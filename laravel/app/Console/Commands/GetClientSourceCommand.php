<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetClientSourceCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:source';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command get the client source using partner id';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $clients = User::whereNotNull('partner_id')
            ->whereNull('source')
            ->get(['id', 'partner_id']);

        $updatedClients = [];

        foreach ($clients as $client) {
            $partnerName = app('referral_api_public')->getPartner($client->partner_id);

            if ($partnerName) {
                $client->source = $partnerName;
                $updatedClients[] = $client;
            }
        }

        if (!empty($updatedClients)) {
            foreach ($updatedClients as $client) {
                $client->save();
            }
        } else {
            Log::info("No clients needed source update.");
        }
    }

}
