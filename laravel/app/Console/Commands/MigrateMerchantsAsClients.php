<?php

namespace App\Console\Commands;

use App\Events\SendEmail;
use App\Mail\MerchantsUpdatePassReq;
use App\Mail\MigratedMerchants;
use App\Models\MigratedMerchant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class MigrateMerchantsAsClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-merchants-as-clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer merchants from migrated_users table to users table';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->updateSuppressionList();

        $batchCount = 2000;
        $merchants = MigratedMerchant::query()
            ->with('store')
            ->where('role_id', 2)
            ->whereNot('status', 'Deleted')
            ->whereNotNull('refer_token')
            ->whereNull('suppression_reason')
            ->get();

        $oldUsers = User::all();
        $alreadyExistedEmails = [];
        $totalMerchants = [];
        $newClients = [];

        foreach ($oldUsers as $oldUser) {
            $alreadyExistedEmails[strtolower($oldUser['email'])] = $oldUser['id'];
        }

        unset($oldUsers);

        foreach ($merchants as $merchant) {
            $totalMerchants[] = [
                'first_name'     => $merchant['firstname'],
                'last_name'      => $merchant['lastname'],
                'email'          => strtolower($merchant['email']),
                'password'       => $merchant['password'],
                'url'            => $merchant->store ? $merchant->store['url'] : Null,
                'role_id'        => Role::CLIENT,
                'is_migrated'   => 1,
            ];
        }

        dump('Total number of merchants: ' . count($totalMerchants));

        foreach ($totalMerchants as $merchant) {
            if (!array_key_exists($merchant['email'], $alreadyExistedEmails)) {
                $newClients[] = $merchant;
            }
        }

        dump('Merchants remaining to migrate: ' . count($newClients));
        dump("About to migrate the batch of $batchCount merchants: ");

        $migratedMerchantsCount = 0;

        foreach ($newClients as $newClient) {
            $merchantName = [
                'firstname' => $newClient['first_name'],
                'lastname' => $newClient['last_name'],
            ];

            if (!$newClient['password'] || strlen($newClient['password']) > 60) {
                $newClient['password'] = Hash::make('{k29Wvb9jQ80');

                $template = new MerchantsUpdatePassReq($merchantName);
            } else {
                $template = new MigratedMerchants($merchantName);
            }

//             SendEmail::dispatch('hamid.heycarson@gmail.com', $template);
//             break;

            $user = User::create($newClient);

            if ($user instanceof User && $user->id) {
                SendEmail::dispatch($newClient['email'], $template);
            }

            dump($migratedMerchantsCount . ': ' . $user->first_name . ' ' . $user->last_name . ' ' . $user->email . ': migrated successfully');

            $migratedMerchantsCount++;

            if ($migratedMerchantsCount === $batchCount)
                break;
        }

        dump("Successfully migrated merchants the batch of $batchCount");
    }

    private function updateSuppressionList(): void
    {
        $jsonContent = File::get(public_path('suppression_list.json'));
        $records = json_decode($jsonContent, true);  // Decode into an array

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON format.");
            return;
        }

        $emailAddresses = [];
        $suppressionReasons = [];

        foreach ($records as $record) {
            $emailAddresses[] = $record['email_address'];
            $suppressionReasons[$record['email_address']] = $record['suppression_reason'];
        }

        $cases = '';
        foreach ($emailAddresses as $email) {
            $cases .= "WHEN email = '" . $email . "' THEN '" . $suppressionReasons[$email] . "' ";
        }


        $query = "
            UPDATE migrated_merchants
            SET suppression_reason = CASE
                $cases
                ELSE suppression_reason
            END
            WHERE email IN ('" . implode("', '", $emailAddresses) . "')
            AND (suppression_reason IS NULL OR suppression_reason = '')
        ";

        dump($query);

        \DB::statement($query);

        $this->info("Suppression List updated.");
    }
}
