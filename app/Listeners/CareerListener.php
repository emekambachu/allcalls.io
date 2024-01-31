<?php

namespace App\Listeners;

use App\Events\CareerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class CareerListener
{
    /**
     * Create the event listener.
     */
    // public $applicantData;
    public function __construct()
    {
        // $this->applicantData = $applicantData;
    }

    /**
     * Handle the event.
     */
    public function handle(CareerEvent $event): void
    {
        Mail::to(recruitingEmail())->send(new \App\Mail\CareerMail($event->applicantData));
    
    }
}
