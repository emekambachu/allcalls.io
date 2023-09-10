<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class notBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->banned){
            $user = Auth::user();

        $sessionId = session()->getId();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $activity = $user->activities->where('session_id', $sessionId)->first();

        if ($activity) {
            $activity->update([
                'logout_time' => now()
            ]);
        }

        return redirect()->route('login')->with('error','your have be blocked');
        }
        return $next($request);
    }
}
