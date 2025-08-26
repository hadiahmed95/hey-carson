<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Models\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Exception;

class QuoteRepository
{
    private int $paginationLimit;

    /**
     * Class constructor.
     *
     * Initializes the default pagination limit using the constant defined in the Constants class.
     */
    public function __construct()
    {
        $this->paginationLimit = Constants::DEFAULT_PAGINATION_LIMIT;
    }

    /**
     * Get all quote/offer records starting from requests table based on project conditions
     * 
     * Combines data from two scenarios:
     * - Scenario 1: Projects with status 'pending_match' and additional_experts (quotes table)
     * - Scenario 2: Projects with status != 'pending_match' (offers table)
     * Both scenarios exclude 'Direct Message' request types.
     * 
     * @param array $filters Filters to apply (e.g., per_page, search, status, page).
     * @return LengthAwarePaginator Paginated list of quotes/offers with related data.
     */
    public function getAllQuotes(array $filters): LengthAwarePaginator
    {
        try {
            $quotesFromScenario1 = $this->getQuotesFromScenario1($filters);
            $quotesFromScenario2 = $this->getQuotesFromScenario2($filters);
            
            $allQuotes = $quotesFromScenario1->merge($quotesFromScenario2);
            
            if (!empty($filters['search'])) {
                $allQuotes = $this->applySearchOnCollection($allQuotes, $filters['search']);
            }
            
            if (!empty($filters['status'])) {
                $allQuotes = $allQuotes->filter(function ($item) use ($filters) {
                    return $item->payment_status === $filters['status'];
                });
            }
            
            return $this->paginateCollection($allQuotes, $filters);
        } catch (\Exception $e) {
            return new LengthAwarePaginator(
                collect([]),
                0,
                $filters['per_page'] ?? $this->paginationLimit,
                $filters['page'] ?? 1
            );
        }
    }

