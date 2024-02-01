<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
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
       
        if (auth()->user()->roles->contains('name', 'internal-agent') && !auth()->user()->legacy_key) {
            return redirect()->route('contract.steps');
        }

        // if (auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->is_locked) {
        //     return redirect()->route('internal.agent.locked');
        // }

        if (DB::table('users_call_type_state')->where('user_id', auth()->user()->id)->count() == 0) {
        return redirect()->route('registration.steps');
        }

        return $next($request);

        // return redirect()->route('registration.steps');


        //        if (DB::table('users_call_type_state')->where('user_id', auth()->user()->id)->count()) {
        //             if(auth()->user()->roles->contains('name', 'internal-agent') && !auth()->user()->legacy_key) {
        //                 return redirect()->route('contract.steps');
        //             }
        //        }
        //        return redirect()->route('registration.steps');
    }
}
