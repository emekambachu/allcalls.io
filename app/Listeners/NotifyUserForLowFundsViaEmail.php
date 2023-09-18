<?php

namespace App\Listeners;

use App\Mail\FundsTooLow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyUserForLowFundsViaEmail
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
    public function handle(\App\Events\FundsTooLow $event): void
    {
        Mail::to($event->user)->send(new FundsTooLow($event->user));
    }
}
