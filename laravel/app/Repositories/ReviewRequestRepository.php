<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Project;
use App\Models\Question;
use App\Models\ReviewRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ReviewRequestRepository
{
    /**
     * Get all review requests for a client's projects where the client hasn't reviewed yet.
     *
     * @param int $clientId
     * @return Collection
     */
    public function getReviewRequests(int $clientId): Collection
    {
        $projectIds = Project::query()
            ->where('client_id', $clientId)
            ->pluck('id');

        return ReviewRequest::query()
            ->whereIn('project_id', $projectIds)
            ->where('is_client_reviewed', false)
            ->with('expert')
            ->with('project')
            ->get();
    }
}
