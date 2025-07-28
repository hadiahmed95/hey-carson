<?php

namespace App\Http\Controllers\Client;

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
use App\Models\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function update(Request $request, Project $project, Offer $offer)
    {
        $user = Auth::user();

        if ($project->client_id === $user->id) {
            $offer->update(['status' => 'paid']);

            $request->merge(['runObserver' => true]);
            $project->messages()->create([
                'type' => 'banner',
                'user_type' => 'client',
                'banner_id' => $offer->type === 'offer' ? Banner::SUCCESS_CLIENT_OFFER : Banner::SUCCESS_CLIENT_SCOPE,
                'seen' => 1
            ]);

            $project->update(['status' => 'in_progress']);
            $expertId = $project->activeAssignment->expert->id;
            UserEvent::create([
                'user_id' => $expertId,
                'project_id' => $project->id,
                'event_id' => $offer->type === 'offer' ? Event::EXPERT_PROJECT_PAYMENT_OFFER : Event::EXPERT_PROJECT_PAYMENT_SCOPE,
            ]);

            UserEvent::create([
                'user_id' => $project->client_id,
                'project_id' => $project->id,
                'event_id' => Event::CLIENT_PROJECT_PAYMENT,
            ]);

            CacheInvalidation::dispatch('user_events', $expertId);
            CacheInvalidation::dispatch('user_events', $project->client_id);

            if ($offer->type === 'offer') {
                SendEmail::dispatch($project->activeAssignment->expert, new ProjectPaymentMail($project->activeAssignment->expert, $user, $project));
            } else {
                SendEmail::dispatch($project->activeAssignment->expert, new ProjectPaymentScopeMail($project->activeAssignment->expert, $user, $project));
            }
            SendEmail::dispatch($user, new ProjectClientPaymentMail($user, $project));

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }

    public function decline(Request $request, Project $project, Offer $offer)
    {
        $user = Auth::user();

        if ($project->client_id === $user->id) {
            $offer->update(['status' => 'decline']);
            Broadcast(new PaymentDeclined($project, $offer));

//            $project->messages()->create([
//                'type' => 'banner',
//                'user_type' => 'client',
//                'banner_id' => $offer->type === 'offer' ? 2 : 3,
//                'seen' => 1
//            ]);
            if ($offer->type === 'offer') {
                $project->update(['status' => 'matched']);
            } else {
                $project->update(['status' => 'in_progress']);
            }
            SendEmail::dispatch($project->activeAssignment->expert, new ProjectPaymentDeclinedMail($project->activeAssignment->expert, $project->client, $project));

            return response()->json(['message' => 'OK']);
        } else {
            return response()->json(['message' => 'unauthorised'], 401);
        }
    }
}
