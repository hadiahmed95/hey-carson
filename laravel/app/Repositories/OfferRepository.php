<?php

namespace App\Repositories;

use App\Events\CacheInvalidation;
use App\Events\PaymentDeclined;
use App\Events\SendEmail;
use App\Mail\Client\ProjectOfferMail;
use App\Mail\Client\ProjectQuoteEditMail;
use App\Mail\Client\ProjectScopeMail;
use App\Mail\Expert\ProjectPaymentDeclinedMail;
use App\Mail\Expert\ProjectPaymentScopeMail;
use App\Mail\Expert\ProjectPaymentMail;
use App\Mail\Client\ProjectPaymentMail as ProjectClientPaymentMail;
use App\Models\Banner;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Project;
use App\Models\Quote;
use App\Models\User;
use App\Models\UserEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Array_;

class OfferRepository
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {}

    /**
     * Update existing unpaid offer
     *
     * @param Project $project The project instance.
     * @param User $user The user instance
     * @param $validatedData
     * @return JsonResponse
     */
    public function update(Project $project, User $user, $validatedData): JsonResponse
    {
        $offer = Offer::where('assignment_id', $project->activeAssignment->id)
            ->where('status', 'send')
            ->first();

        if ($offer) {

            $offer->update([
                'hours' => $validatedData['hours'],
                'rate' => $validatedData['rate'],
                'deadline' => Carbon::parse($validatedData['deadline'])
            ]);

            SendEmail::dispatch($project->client, new ProjectQuoteEditMail($project->client, $user, $project));

            return response()->json(['message' => 'Offer updated successfully']);
        }

        return response()->json(['message' => 'Offer not found or already processed'], 404);
    }

    /*
     * Update the status of offers related to the given project to 'expired'.
     *
     * @param Offer $offer
     * @return void
     */
    public function updateOfferStatusToExpired(Offer $offer): void
    {
        $offer->update(['status' => 'expired']);
    }

    /**
     * Create offer or quote based on project conditions
     *
     * @param array $validatedData
     * @param Project $project
     * @param $user
     * @param $request
     * @return JsonResponse
     */
    public function createOfferOrQuote(array $validatedData, Project $project, $user, $request): JsonResponse
    {
        $hasAdditionalExperts = !empty($project->additional_experts) &&
            is_array($project->additional_experts) &&
            count($project->additional_experts) > 0;
        $isPendingMatched = $project->status === 'pending_match';

        if ($hasAdditionalExperts && $isPendingMatched) {
            return $this->createQuote($validatedData, $project, $user);
        } else {
            return $this->createOffer($validatedData, $project, $user, $request);
        }
    }

    /**
     * Create quote for projects with additional experts
     *
     * @param array $validatedData
     * @param Project $project
     * @param $user
     * @return JsonResponse
     */
    private function createQuote(array $validatedData, Project $project, $user): JsonResponse
    {
        $quote = Quote::create([
            'expert_id'     => $user->id,
            'client_id'     => $project->client_id,
            'project_id'    => $project->id,
            'hours'         => $validatedData['hours'],
            'rate'          => $validatedData['rate'],
            'deadline'      => Carbon::parse($validatedData['deadline']),
            'status'        => 'send'
        ]);

        UserEvent::create([
            'user_id' => $project->client_id,
            'project_id' => $project->id,
            'event_id' => Event::CLIENT_PROJECT_OFFER,
        ]);

        return response()->json([
            'message' => 'Quote created successfully',
            'quote' => $quote
        ]);
    }

    /**
     * Create offer for regular projects
     * @param array $validatedData
     * @param Project $project
     * @param $user
     * @param $request
     * @param bool $sendNotification
     * @param bool $returnArray - Return array instead of JsonResponse for internal calls
     * @return JsonResponse|array
     */
    private function createOffer(array $validatedData, Project $project, $user, $request, bool $sendNotification = true, bool $returnArray = false): JsonResponse|array
    {
        if (!$project->activeAssignment || $project->activeAssignment->expert_id !== $user->id) {
            if ($project->additional_experts && $project->status === Project::PENDING_MATCH) {
                $this->projectRepository->assignPreferred($project, $user->id);
            } else {
                if ($returnArray) {
                    return [
                        'success' => false,
                        'error' => 'Unauthorized access to project',
                        'status_code' => 403
                    ];
                }
                return response()->json(['success' => false], 403);
            }
        }

        try {
            $offer = Offer::create([
                'assignment_id' => $project->activeAssignment->id,
                'expert_id' => $user->id,
                'type' => $validatedData['type'],
                'hours' => $validatedData['hours'],
                'rate' => $validatedData['rate'],
                'deadline' => Carbon::parse($validatedData['deadline'])
            ]);

            $project->update(['status' => 'pending_payment']);

            $request->merge(['runObserver' => true]);

            // Create message for offer
//        $project->messages()->create([
//            'type' => 'offer',
//            'user_type' => 'expert',
//            'offer_id' => $offer->id,
//            'seen' => 1
//        ]);

            // Create user event
            if ($sendNotification) {
                UserEvent::create([
                    'user_id' => $project->client_id,
                    'project_id' => $project->id,
                    'event_id' => $validatedData['type'] === 'offer' ? Event::CLIENT_PROJECT_OFFER : Event::CLIENT_PROJECT_SCOPE,
                ]);
            }

            // Send appropriate email
            if ($sendNotification) {
                if ($validatedData['type'] === 'offer') {
                    SendEmail::dispatch($project->client, new ProjectOfferMail($project->client, $user, $project));
                } else {
                    SendEmail::dispatch($project->client, new ProjectScopeMail($project->client, $user, $project));
                }
            }

            // Cache invalidation
//        CacheInvalidation::dispatch('user_events', $project->client_id);

            $responseData = [
                'success' => true,
                'message' => 'Offer created successfully',
                'offer' => $offer
            ];

            // Return array for internal calls, JsonResponse for external calls
            if ($returnArray) {
                return $responseData;
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            \Log::error('Failed to create offer: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            $errorResponse = [
                'success' => false,
                'error' => 'Failed to create offer',
                'status_code' => 500
            ];

            if ($returnArray) {
                return $errorResponse;
            }

            return response()->json(['error' => 'Failed to create offer'], 500);
        }
    }

    /**
     * Process payment for an offer
     *
     * @param Project $project
     * @param Offer $offer
     * @param $user
     * @param Request $request
     * @return array
     */
    public function processPostPayment(Project $project, Offer $offer, $user, Request $request): array
    {
        if ($project->client_id !== $user->id) {
            return [
                'success' => false,
            ];
        }

        $offer->update(['status' => 'paid']);
        $offer->refresh();

        $this->createProjectMessage($project, $offer, $request);

        $project->update(['status' => 'in_progress']);

        $this->createUserEvents($project, $offer);

        // $this->invalidateCache($project);

        $this->sendEmailNotifications($project, $offer, $user);

        return [
            'success' => true,
            'message' => 'OK',
            'offer' => $offer
        ];
    }

    /**
     * Create project banner message
     *
     * @param Project $project
     * @param Offer $offer
     * @param Request $request
     * @return void
     */
    private function createProjectMessage(Project $project, Offer $offer, Request $request): void
    {
        $request->merge(['runObserver' => true]);
        //Todo: Implement this during chat feature
//        $bannerId = $offer->type === 'offer'
//            ? Banner::SUCCESS_CLIENT_OFFER
//            : Banner::SUCCESS_CLIENT_SCOPE;
//
//        $project->messages()->create([
//            'type' => 'banner',
//            'user_type' => 'client',
//            'banner_id' => $bannerId,
//            'seen' => 1
//        ]);
    }

    /**
     * Create user events for payment
     *
     * @param Project $project
     * @param Offer $offer
     * @return void
     */
    private function createUserEvents(Project $project, Offer $offer): void
    {
        $expertId = $project->activeAssignment->expert->id;

        // Expert event
        $expertEventId = $offer->type === 'offer'
            ? Event::EXPERT_PROJECT_PAYMENT_OFFER
            : Event::EXPERT_PROJECT_PAYMENT_SCOPE;

        UserEvent::create([
            'user_id' => $expertId,
            'project_id' => $project->id,
            'event_id' => $expertEventId,
        ]);

        // Client event
        UserEvent::create([
            'user_id' => $project->client_id,
            'project_id' => $project->id,
            'event_id' => Event::CLIENT_PROJECT_PAYMENT,
        ]);
    }

    /**
     * Invalidate relevant caches
     *
     * @param Project $project
     * @return void
     */
    private function invalidateCache(Project $project): void
    {
        $expertId = $project->activeAssignment->expert->id;

        CacheInvalidation::dispatch('user_events', $expertId);
        CacheInvalidation::dispatch('user_events', $project->client_id);
    }

    /**
     * Send email notifications
     *
     * @param Project $project
     * @param Offer $offer
     * @param $user
     * @return void
     */
    private function sendEmailNotifications(Project $project, Offer $offer, $user): void
    {
        $expert = $project->activeAssignment->expert;

        // Send email to expert
        if ($offer->type === 'offer') {
            SendEmail::dispatch($expert, new ProjectPaymentMail($expert, $user, $project));
        } else {
            SendEmail::dispatch($expert, new ProjectPaymentScopeMail($expert, $user, $project));
        }

        // Send email to client
        SendEmail::dispatch($user, new ProjectClientPaymentMail($user, $project));
    }

    /**
     * Convert selected quote to offer and match expert to project
     *
     * @param Project $project
     * @param array $selectedPack
     * @param Request $request
     * @return array
     */
    public function convertQuoteToOfferAndMatchExpert(Project $project, array $selectedPack, Request $request): array
    {
        try {
            if (!isset($selectedPack['offer_id'])) {
                return [
                    'success' => false,
                    'error' => 'Offer ID is required for pending match projects',
                    'status_code' => 400
                ];
            }

            $quote = Quote::find($selectedPack['offer_id']);

            if (!$quote) {
                return [
                    'success' => false,
                    'error' => 'Quote not found',
                    'status_code' => 404
                ];
            }

            $expert = User::find($quote->expert_id);

            if (!$expert) {
                return [
                    'success' => false,
                    'error' => 'Expert not found',
                    'status_code' => 404
                ];
            }

            $offerData = [
                'type'      => 'offer',
                'hours'     => $quote->hours,
                'rate'      => $quote->rate,
                'deadline'  => $quote->deadline
            ];

            // Create offer from the selected quote
            $result = $this->createOffer($offerData, $project, $expert, $request, false, true);

            if (!$result['success']) {
                return [
                    'success' => false,
                    'error' => $result['message'] ?? 'Failed to create offer',
                    'status_code' => $result['status_code'] ?? 500
                ];
            }

            // Update the lead request with the selected expert
            $leadRequest = \App\Models\Request::where('project_id', $project->id)->first();

            if ($leadRequest) {
                $leadRequest->update(['expert_id' => $quote->expert_id]);
            }

            // TODO: Send email notifications to other additional experts about project match
            $this->notifyAdditionalExperts($project, $expert);

            return [
                'success' => true,
                'offer_id' => $result['offer']['id']
            ];

        } catch (\Exception $e) {
            \Log::error('Failed to handle pending match project: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'quote_id' => $selectedPack['offer_id'] ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to process project matching',
                'status_code' => 500
            ];
        }
    }

    /**
     * Send notifications to other experts about project match
     *
     * @param Project $project
     * @param User $selectedExpert
     * @return void
     */
    private function notifyAdditionalExperts(Project $project, User $selectedExpert): void
    {
        try {
            if (!$project->additional_experts || !is_array($project->additional_experts)) {
                return;
            }

            $otherExpertIds = array_filter(
                $project->additional_experts,
                fn($expertId) => $expertId !== $selectedExpert->id
            );

            if (empty($otherExpertIds)) {
                return;
            }

            $otherExperts = User::whereIn('id', $otherExpertIds)->get();

            foreach ($otherExperts as $expert) {
                // TODO: Replace with actual email notification
                // SendEmail::dispatch($expert, new ProjectMatchedMail($expert, $selectedExpert, $project));
                \Log::info("Should send project matched notification to expert: {$expert->id}");
            }

        } catch (\Exception $e) {
            \Log::error('Failed to notify other experts: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'selected_expert_id' => $selectedExpert->id
            ]);
            // Don't throw exception as this is not critical for payment flow
        }
    }

    /**
     * @param Project $project
     * @param int $offerOrQuoteId
     * @param $user
     * @return array
     */
    public function declineOffer(Project $project, int $offerOrQuoteId, $user): array
    {
        if ($project->client_id !== $user->id) {
            return [
                'success' => false,
            ];
        }

        $isQuoteRequestWithAdditionalExperts = $project->additional_experts && $project->status === Project::PENDING_MATCH;

        if($isQuoteRequestWithAdditionalExperts) {
            $offerOrQuote = Quote::find($offerOrQuoteId);
        } else {
            $offerOrQuote = Offer::find($offerOrQuoteId);
        }

        $offerOrQuote->update(['status' => 'decline']);
        $offerOrQuote->refresh();

        // Broadcast event
//        Broadcast(new PaymentDeclined($project, $offer));

        // Create banner message
//        $project->messages()->create([
//            'type' => 'banner',
//            'user_type' => 'client',
//            'banner_id' => $offer->type === 'offer' ? 2 : 3,
//            'seen' => 1
//        ]);

        if(!$isQuoteRequestWithAdditionalExperts) {
            if ($offerOrQuote->type === 'offer') {
                $project->update(['status' => 'matched']);
            } else {
                $project->update(['status' => 'in_progress']);
            }
        }

        // Send email notification
        if(!$isQuoteRequestWithAdditionalExperts) {
            SendEmail::dispatch(
                $project->activeAssignment->expert,
                new ProjectPaymentDeclinedMail(
                    $project->activeAssignment->expert,
                    $project->client,
                    $project
                )
            );
        } else {
            //Todo: Need to send email here
        }

        return [
            'success' => true,
            'message' => 'OK',
            'offer_or_quote' => $offerOrQuote
        ];
    }
}
