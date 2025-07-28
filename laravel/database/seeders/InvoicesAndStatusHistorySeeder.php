<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Request;
use App\Models\Assignment;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\ProjectHistory;
use Illuminate\Support\Str;
use Carbon\Carbon;


class InvoicesAndStatusHistorySeeder extends Seeder
{
    public function run(): void
    {
        $request = Request::query()
            ->where('type', 'Matched')
            ->oldest()
            ->first();

        if (!$request) {
            $this->command->info('No request with type "Matched" found.');
            return;
        }

        $clientName = $request->client->first_name . ' ' . $request->client->last_name ?? 'Client 1';
        $expertName = $request->expert->first_name . ' ' . $request->expert->last_name ?? 'Expert 1';
        $clientId = $request->client_id;

        ProjectHistory::query()->create([
            'project_id' => $request->project_id,
            'changed_by_name' => $clientName,
            'changed_by_id' => $clientId,
            'role' => 'client',
            'action' => "{$clientName} submitted the project.",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $assignment = Assignment::query()->create([
            'project_id' => $request->project_id,
            'expert_id' => $request->expert_id,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info("Assignment created with ID {$assignment->id}");

        ProjectHistory::query()->create([
            'project_id' => $request->project_id,
            'changed_by_name' => $clientName,
            'changed_by_id' => $clientId,
            'role' => 'client',
            'action' => "{$clientName} added {$expertName} to the project.",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $offersData = [
            ['type' => 'offer', 'status' => 'paid'],
            ['type' => 'scope', 'status' => 'paid'],
            ['type' => 'scope', 'status' => 'send'],
        ];

        $offers = [];

        foreach ($offersData as $data) {
            $hours = rand(5, 12);
            $rate = rand(90, 120);

            $offer = Offer::query()->create([
                'expert_id' => $request->expert_id,
                'assignment_id' => $assignment->id,
                'type' => $data['type'],
                'status' => $data['status'],
                'hours' => $hours,
                'rate' => $rate,
                'deadline' => Carbon::now()->addDays(rand(3, 10)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $offers[] = $offer;
            $this->command->info("Offer created: ID {$offer->id}, type: {$offer->type}, status: {$offer->status}");
        }

        foreach ($offers as $offer) {
            if ($offer->status === 'paid') {
                $totalPrice = $offer->hours * $offer->rate;
                $payment = Payment::query()->create([
                    'click_id' => null,
                    'user_id' => $clientId,
                    'project_id' => $request->project_id,
                    'offer_id' => $offer->id,
                    'expert_id' => $request->expert_id,
                    'stripe_payment_id' => 'ch_' . Str::random(14),
                    'amount' => $offer->hours, // amount = hours
                    'price' => $offer->rate,
                    'total' => $totalPrice,
                    'status' => 'succeeded',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->command->info("Payment created for offer ID {$offer->id}, payment ID {$payment->id}");

                ProjectHistory::query()->create([
                    'project_id' => $request->project_id,
                    'changed_by_name' => $expertName,
                    'changed_by_id' => $request->expert_id,
                    'role' => 'expert',
                    'action' => "{$expertName} sent a custom quote.",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                ProjectHistory::query()->create([
                    'project_id' => $request->project_id,
                    'changed_by_name' => $clientName,
                    'changed_by_id' => $clientId,
                    'role' => 'client',
                    'action' => "{$clientName} accepted and paid a quote.",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Seeder completed successfully.');
    }

}
