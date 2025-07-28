<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateInvalidPartnerUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:update-invalid-partners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users with invalid partner IDs to Website Direct and clear related fields';

    /**
     * List of valid partner IDs
     */
    private array $invalidPartnerIds = [
        'e73ccde4-ee54-4ae7-b4c3-de18553a94c1',
        'a4d58061-c249-445f-8ab1-d81f7c930f35',
        'c9c77fa6-0909-469b-b0e6-77a9404989f8',
        'edab2201-d0de-48c8-8723-d44105a12240',
        '5e046a1f-8e78-40f2-8612-e7010367f8fc',
        'd261acfc-f067-4940-b66d-7d43aa04ec5d',
        '0d515487-55af-482e-b62f-1e6eab71a016',
        'ee901c6e-9411-4d6f-95ba-2a2e491748c9',
        '3e0d7ff7-7059-4547-a3a7-cba4d439575a',
        '30404ba5-dec6-451d-8dbf-c0728154632a',
        '7e230f00-d669-4b6e-a656-28147e7575f3',
        'c908472c-f1ac-494c-8f3e-3a043a498514',
        'ee8d4246-1565-4995-a9f0-cdd7e8bd1350',
        '39ab0c08-f58a-430c-b585-60863caff220',
        'c3a019f0-66c2-4f41-8c4a-9320df7f3598'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = User::whereNotNull('partner_id')
            ->whereIn('partner_id', $this->invalidPartnerIds)
            ->update([
                'source' => 'Website Direct',
                'click_id' => null,
                'program_id' => null,
                'partner_id' => null
            ]);

        $this->info("Updated $count users with invalid partner IDs");
        Log::info("Updated $count users with invalid partner IDs");
    }
}
