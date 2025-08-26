<?php

namespace App\Http\Controllers\NewDashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuoteSentResource;
use App\Repositories\QuoteRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class QuoteSentController extends Controller

{
    /**
     * QuoteSentController constructor.
     * 
     * @param QuoteSentRepository $quoteSentRepository Repository for quote operations
     */
    public function __construct(private QuoteRepository $quoteRepository)
    {}

    /**
     * Get all quotes sent with filtering, search and pagination.
     * 
     * Retrieves a paginated list of quotes based on provided filters.
     * Uses QuoteSentResource to apply business logic transformations.
     * 
     * @param Request $request HTTP request containing filter parameters
     * @return JsonResponse Paginated quotes data with business metrics or error response
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $quotes = $this->quoteRepository->getAllQuotes($request->all());
            $quotesCount = $quotes->total();

            $transformedQuotes = $quotes->setCollection(
                $quotes->getCollection()->map(function ($quote) {
                    return new QuoteSentResource($quote);
                })
            );

            return response()->json([
                'quotes' => $transformedQuotes,
                'quotes_count' => $quotesCount
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get available filter options for quotes.
     * 
     * Retrieves all available filter options for quotes filtering.
     * 
     * @return JsonResponse Filter options data or error response
     */
    public function getFilterOptions(): JsonResponse
    {
        try {
            $filterOptions = $this->quoteRepository->getFilterOptions();
            
            $formattedStatuses = collect($filterOptions['statuses'])->map(function ($status) {
                return QuoteSentResource::formatPaymentStatus($status);
            })->unique()->sort()->values()->toArray();

            return response()->json([
                'statuses' => $formattedStatuses
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}