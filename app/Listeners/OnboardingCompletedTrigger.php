<?php

namespace App\Listeners;

use App\Events\OnboardingCompleted;
use Illuminate\Support\Facades\Mail;

class OnboardingCompletedTrigger
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(OnboardingCompleted $event): void
    {
        Mail::to(SYSTEM_EMAILS)->send(new \App\Mail\OnboardingCompleted($event->user));
    }
}
