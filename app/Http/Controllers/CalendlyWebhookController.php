<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendlyWebhookController extends Controller
{
    public function show(Request $request)
    {
        Log::debug('CalendlyWebhook:', [
            'request' => $request->all(),
        ]);
    }
}
