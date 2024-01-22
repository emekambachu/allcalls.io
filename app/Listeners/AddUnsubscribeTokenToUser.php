<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUnsubscribeTokenToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        Log::debug('AddUnsubscribeToken: Handling event');

        // Check if 'user' and 'email' keys are available in the event data
        if (!isset($event->data['user']['email'])) {
            Log::debug('AddUnsubscribeToken: No email found in event data');
        }

        $email = $event->data['user']['email'];


        $user = User::where('email', $email)->first();

        Log::debug('AddUnsubscribeToken: Checking if user exists', [
            'email' => $email, 'exists' => $user,
            'Unsubscribe token does not exist' => !$user->unsubscribeToken,
        ]);

        if ($user && !$user->unsubscribeToken) {
            Log::debug('AddUnsubscribeToken: Generating unsubscribe token for user', ['email' => $email]);
            $user->generateUnsubscribeToken();
        }


        return true;
    }
}
