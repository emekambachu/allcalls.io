<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Security\RequestValidator;
use Symfony\Component\HttpFoundation\Response;

class TwilioRequestValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = env("TWILIO_AUTH_TOKEN");
        $signature = $request->header("HTTP_X_TWILIO_SIGNATURE");
        $url = $request->url();
        $postVars = $request->all();

        $validator = new RequestValidator($token);

        if ($validator->validate($signature, $url, $postVars)) {
            Log::debug('The request to url ' . $url . ' came from twilio indeed.');
        } else {
            Log::debug('The request to url ' . $url . ' did NOT come from twilio.');
        }

        return $next($request);
    }
}
