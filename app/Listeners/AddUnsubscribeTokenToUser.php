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
        // Check if 'user' and 'email' keys are available in the event data
        if (!isset($event->data['user']['email'])) {
            return true;
        }

        $email = $event->data['user']['email'];


        $user = User::where('email', $email)->first();

        if ($user && !$user->unsubscribeToken()) {
            Log::debug('Generating unsubscribe token for user', ['email' => $email]);
            $user->generateUnsubscribeToken();
        }
    }
}
