<?php

namespace App\Http\Controllers\NewDashboard\Client;

use App\Http\Controllers\Controller;

use App\Http\Requests\CreateLeadRequest;
use App\Http\Requests\FreeQuoteRequest;
use App\Http\Requests\GetMatchedRequest;
use App\Models\Request as LeadRequest;
use App\Repositories\RequestRepository as LeadRequestRepository;
use Illuminate\Http\JsonResponse;

class LeadRequestController extends Controller
{
    public function __construct(
        private LeadRequestRepository $leadRequestRepository
    ) {}

    /**
     * @return JsonResponse
     */
    public function requests(): JsonResponse
    {
        $requests = $this->leadRequestRepository->getRequestsForClient();

        return response()->json([
            'requests' => $requests
        ]);
    }

    /**
     * @param GetMatchedRequest $request
     * @return JsonResponse
     */
    public function getMatched(GetMatchedRequest $request): JsonResponse
    {
        //Todo: Need to catch here attachments also
        return $this->leadRequestRepository->createRequest($request, LeadRequest::MATCHED);
    }

    /**
     * @param FreeQuoteRequest $request
     * @return JsonResponse
     */
    public function freeQuote(FreeQuoteRequest $request): JsonResponse
    {
        //Todo: Need to catch here attachments also
        return $this->leadRequestRepository->createRequest($request, LeadRequest::QUOTE_REQUEST);
    }

    /**
     * @param CreateLeadRequest $request
     * @return JsonResponse
     */
    public function create(CreateLeadRequest $request): JsonResponse
    {
        //Todo: Need to catch here attachments also
        return $this->leadRequestRepository->createRequest($request, LeadRequest::QUOTE_REQUEST, \Auth::user());
    }

    /**
     * @param $requestId
     * @return JsonResponse
     */
    public function request($requestId): JsonResponse
    {
        $request = $this->leadRequestRepository->getClientRequestWithQuotes($requestId);
        return response()->json([
            'request' => $request,
        ]);
    }

}
