<?php

namespace App\Listeners;

use App\Mail\OnboardingWelcome;
use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail
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
    public function handle(Registered $event): void
    {

        if($event->user->hasRole('internal-agent')) {
            Mail::to($event->user)->send(new OnboardingWelcome($event->user));
        }
        else {
            Mail::to($event->user)->send(new Welcome($event->user));
        }
    }
}
