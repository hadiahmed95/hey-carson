<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request;
use App\Models\Quote;
use Illuminate\Support\Carbon;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        $request = Request::query()
            ->where('type', 'Quote Request')
            ->with('project')
            ->oldest()
            ->first();

        if (!$request || !$request->project) {
            $this->command->warn("No suitable request or associated project found.");
            return;
        }

        $project = $request->project;

        if (!$project->is_additional_experts || !is_array($project->additional_experts)) {
            $this->command->warn("Project does not have additional experts.");
            return;
        }

        $expertIds = collect($project->additional_experts)
            ->filter(fn($id) => $id !== $project->preferred_expert_id)
            ->take(2)
            ->values();

        if (count($expertIds) < 2 || !$project->preferred_expert_id) {
            $this->command->warn("Not enough expert IDs to create 3 quotes.");
            return;
        }

        $allExpertIds = collect([$project->preferred_expert_id])->merge($expertIds);

        foreach ($allExpertIds as $expertId) {
            Quote::query()->create([
                'expert_id'  => $expertId,
                'client_id'  => $request->client_id,
                'project_id' => $request->project_id,
                'hours'      => rand(10, 20),
                'deadline'   => Carbon::now()->addDays(rand(5, 10)),
                'rate'       => 95.00,
                'status'     => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("3 quotes created for request ID {$request->id}.");
    }
}
