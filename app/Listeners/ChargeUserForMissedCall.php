<?php

namespace App\Listeners;

use App\Events\MissedCallEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChargeUserForMissedCall
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
    public function handle(MissedCallEvent $event): void
    {
        Log::debug('ChargeUserForMissedCall');
        Log::debug('User:');
        Log::debug($event->user);
    }
}
