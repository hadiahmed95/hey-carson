<?php

namespace App\Http\Controllers\NewDashboard\Client;

use App\Events\PaymentDeclined;
use App\Events\SendEmail;
use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Mail\Expert\ProjectPaymentDeclinedMail;
use App\Mail\Expert\ProjectPaymentMail;
use App\Mail\Client\ProjectPaymentMail as ProjectClientPaymentMail;
use App\Mail\Expert\ProjectPaymentScopeMail;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Project;
use App\Models\Quote;
use App\Models\User;
use App\Models\UserEvent;
use App\Repositories\OfferRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * @param OfferRepository $offerRepository
     */
    public function __construct(private readonly OfferRepository $offerRepository) {}

    /**
     * @param Request $request
     * @param Project $project
     * @param Offer $offer
     * @return JsonResponse
     */
    public function update(Request $request, Project $project, Offer $offer): JsonResponse
    {
        $user = Auth::user();

        $result = $this->offerRepository->processPostPayment($project, $offer, $user, $request);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'offer' => $result['offer']
            ]);
        } else {
            return response()->json(['message' => $result['message']], 403);
        }
    }

    /**
     * @param Project $project
     * @param int $offerOrQuoteId
     * @return JsonResponse
     */
    public function decline(Project $project, int $offerOrQuoteId): JsonResponse
    {
        $user = Auth::user();
        $result = $this->offerRepository->declineOffer($project, $offerOrQuoteId, $user);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'offer_or_quote' => $result['offer_or_quote']
            ]);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }
}
