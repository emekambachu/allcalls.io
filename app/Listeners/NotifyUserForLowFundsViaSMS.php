<?php

namespace App\Listeners;

use App\Mail\FundsTooLow;
use App\Mail\FundTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyUserForLowFundsViaSMS
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
    public function handle(\App\Events\FundsTooLow $event): void
    {
        Log::debug('The SMS listener for insufficient balance was fired');
        return;
    }
}
