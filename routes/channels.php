<?php

use App\Models\User;
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

Broadcast::channel('calls.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('user.{userId}.callStatus', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('User.Status.Online.{callTypeId}', function ($user, $callTypeId) {
    Log::debug($user->id . ' turned on call type ' . $callTypeId);
    return ['user' => [
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
    ]];
});

Broadcast::channel('User.Status.Offline.{callTypeId}', function ($user, $callTypeId) {
    Log::debug($user->id . ' turned off call type ' . $callTypeId);
    return ['user' => $user];
});

Broadcast::channel('active-users', function ($user) {
    return [ 'user_id' => $user->id ];
});
