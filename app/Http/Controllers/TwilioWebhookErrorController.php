<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioWebhookErrorController extends Controller
{
    public function store()
    {
        // Log the error
        Log::error('Twilio webhook error: ' . request()->getContent());

        return response('Error logged', 200);
    }
}
