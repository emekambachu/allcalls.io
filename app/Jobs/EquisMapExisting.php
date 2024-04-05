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

class EquisMapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userName;
    protected $partnerUniqueId;

    /**
     * Create a new job instance.
     *
     * @param string $userName The username of the user to be mapped.
     * @param string $partnerUniqueId The unique ID for the partner to be mapped with the user.
     */
    public function __construct(string $userName, string $partnerUniqueId)
    {
        $this->userName = $userName;
        $this->partnerUniqueId = $partnerUniqueId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $accessToken = $this->getAccessToken();

            if (!$accessToken) {
                Log::error('Failed to retrieve access token for EquisMapJob.');
                return;
            }


            $users = User::whereNotNull('equis_number')->get(['id', 'equis_number']);

            foreach($users as $user) 
            {
                $this->mapUser($user, $accessToken);
            }
        } catch (Exception $e) {
            Log::debug('EquisMapJob encountered an exception.', [
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
            Log::debug('EquisMapJob response:', [
                'requestBody' => [
                    'userName' => $this->userName,
                    'partnerUniqueId' => $this->partnerUniqueId,
                ],
                'responseBody' => $response->body(),
                'responseStatus' => $response->status(),
            ]);

            if (!$response->successful()) {
                // Handle unsuccessful map attempt, maybe retry or send notification
                Log::debug('EquisMapJob failed to map agent.', [
                    'responseBody' => $response->body(),
                    'responseStatus' => $response->status(),
                ]);
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
