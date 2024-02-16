<?php

namespace App\Listeners;

use App\Events\AppSubmittedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use App\Models\Call;
use Illuminate\Support\Facades\Log;

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
        $matchedRecord = Call::where('from', trim($event->business->client_phone_no))
            ->where('created_at', '<=', $dateTillEndOfDay)
            ->orderBy('created_at', 'desc')->first();
        if ($matchedRecord) {
            $matchedRecord->disposition = 'Sale - Guaranteed Issue';
            $matchedRecord->save();
            Log::debug("Date --> $dateTillEndOfDay Mark Call As Sale For Business -->$event->business AND Call Record ---> $matchedRecord");
        }else {
            Log::debug("Call Record Not found For Business -->$event->business");
        }

    }
}
