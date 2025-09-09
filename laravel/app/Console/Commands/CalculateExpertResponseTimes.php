<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateExpertResponseTimes extends Command
{
    protected $signature = 'calculate:response-times';
    protected $description = 'Calculate expert response times after offer messages (text-based)';

    public function handle()
    {
        $totalProcessed = 0;
        $offerFound = 0;
        $clientTextResponseFound = 0;
        $expertResponseFound = 0;

        // Get assignments that match the SQL query's filtering logic
        $assignments = DB::table('assignments as a')
            ->where('a.is_active', true)
            ->whereNotIn('a.project_id', function($q) {
                $q->select('project_id')
                    ->from('messages')
                    ->where('user_id', 21)
                    ->where('user_type', 'client');
            })
            ->whereIn('a.project_id', function($q) {
                $q->select('project_id')
                    ->from('messages')
                    ->where('created_at', '>=', now()->subMonths(6))
                    ->distinct();
            })
            ->get(['a.id', 'a.expert_id', 'a.project_id']);

        foreach ($assignments as $assignment) {
            $totalProcessed++;

            // Get expert's first offer message (MIN time per project)
            $offerMessage = DB::table('messages')
                ->where('project_id', $assignment->project_id)
                ->where('type', 'offer')
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$offerMessage) {
                continue;
            }
            $offerFound++;

            // Get first client TEXT message after the offer
            $clientMessage = DB::table('messages')
                ->where('project_id', $assignment->project_id)
                ->where('user_type', 'client')
                ->where('type', 'text')
                ->where('created_at', '>', $offerMessage->created_at)
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$clientMessage) {
                continue;
            }
            $clientTextResponseFound++;

            // Get expert's first response after client's text message
            $expertResponse = DB::table('messages')
                ->where('project_id', $assignment->project_id)
                ->where('user_type', 'expert')
                ->where('user_id', $assignment->expert_id) // Ensure it's the same expert
                ->where('created_at', '>', $clientMessage->created_at)
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$expertResponse) {
                continue;
            }
            $expertResponseFound++;

            // Update assignment (same structure as before)
            Assignment::where('id', $assignment->id)->update([
                'total_response_time' => strtotime($expertResponse->created_at) - strtotime($clientMessage->created_at),
                'response_pair_count' => 1,
            ]);
        }

        $this->info('Calculation completed!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total assignments processed', $totalProcessed],
                ['Assignments with offer messages', $offerFound],
                ['Offers with client TEXT responses', $clientTextResponseFound],
                ['Client texts with expert follow-up', $expertResponseFound],
                ['Successfully calculated pairs', $expertResponseFound]
            ]
        );
    }
}
