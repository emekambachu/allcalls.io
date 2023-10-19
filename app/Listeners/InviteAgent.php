<?php

namespace App\Listeners;

use App\Events\InviteAgent as EventInviteAgent;
use Exception;
use App\Mail\InviteAgent as MailInviteAgent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InviteAgent
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
    public function handle(EventInviteAgent $event): void
    {
        try {
            Mail::to($event->email)->send(new MailInviteAgent($event->url));
            
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Invite Agent Error: ' . $e->getMessage());
        }
    }
}
