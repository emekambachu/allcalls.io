<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\BroadcastManager;

class CustomBroadcastingAuthController extends Controller
{
    public function store(Request $request)
    {
        // Get the Sanctum token from the query string
        $token = $request->query('sanctum_token');

        Log::debug('Gamma: Token:');
        Log::debug($token);

        // Use the token to authenticate the user
        $user = $this->getUserBySanctumToken($token);

        // Set the user as the authenticated user for this request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        Log::debug('Gamma: user is set:');
        Log::debug($request->user());

        if ($request->hasSession()) {
            $request->session()->reflash();
        }

        return Broadcast::auth($request);
    }

    private function getUserBySanctumToken($token)
    {
        // Retrieve user by the token. You might need to modify this based on your database setup.
        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken) {
            abort(401, 'Invalid token.');
        }

        return $accessToken->tokenable;
    }

}
