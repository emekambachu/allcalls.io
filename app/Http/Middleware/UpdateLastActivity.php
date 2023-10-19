<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {     
        $devices_details = "";
        if((new \Jenssegers\Agent\Agent())->isDesktop()){
            $devices_details = "Desktop";
        }
        if((new \Jenssegers\Agent\Agent())->isMobile()){
            $devices_details = "Mobile";
        }
        if((new \Jenssegers\Agent\Agent())->isTablet()){
            $devices_details = "Tablet";
        }
        if (Auth::check()) {
            $sessionId = session()->getId();

            $user = Auth::user();
            $activity = $user->activities->where('session_id', $sessionId)->first();
            if ($activity) {
                $activity->last_activity_at = now();
                $activity->ip_address = $request->ip();
                $activity->user_agent = $request->header('user-agent');
                $activity->devices_details = $devices_details;
                $activity->save();
            }
        }

        return $next($request);
    }
}
