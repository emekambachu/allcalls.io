<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegistrationStepCheckerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (DB::table('users_call_type_state')->where('user_id', auth()->user()->id)->count()) {
            if(auth()->user()->roles->contains('name', 'internal-agent')) {
                if(!auth()->user()->internalAgentContract) {
                    return redirect()->route('contract.steps');
                }
                return $next($request);
            }
            return $next($request);
        }
        return redirect()->route('registration.steps');
    }
}
