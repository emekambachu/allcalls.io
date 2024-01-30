<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LiveTrainingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->agent_access_status === 'Training' && auth()->user()->balance < 40) {
            return redirect()->route('training.lower.balance');  
        }

        return $next($request);
    }
}
