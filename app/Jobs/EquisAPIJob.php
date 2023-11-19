<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\EquisDuplicateMail;
use Carbon\Carbon;
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
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
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
                "address" => $this->user->internalAgentContract->address ?? null,
                "addressTwo" =>  $this->user->internalAgentContract->address ?? null,
                "birthDate" =>  isset($this->user->internalAgentContract->dob) ? Carbon::parse($this->user->internalAgentContract->dob)->format('Y-m-d') : '-',
                "city" =>  $this->user->internalAgentContract->city ?? null,
                "currentlyLicensed" => false,
                "email" =>  $this->user->internalAgentContract->email ?? null,
                "firstName" =>  $this->user->internalAgentContract->first_name ?? null,
                "languageId" => "en",
                "lastName" =>  $this->user->internalAgentContract->last_name ?? null,
                "npn" => "F4CSXL3",
                "partnerUniqueId" => "AC".$this->user->id,
                // "preferredFirstName" => "Emily",
                // "preferredLastName" => "Anderson",
                // "preferredSuffix" => "III",
                "role" => "Agent",
                "details" => "Nothing details found.",
                "state" => isset($this->user->internalAgentContract->state) ? getStateName($this->user->internalAgentContract->state) : null,
                // "suffix" => "II",
                "uplineAgentEFNumber" => "EF222171",
                "zipCode" =>  $this->user->internalAgentContract->address ?? null,
            ]);
            // Log the response body and status
            Log::debug($response->body());
            Log::debug($response->status());

            if (!$response->successful()) {
                $responseBody = (string) $response->body();

                Log::debug('equis-api-job:Failed to create an agent in Equis API');
                Log::debug('equis-api-job:Response body: ', [
                    'responseBody' => $responseBody,
                ]);

                if (str_contains($responseBody, 'System.DuplicateAgentException')) {
                    Log::debug('equis-api-job:Duplicate agent found in Equis API while creating an agent');

                    Log::debug('equis-api-job:Sending email to people');
                    Mail::to(['bizdev@equisfinancial.com'])
                    ->cc(['contracting@allcalls.io'])
                    ->send(new EquisDuplicateMail($this->user->internalAgentContract->first_name." ".$this->user->internalAgentContract->last_name, 'EF222171', $this->user->internalAgentContract->email));
                    
                    Log::debug('equis-api-job:tagging user as equis_duplicate');
                    $this->user->equis_duplicate = true;
                    $this->user->save();



                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->withToken($accessToken)->post($url2, [
                        "userName" => "EF222171",
                        "partnerUniqueId" =>"AC".$this->user->id,
                    ]);
    
                    // Log the response body and status
                    Log::debug('equis-api-job:map agent response:', [
                        'responseBody' => $response->body(),
                        'responseStatus' => $response->status(),
                    ]);

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
                }

                return;
            }
        } else {
            // Handle error in token retrieval
            // log error:
            Log::debug('equis-api-job:Failed to retrieve access token on the request to create an agent');
        }
    }
}
