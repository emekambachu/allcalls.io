<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

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
        if(auth()->user() && auth()->user()->roles->contains('name', 'admin')) {
            $role = 'admin';
        }

        if(auth()->user() && auth()->user()->roles->contains('name', 'user')) {
            $role = 'user';
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
                'role' => $role,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'bonus' => fn () => $request->session()->get('bonus'),
            ],
        ]);
    }
}
