<?php

namespace App\Http\Controllers\NewDashboard\Expert;

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
use App\Models\Quote;
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

        return $this->offerRepository->createOfferOrQuote($validatedData, $project, $user, $request);
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
