<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

/**
 *  Channel for real-time chat for a specific project ({projectId}),
 *  returning the receiver's ID and name for user identification.
 */
Broadcast::channel('chat.{projectId}', function ($receiver) {
    return ['id' => $receiver->id, 'name' => $receiver->name];
});

/**
 *  Channel for user notifications that allows any authenticated user
 *  to listen for their notifications.
 */
Broadcast::channel('notifications.{receiverId}', function ($user) {
    return true;
});

/**
 *  Channel for presence notifications that tracks user availability,
 *  returning the receiver's ID and name for identification.
 */
Broadcast::channel('presence.receiver', function ($receiver) {
    return ['id' => $receiver->id, 'name' => $receiver->name];
});

// Broadcast when an expert is online/offline
Broadcast::channel('users.online', function () {
    return true;
});

