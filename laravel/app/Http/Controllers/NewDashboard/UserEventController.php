<?php

namespace App\Http\Controllers\NewDashboard;

use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Models\UserEvent;
use App\Repositories\EventRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserEventController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function updateBulk(): JsonResponse
    {
        $user = Auth::user();

        $user->userEvents()->update(['seen' => true]);

        CacheInvalidation::dispatch('user_events', $user->id);
        return response()->json(['message' => 'OK']);
    }

    /**
     * @param Request $request
     * @param EventRepository $eventRepository
     * @return JsonResponse
     */
    public function all(Request $request, EventRepository $eventRepository): JsonResponse
    {
        $filterTag = $request->get('event');

        return response()->json(['events' => $eventRepository->userEventsWithRequests($filterTag)]);
    }

    /**
     * @param UserEvent $userEvent
     * @return JsonResponse
     */
    public function update(UserEvent $userEvent): JsonResponse
    {
        $userEvent->update(['seen' => true]);
        CacheInvalidation::dispatch('user_events', $userEvent->user_id);

        return response()->json(['message' => 'OK']);

    }
}
