<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\EquisDuplicateMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Notifications\Messages\MailMessage;

class EquisAPIJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $clientId = env('EQUIS_CLIENT_ID'); // Your client ID here
        $clientSecret = env('EQUIS_CLIENT_SECRET'); // Your client secret here
        $url = 'https://equisapipartner-uat.azurewebsites.net/Agent'; // The specific endpoint you want to access
        $url2 = 'https://equisapipartner-uat.azurewebsites.net/Agent/Map'; // The specific endpoint you want to access

        // First, retrieve the Bearer token
        $tokenResponse = Http::asForm()->post('https://equisfinancialb2c.b2clogin.com/equisfinancialb2c.onmicrosoft.com/B2C_1_SignIn/oauth2/v2.0/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://equisfinancialb2c.onmicrosoft.com/equis-partner-api-uat/.default'
        ]);


        // Log the response body and status
        Log::debug($tokenResponse->body());
        Log::debug($tokenResponse->status());

        if ($tokenResponse->successful()) {
            $accessToken = $tokenResponse->json()['access_token'];

            // Now, make the POST request to the API endpoint with the Bearer token
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withToken($accessToken)->post($url, [
                "address" => "83708 Robert Forge",
                "addressTwo" => "Suite 886",
                "birthDate" => "1966-06-05",
                "city" => "Landrymouth",
                "currentlyLicensed" => false,
                "email" => "christopher49@yahoo.com",
                "firstName" => "Anthony",
                "languageId" => "en",
                "lastName" => "Bishop",
                "npn" => "F4CSXL3",
                "partnerUniqueId" => "6cd946ae-fe3e-42f5-9a43-a00afd261c6b",
                "preferredFirstName" => "Emily",
                "preferredLastName" => "Anderson",
                "preferredSuffix" => "III",
                "role" => "Agent",
                "state" => "AL",
                "suffix" => "II",
                "uplineAgentEFNumber" => "EF222171",
                "zipCode" => "65523"
            ]);

            // Log the response body and status
            Log::debug($response->body());
            Log::debug($response->status());

            if ($response->successful()) {
                // Handle successful response
                $data = $response->json();
                // return response()->json($data);

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->withToken($accessToken)->post($url2, [
                    "userName" => "EF222171",
                    "partnerUniqueId" => "abc12378",
                ]);

                // Log the response body and status
                Log::debug($response->body());
                Log::debug($response->status());


                if ($response->successful()) {
                    // Handle successful response
                    $data = $response->json();
                    Log::debug('equis-api-job:Data retrieved from Equis API', [
                        'data' => $data
                    ]);
                } else {
                    // Handle error
                    Log::debug('equis-api-job:Failed to retrieve data from Equis API');
                }
            } else {
                $responseBody = (string) $response->body();

                if (str_contains($responseBody, 'System.DuplicateAgentException')) {
                    // send a mail to iamfaizahmed123@gmail.com App\Mail\EquisDuplicateMail
                    Mail::to(['iamfaizahmed123@gmail.com'])->send(new EquisDuplicateMail('Example Agent Name', 'EF123123123', 'agentemail@example.com'));

                    Log::debug('equis-api-job:Agent already exists in Equis API');
                }

                // log it:
                Log::debug('equis-api-job:Failed to retrieve data from Equis API on the request to map an agent');
                // Handle error
            }
        } else {
            // Handle error in token retrieval
            // log error:
            Log::debug('equis-api-job:Failed to retrieve access token on the request to create an agent');
        }
    }
}
