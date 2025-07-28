<?php

namespace App\Http\Controllers\Expert;

use App\Events\CacheInvalidation;
use App\Http\Controllers\Controller;
use App\Models\ExpertFund;
use App\Models\Message;
use App\Models\Payout;
use App\Models\UserEvent;
use App\Repositories\EventRepository;
use App\Services\CacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
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

        return response()->json(['events' => $eventRepository->all($filterTag, Auth::user()->role_id)]);
    }

    public function update(UserEvent $userEvent)
    {
        $userEvent->update(['seen' => true]);
        CacheInvalidation::dispatch('user_events', $userEvent->user_id);

        return response()->json(['message' => 'OK']);

    }

    public function messages()
    {
        $user = Auth::user();

        $projectId = $user->activeAssignments()->pluck('project_id');

        $messages = Message::query()->with(['project', 'user'])
            ->whereIn('project_id', $projectId)
            ->whereNot('user_id', $user->id);

        $messages->update(['seen' => true]);

        return response()->json(['message' => 'OK']);
    }

    public function message(Message $message)
    {
        $message->update(['seen' => true]);

        return response()->json(['message' => 'OK']);
    }
}
