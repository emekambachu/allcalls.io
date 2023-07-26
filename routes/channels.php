<?php

use Illuminate\Support\Facades\Log;
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

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('User.Status.Online.{callTypeId}', function ($user, $callTypeId) {
    Log::debug($user->id . ' turned on call type ' . $callTypeId);
    return true;
});

Broadcast::channel('User.Status.Offline.{callTypeId}', function ($user, $callTypeId) {
    Log::debug($user->id . ' turned off call type ' . $callTypeId);
    return true;
});
