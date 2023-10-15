<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Broadcasting\BroadcastManager;

class CustomBroadcastingAuthController extends Controller
{
    public function store(Request $request)
    {
        // Get the Sanctum token from the query string
        $token = $request->query('sanctum_token');

        // Use the token to authenticate the user
        $user = $this->getUserBySanctumToken($token);

        // Set the user as the authenticated user for this request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Now, we can proceed with the broadcasting authentication using Laravel's default functionality
        $broadcaster = app(BroadcastManager::class)->connection();
        $response = $broadcaster->auth($request);

        return $response;
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
