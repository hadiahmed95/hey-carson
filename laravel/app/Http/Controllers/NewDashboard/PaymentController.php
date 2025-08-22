<?php

namespace App\Http\Controllers\NewDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCardRequest;
use App\Models\Project;
use App\Models\SavedCard;
use App\Repositories\OfferRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly OfferRepository $offerRepository
    ) {
    }

    /**
     * Save a new payment card for the authenticated user
     *
     * @param SaveCardRequest $request
     * @return JsonResponse
     */
    public function saveCard(SaveCardRequest $request): JsonResponse
    {
        $user = \Auth::user();

        $cardData = [
            'payment_id' => $request->input('payment_id'),
            'last_digits' => $request->input('last_digits'),
            'card_type' => $request->input('card_type'),
            'exp_date' => $request->input('exp_date'),
        ];

        $result = $this->paymentRepository->saveCard($user, $cardData);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'saved_card' => $result['saved_card'] ?? null,
            ], $result['status_code']);
        } else {
            return response()->json([
                'error' => $result['error']
            ], $result['status_code']);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function cardPayment(Request $request): JsonResponse
    {
        $user = Auth::user();

        $projectId = $request->get('project_id');
        $project = Project::find($projectId);

        $selectedPack = $request->get('selected_pack');
        $selectedCard = SavedCard::find($request->get('selected_card_id'));

        // Validate request data
        $validation = $this->paymentRepository->validatePaymentData($user, $project, $selectedCard);
        if (!$validation['success']) {
            return response()->json(['error' => $validation['error']], $validation['status_code']);
        }

        if ($project->additional_experts && $project->status === Project::PENDING_MATCH) {
            $result = $this->offerRepository->convertQuoteToOfferAndMatchExpert($project, $selectedPack, $request);

            if (!$result['success']) {
                return response()->json(['error' => $result['error']], $result['status_code']);
            }

            $selectedPack['offer_id'] = $result['offer_id'];
        }

        $result = $this->paymentRepository->processCardPayment($user, $project, $selectedPack, $selectedCard);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'payment' => $result['payment'],
                ]);
        } else {
            $response = ['error' => $result['error']];

            if (isset($result['decline_code'])) {
                $response['decline_code'] = $result['decline_code'];
            }

            return response()->json($response, $result['status_code']);
        }
    }
}
