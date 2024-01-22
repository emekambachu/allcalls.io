<?php

namespace App\Listeners;

use App\Models\User;
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

        $this->addUnsubscribeTokenIfNotExists($email);

        $isBlacklisted = EmailBlacklist::where('email', $email)->exists();

        if ($isBlacklisted) {
            Log::warning('Attempt to use blacklisted email', ['email' => $email]);
            return false;
        }

        return true;
    }

    protected function addUnsubscribeTokenIfNotExists($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return;
        }

        Log::debug('AddUnsubscribeToken: Checking if user exists', [
            'email' => $email, 'exists' => $user,
            'Unsubscribe token does not exist' => !$user->unsubscribeToken,
        ]);

        if ($user && !$user->unsubscribeToken) {
            Log::debug('AddUnsubscribeToken: Generating unsubscribe token for user', ['email' => $email]);
            $user->generateUnsubscribeToken();
        }
    }
}
