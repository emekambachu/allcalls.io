<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SendBirdUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SendBirdUserController extends Controller
{
    public function createSendBirdUser(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        Log::debug('Creating SendBird user for user ID: ' . $user->id);

        Log::debug('And we stopped here or right at trying to check if the user has the required roles!');

        // Check if the user has the required roles
        if (!$user->roles->contains('name', 'internal-agent') && !$user->roles->contains('name', 'admin')) {
            Log::warning('User does not have required roles. User ID: ' . $user->id);
            return response()->json(['message' => 'User is not an internal agent or admin'], 403);
        }

        Log::debug('Were still going!');

        // Check if a SendBird user already exists for the user
        $existingSendBirdUser = $user->sendBirdUser()->first();
        if ($existingSendBirdUser) {
            Log::info('SendBird user already exists for user ID: ' . $user->id);
            return response()->json(['message' => 'SendBird user already exists'], 409);
        }

        Log::debug('We just passed checking if a SendBird user already exists for the user!');

        // Validate incoming request fields
        $validatedData = $request->validate([
            'nickname' => 'required|string|max:255',
            'profile_image' => 'required|image|max:5120', // 5 MB limit
        ]);

        Log::debug('Validated request successfully!');

        // Handle Image Upload
        $path = $request->file('profile_image')->storeAs(
            'profile_pictures', $user->id . '.' . $request->file('profile_image')->extension(),
            'public'
        );
        $profileUrl = Storage::url($path);
        $fullUrl = url($profileUrl);
        
        // Update user's profile picture in the database
        $user->profile_picture = $profileUrl;
        $user->save();
        Log::debug('Profile image uploaded. Path: ' . $path);

        $applicationId = env('SENDBIRD_APPLICATION_ID');
        $apiKey = env('SENDBIRD_API_TOKEN');

        $response = Http::withHeaders([
            'Api-Token' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post("https://api-{$applicationId}.sendbird.com/v3/users", [
            'user_id' => $user->id,
            'nickname' => $request->nickname,
            'profile_url' => $fullUrl,
            'issue_access_token' => true, // Include this field
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
        
            $sendBirdUser = new SendBirdUser([
                'nickname' => $responseData['nickname'],
                'user_id' => $user->id,
                'profile_url' => $responseData['profile_url'],
                'access_token' => $responseData['access_token'],
                'is_online' => $responseData['is_online'],
                'is_active' => $responseData['is_active'],
                'is_created' => $responseData['is_created'],
                'phone_number' => $responseData['phone_number'],
                'require_auth_for_profile_image' => $responseData['require_auth_for_profile_image'],
                'session_tokens' => json_encode($responseData['session_tokens']),
                'last_seen_at' => $responseData['last_seen_at'],
                'discovery_keys' => json_encode($responseData['discovery_keys']),
                // 'preferred_languages' => json_encode($responseData['preferred_languages']),
                'has_ever_logged_in' => $responseData['has_ever_logged_in'],
            ]);
        
            $sendBirdUser->save();
            Log::info('SendBird user created successfully for user ID: ' . $user->id);

            // Join the user to a SendBird group channel
            $channelUrl = env('SENDBIRD_INTERNAL_AGENTS_GROUP_URL'); // Replace with your actual channel URL
            $joinChannelResponse = $this->joinSendBirdGroupChannel((string) $user->id, $channelUrl);

            if ($joinChannelResponse->successful()) {
                Log::info("User ID: {$user->id} joined SendBird group channel successfully.");
            } else {
                Log::error("Failed to join SendBird group channel. User ID: {$user->id} Response: " . $joinChannelResponse->body());
            }

            return response()->json([
                'message' => 'SendBird user created and joined to channel successfully',
                'sendBirdUser' => $sendBirdUser
            ], 201);
        } else {
            // Handle failure response from SendBird API
            Log::error('Failed to create SendBird user. User ID: ' . $user->id . ' Response: ' . $response->body());
            return response()->json([
                'message' => 'Failed to create SendBird user',
                'error' => $response->body()
            ], 500);
        }        
    }

    public function checkSendBirdUser(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Check if the user has the required roles
        if (!$user->roles->pluck('name')->contains('internal-agent') && !$user->roles->pluck('name')->contains('admin')) {
            return response()->json(['message' => 'User is not an internal agent or admin'], 403);
        }

        // Retrieve the SendBird user record if it exists
        $sendBirdUser = $user->sendBirdUser()->first();

        if ($sendBirdUser) {
            // If a SendBird user exists, return success response with SendBird user data
            return response()->json([
                'message' => 'SendBird user exists',
                'sendBirdUser' => $sendBirdUser
            ], 200);
        } else {
            // If a SendBird user does not exist, return a not found response
            return response()->json(['message' => 'SendBird user does not exist'], 404);
        }
    }

    protected function joinSendBirdGroupChannel($userId, $channelUrl)
    {
        $applicationId = env('SENDBIRD_APPLICATION_ID');
        $apiKey = env('SENDBIRD_API_TOKEN');

        $response = Http::withHeaders([
            'Api-Token' => $apiKey,
            'Content-Type' => 'application/json',
        ])->put("https://api-{$applicationId}.sendbird.com/v3/group_channels/{$channelUrl}/join", [
            'user_id' => $userId,
        ]);

        return $response;
    }
}
