<?php

namespace App\Jobs;

use Exception;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EquisMapExisting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                Log::error('Failed to retrieve access token for EquisMapExisting.');
                return;
            }

            $users = User::whereNotNull('equis_number')->get(['id', 'equis_number']);

            foreach ($users as $user) {
                $this->mapUser($user, $accessToken);
            }
        } catch (Exception $e) {
            Log::debug('EquisMapExisting encountered an exception.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    protected function mapUser($user, $accessToken)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->post(env('EQUIS_BASE_URL') . '/Agent/Map', [
            'userName' => $user->equis_number,
            'partnerUniqueId' => 'AC' . $user->id,
        ]);

        // Log the response
        Log::debug('EquisMapExistingJob response:', [
            'requestBody' => [
                'userName' => $user->equis_number,
                'partnerUniqueId' => 'AC' . $user->id,
            ],
            'responseBody' => $response->body(),
            'responseStatus' => $response->status(),
        ]);

        if (!$response->successful()) {
            // Handle unsuccessful map attempt, maybe retry or send notification
            Log::debug('EquisMapExisting failed to map agent.', [
                'responseBody' => $response->body(),
                'responseStatus' => $response->status(),
            ]);

            return;
        }
    }

    /**
     * Retrieve the access token needed for API calls.
     *
     * @return string|null The access token, or null if retrieval fails.
     */
    protected function getAccessToken(): ?string
    {
        $clientId = env('EQUIS_CLIENT_ID');
        $clientSecret = env('EQUIS_CLIENT_SECRET');
        $tokenResponse = Http::asForm()->post('https://equisfinancialb2c.b2clogin.com/equisfinancialb2c.onmicrosoft.com/B2C_1_SignIn/oauth2/v2.0/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => env('EQUIS_SCOPE'),
        ]);

        if ($tokenResponse->successful()) {
            return $tokenResponse->json()['access_token'];
        }

        Log::error('Failed to retrieve access token.', [
            'responseBody' => $tokenResponse->body(),
            'responseStatus' => $tokenResponse->status(),
        ]);

        return null;
    }
}
