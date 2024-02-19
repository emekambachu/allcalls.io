<?php

namespace App\Listeners;

use App\Events\AppSubmittedEvent;
use App\Models\Call;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MarkCallAsSale implements ShouldQueue
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
        $matchedRecord = Call::whereFrom(trim($event->business->client_phone_no))
            ->where('created_at', '<=', $dateTillEndOfDay)
            ->orderBy('created_at', 'desc')->first();
        if ($matchedRecord) {
            if (isset($matchedRecord->client)) {
                $matchedRecord->client->status = 'Sale - Guaranteed Issue';
                $matchedRecord->client->save();
            }
            $matchedRecord->save();
            Log::debug("Mark Call As Sale For Business -->$event->business AND Call Record ---> $matchedRecord");
        } else {
            Log::debug("Date --> $dateTillEndOfDay Call Record Not found For Business -->$event->business");
        }

    }
}
