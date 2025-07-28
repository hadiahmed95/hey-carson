<?php

namespace App\Repositories;

use App\Events\SendEmail;
use App\Mail\Client\ProjectQuoteEditMail;
use App\Models\Offer;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class OfferRepository
{
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
}
