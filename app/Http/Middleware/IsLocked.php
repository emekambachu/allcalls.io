<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsLocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->is_locked) {
            return redirect()->route('internal.agent.locked');
        }
        return $next($request);
        // if (auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->agent_access_status == NOT_LIVE) {
        //     return redirect()->route('training.index');
        // }

        // if (auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->agent_access_status == TRAINING) {
        //     if (in_array($request->route()->getName(), IN_TRAINING_STATUS_ROUTES)) {
        //         return $next($request);
        //     } else {
        //         return redirect()->route('take-calls.show');
        //     }
        // }
        // return $next($request);
    }
}
