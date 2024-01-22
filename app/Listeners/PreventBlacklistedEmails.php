<?php

namespace App\Listeners;

use App\Models\EmailBlacklist;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreventBlacklistedEmails
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
    public function handle($event)
    {
        // Check if 'user' and 'email' keys are available in the event data
        if (!isset($event->data['user']['email'])) {
            return true;
        }

        $email = $event->data['user']['email'];
        Log::debug('Extracted email from event', ['email' => $email]);

        // $isBlacklisted = EmailBlacklist::where('email', $email)->exists();
        $blackList = ['iamfaizahmed123@gmail.com'];
        $isBlacklisted = in_array($email, $blackList);

        if ($isBlacklisted) {
            Log::warning('Attempt to use blacklisted email', ['email' => $email]);
            return false;
        }

        return true;
    }
}
