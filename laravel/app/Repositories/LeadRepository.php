<?php

namespace App\Repositories;

use App\Models\ExpertStat;
use App\Models\Project;
use App\Models\Request as Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository
{
    /**
     * Get a single lead by ID with all related data
     *
     * @param Lead $lead
     * @return Lead
     */
    public function getLeadWithRelations(Lead $lead): Lead
    {
        // Load basic relations
        $lead->load([
            'project.activeAssignment.offers',
            'project.invoices',
            'project.history',
            'project',
            'client'
        ]);

        // Load conditional client quotes for Quote Request type
        if ($this->shouldLoadClientQuotes($lead)) {
            $this->loadClientQuotes($lead);
        }

        return $lead;
    }

    /**
     * Get leads for the authenticated expert
     *
     * @param int|null $expertId
     * @param string|null $type
     * @return Collection
     */
    public function getLeadsForExpert(?int $expertId = null, ?string $type = null): Collection
    {
        $expertId = $expertId ?: Auth::id();

        $query = Lead::query()
            ->where(function ($q) use ($expertId) {
                $q->where('expert_id', $expertId)
                    ->orWhereHas('project', function ($projectQuery) use ($expertId) {
                        $projectQuery->where('status', Project::PENDING_MATCH)
                            ->whereJsonContains('additional_experts', $expertId);
                    });
            })
            ->with(['project', 'client'])
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    /**
     * Get expert statistics
     *
     * @param int|null $expertId
     * @return ExpertStat|null
     */
    public function getExpertStats(?int $expertId = null): ?ExpertStat
    {
        $expertId = $expertId ?: Auth::id();

        $expertStat = ExpertStat::query()
            ->where('expert_id', $expertId)
            ->first();

        return $expertStat instanceof ExpertStat ? $expertStat : null;
    }

    /**
     * Check if client quotes should be loaded
     *
     * @param Lead $lead
     * @return bool
     */
    private function shouldLoadClientQuotes(Lead $lead): bool
    {
        return $lead->type === Lead::QUOTE_REQUEST && $lead->client && $lead->project->status === Project::PENDING_MATCH;
    }

    /**
     * Load client quotes for the specific project and expert
     *
     * @param Lead $lead
     * @return void
     */
    private function loadClientQuotes(Lead $lead): void
    {
        $lead->client->load(['quotesByClientId' => function ($query) use ($lead) {
            $query->where('project_id', $lead->project_id)
                ->where('expert_id', Auth::id());
        }]);
    }
}
