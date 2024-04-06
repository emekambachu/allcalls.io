<?php

namespace App\Listeners;

use App\Events\OnboardingCompleted;
use App\Jobs\EquisAPIJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateEquisUser
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
        EquisAPIJob::dispatch($event->user);
    }
}
