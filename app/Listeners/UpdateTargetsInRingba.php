<?php

namespace App\Listeners;

use Exception;
use App\Models\State;
use App\Models\CallType;
use App\Services\Ringba;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use App\Events\UserCallTypeStateUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTargetsInRingba
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
    public function handle(UserCallTypeStateUpdated $event): void
    {
        Log::debug('Update targets now...');
        Log::debug($event->userId);
        Log::debug($event->toDelete);
        Log::debug($event->toInsert);
    }
}
