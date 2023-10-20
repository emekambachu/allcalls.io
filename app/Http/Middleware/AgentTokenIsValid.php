<?php

namespace App\Http\Middleware;

use App\Models\AgentInvite;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (isset($_GET['agentToken']) && $_GET['agentToken'] != '') {
            $token = AgentInvite::where('token', '=', $_GET['agentToken'])->first();
            if ($token) {
                if (!$token->tokenExpired($token->token) && $token->isAvailable($token->token)) {
                    session()->put('agent-token', $token->token);
                    return $next($request);
                } else {
                    $alreadyAttempFirstStep = User::where('email', $token->email)->first();
                    if ($alreadyAttempFirstStep) {
                        return $next($request);
                    }
                    return redirect()->route('home')->with('error', 'Your agent registration token expired.');
                }
            } else {
                return redirect()->route('home')->with('error', 'Invalid agent invite token.');
            }
        } else {
            return redirect()->route('home')->with('error', 'Invalid agent invite token.');
        }
    }
}
