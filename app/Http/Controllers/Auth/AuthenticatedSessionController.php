<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Activity;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        Activity::create([
            'user_id' => Auth::user()->id,
            'session_id' => session()->getId(),
        ]);


        UserActivity::create([
            'action' => 'Signed in.',
            'data' => json_encode([]),
            'platform' => 'web',
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
        ]);

        if ( $request->device_type ) {
            $deviceCount = $request->user()->devices()->where('device_type', $request->device_type)->count();
            if ($deviceCount === 0) {
                $request->user()->devices()->create([
                    'device_type' => $request->device_type,
                ]);
            }
        }

        if(auth()->user()->roles->contains('name', 'admin')) {
            return redirect()->intended(RouteServiceProvider::AdminHome);
        }
        
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
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

        UserActivity::create([
            'action' => 'Signed out.',
            'data' => json_encode([]),
            'platform' => 'web',
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect('/');
    }
}
