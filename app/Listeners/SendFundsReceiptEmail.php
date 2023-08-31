<?php

namespace App\Listeners;

use App\Events\FundsAdded;
use App\Mail\FundTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendFundsReceiptEmail
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
    public function handle(FundsAdded $event): void
    {
        Mail::to($event->user)->send(new FundTransaction($event->user, $event->subTotal, $event->processingFee, $event->total, $event->bonus));

    }
}
