<?php

namespace App\Listeners;

use App\Events\AppSubmittedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use App\Models\Call;

class MarkCallAsSale
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
    public function handle(AppSubmittedEvent $event): void
    {
        $dateTillEndOfDay = Carbon::parse($event->business->application_date)->endOfDay();
        $matchedRecord = Call::whereFrom($event->business->client_phone_no)
            ->where('created_at', '<=', $dateTillEndOfDay)
            ->orderBy('created_at', 'desc')->first();
        if ($matchedRecord) {
            $matchedRecord->disposition = 'Sale - Guaranteed Issue';
            $matchedRecord->save();
        }
    }
}