    /**
     * Scenario 1: Fetch quotes data
     * 
     * Conditions:
     * - projects.status = 'pending_match' 
     * - projects.additional_experts IS NOT NULL
     * - requests.type != 'Direct Message'
     * 
     * Data flow: requests → projects → quotes → client/expert/expertProfile relationships
     * 
     * @param array $filters Filters to apply during data fetching.
     * @return Collection Collection of stdClass objects containing quote data with relationships.
     */
    private function getQuotesFromScenario1(array $filters): Collection
    {
        try {
            $requests = Request::query()
                ->where('type', '!=', 'Direct Message')
                ->with([
                    'project' => function ($query) {
                        $query->select('id', 'name', 'url', 'status', 'additional_experts', 'client_id')
                              ->where('status', 'pending_match')
                              ->whereNotNull('additional_experts');
                    },
                    'project.quotes.client:id,first_name,last_name,url,shopify_plan,photo',
                    'project.quotes.expert:id,first_name,last_name,url,photo',
                    'project.quotes.expertProfile:id,user_id,expert_type'
                ])
                ->get();

            $results = collect();

            foreach ($requests as $request) {
                if (!$request->project || 
                    $request->project->status !== 'pending_match' || 
                    $request->project->additional_experts === null ||
                    !$request->project->quotes) {
                    continue;
                }

                foreach ($request->project->quotes as $quote) {
                    $item = new \stdClass();
                    $item->hours = $quote->hours;
                    $item->deadline = $quote->deadline;
                    $item->created_at = $quote->created_at;
                    $item->rate = $quote->rate;
                    $item->project_name = $request->project->name;
                    $item->project_url = $request->project->url;
                    $item->payment_status = $quote->status;
                    $item->client_name = $quote->client ? trim($quote->client->first_name . ' ' . $quote->client->last_name) : '';
                    $item->client_url = $quote->client?->url;
                    $item->client_shopify_plan = $quote->client?->shopify_plan;
                    $item->client_photo = $quote->client?->photo;
                    $item->expert_name = $quote->expert ? trim($quote->expert->first_name . ' ' . $quote->expert->last_name) : '';
                    $item->expert_url = $quote->expert?->url;
                    $item->expert_type = $quote->expertProfile?->expert_type;
                    $item->expert_photo = $quote->expert?->photo;
                    
                    $results->push($item);
                }
            }

            return $results;
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Scenario 2: Fetch offers data
     * 
     * Conditions:
     * - projects.status != 'pending_match'
     * - requests.type != 'Direct Message'
     * 
     * Data flow: requests → projects → assignments → offers → client/expert/expertProfile relationships
     * Note: Includes all offers regardless of type or additional_experts value.
     * 
     * @param array $filters Filters to apply during data fetching.
     * @return Collection Collection of stdClass objects containing offer data with relationships.
     */
    private function getQuotesFromScenario2(array $filters): Collection
    {
        try {
            $requests = Request::query()
                ->where('type', '!=', 'Direct Message')
                ->with([
                    'project' => function ($query) {
                        $query->select('id', 'name', 'url', 'status', 'additional_experts', 'client_id')
                              ->where('status', '!=', 'pending_match');
                    },
                    'project.client:id,first_name,last_name,url,shopify_plan,photo',
                    'project.assignments.offers',
                    'project.assignments.offers.expert:id,first_name,last_name,url,photo',
                    'project.assignments.offers.expertProfile:id,user_id,expert_type'
                ])
                ->get();

            $results = collect();

            foreach ($requests as $request) {
                if (!$request->project || 
                    $request->project->status === 'pending_match' || 
                    !$request->project->assignments) {
                    continue;
                }

                foreach ($request->project->assignments as $assignment) {
                    foreach ($assignment->offers as $offer) {
                        $item = new \stdClass();
                        $item->hours = $offer->hours;
                        $item->deadline = null;
                        $item->created_at = $offer->created_at;
                        $item->rate = $offer->rate;
                        $item->project_name = $request->project->name;
                        $item->project_url = $request->project->url;
                        $item->payment_status = $offer->status;
                        $item->client_name = $request->project->client ? trim($request->project->client->first_name . ' ' . $request->project->client->last_name) : '';
                        $item->client_url = $request->project->client?->url;
                        $item->client_shopify_plan = $request->project->client?->shopify_plan;
                        $item->client_photo = $request->project->client?->photo;
                        $item->expert_name = $offer->expert ? trim($offer->expert->first_name . ' ' . $offer->expert->last_name) : '';
                        $item->expert_url = $offer->expert?->url;
                        $item->expert_type = $offer->expertProfile?->expert_type;
                        $item->expert_photo = $offer->expert?->photo;
                        
                        $results->push($item);
                    }
                }
            }

            return $results;
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Apply search filter on the merged collection of quotes/offers
     * 
     * Searches across:
     * - project_name (project names)
     * - client_name (concatenated client first_name + last_name)
     * - expert_name (concatenated expert first_name + last_name)
     * 
     * @param Collection $collection The collection to search within.
     * @param string $search The search term to filter by.
     * @return Collection Filtered collection containing only items matching the search criteria.
     */
    private function applySearchOnCollection(Collection $collection, string $search): Collection
    {
        return $collection->filter(function ($item) use ($search) {
            $projectMatch = !empty($item->project_name) && 
                           stripos($item->project_name, $search) !== false;
            
            $clientMatch = !empty($item->client_name) && 
                          stripos($item->client_name, $search) !== false;
            
            $expertMatch = !empty($item->expert_name) &&
                          stripos($item->expert_name, $search) !== false;
            
            return $projectMatch || $clientMatch || $expertMatch;
        });
    }

    /**
     * Manually paginate a collection of quotes/offers
     * 
     * Sorts the collection by created_at in descending order and applies
     * pagination with configurable per_page and page parameters.
     * 
     * @param Collection $collection The collection to paginate.
     * @param array $filters Filters containing pagination parameters (per_page, page).
     * @return LengthAwarePaginator Paginated result with navigation links and metadata.
     */
    private function paginateCollection(Collection $collection, array $filters): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? $this->paginationLimit;
        $page = $filters['page'] ?? 1;
        
        $sorted = $collection->sortByDesc('created_at');
        $total = $sorted->count();
        $items = $sorted->slice(($page - 1) * $perPage, $perPage)->values();
        
        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }

    /**
     * Get available filter options for quotes/offers.
     * 
     * Returns unique status values from both quotes and offers tables
     * that match the scenario conditions.
     * 
     * @return array Available filter options with 'statuses' key containing array of status values.
     */
    public function getFilterOptions(): array
    {
        try {
            $scenario1Statuses = $this->getQuotesFromScenario1([])->pluck('payment_status')->filter()->unique();
            $scenario2Statuses = $this->getQuotesFromScenario2([])->pluck('payment_status')->filter()->unique();
            
            $rawStatuses = $scenario1Statuses->merge($scenario2Statuses)
                                            ->unique()
                                            ->sort()
                                            ->values()
                                            ->toArray();

            return [
                'statuses' => $rawStatuses, // Return raw statuses
            ];
        } catch (\Exception $e) {
            return [
                'statuses' => [],
            ];
        }
    }
}