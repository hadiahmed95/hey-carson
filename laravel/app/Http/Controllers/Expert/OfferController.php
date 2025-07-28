<?php

namespace App\Http\Controllers\Expert;

use App\Events\SendEmail;
use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Mail\Client\ProjectOfferMail;
use App\Mail\Client\ProjectScopeMail;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Project;
use App\Models\UserEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OfferRepository;

class OfferController extends Controller
{
    private OfferRepository $offerRepository;

    /**
     * @param OfferRepository $offerRepository
     */
    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    /**
     * @param CreateOfferRequest $request
     * @param Project $project
     * @return JsonResponse
     */
    public function create(CreateOfferRequest $request, Project $project): JsonResponse
    {
        $validatedData = $request->validated();
        $user = Auth::user();
        if ($project->activeAssignment->expert_id === $user->id) {
            $offer = Offer::create([
                'assignment_id'     =>  $project->activeAssignment->id,
                'expert_id'         => $user->id,
                'type'              => $validatedData['type'],
                'hours'             => $validatedData['hours'],
                'rate'              => $validatedData['rate'],
                'deadline'          => Carbon::parse($validatedData['deadline'])
            ]);

            $project->update(['status' => 'pending_payment']);

            $request->merge(['runObserver' => true]);
            $project->messages()->create([
                'type' => 'offer',
                'user_type' => 'expert',
                'offer_id' => $offer->id,
                'seen' => 1
            ]);

            UserEvent::create([
                'user_id' => $project->client_id,
                'project_id' => $project->id,
                'event_id' => $validatedData['type'] === 'offer' ? Event::CLIENT_PROJECT_OFFER : Event::CLIENT_PROJECT_SCOPE,
            ]);

            CacheInvalidation::dispatch('user_events', $project->client_id);
            if ($validatedData['type'] === 'offer') {
                SendEmail::dispatch($project->client, new ProjectOfferMail($project->client, $user, $project));
            } else {
                SendEmail::dispatch($project->client, new ProjectScopeMail($project->client, $user, $project));
            }

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function update(UpdateOfferRequest $request, Project $project): JsonResponse
    {
        $validatedData = $request->validated();
        $user = Auth::user();
        if ($project->activeAssignment->expert_id === $user->id) {
            $this->offerRepository->update($project, $user, $validatedData);
            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }
}
