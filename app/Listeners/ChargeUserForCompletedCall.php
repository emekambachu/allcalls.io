<?php

namespace App\Listeners;

use App\Events\CompletedCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChargeUserForCompletedCall
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
    public function handle(CompletedCallEvent $event): void
    {
        Log::debug('ChargeUserForCompletedCall');
        Log::debug('User:');
        Log::debug($event->user);
    }
}
