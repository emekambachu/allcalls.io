<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $role = null;
        $user_level = null;
        if(auth()->user() && auth()->user()->roles->contains('name', 'admin')) {
            $role = 'admin';  
        }

        // if auth user and auth user is NOT internal agent or admin
        if(auth()->user() && !auth()->user()->roles->contains('name', 'internal-agent') && !auth()->user()->roles->contains('name', 'admin')) {
            $role = 'user';
        }

        if(auth()->user() && auth()->user()->roles->contains('name', 'internal-agent')) {
            $role = 'internal-agent';
            $user_level = $request->user()->getAgentLevel;
        }

        $notifications = null;

        if (auth()->user()) {
            $notifications = auth()->user()->notifications->map(function ($notification) {
                $notification->created_at_diff = Carbon::parse($notification->created_at)->diffForHumans();
                return $notification;
            });
        }


        if (auth()->user() && auth()->user()->clients()->latest()->first() && auth()->user()->clients()->latest()->first()->status === null) {
            $callDuration = auth()->user()->clients()->latest()->first()->call->call_duration_in_seconds;

            if (! $callDuration) {
                $showDispositionUpdateOption = false;
            } else {
                $showDispositionUpdateOption = true;
            }
        } else {
            $showDispositionUpdateOption = false;
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'role' => $role,
                'user_level' => $user_level,
                'notifications' => $notifications,
                'showDispositionUpdateOption' =>  $showDispositionUpdateOption,
                'last_client' =>  (auth()->user()->clients()->latest()->first() && auth()->user()->clients()->latest()->first()->client) ? auth()->user()->clients()->latest()->first()->client : null,
            ],

            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
                'bonus' => fn () => $request->session()->get('bonus'),
                'agentInvitationLink' => fn () => $request->session()->get('agentInvitationLink'),
            ],
        ]);
    }
}
