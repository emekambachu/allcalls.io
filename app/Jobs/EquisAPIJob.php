<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\State;
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
    public $user;
    public function __construct($user)
    {
        Log::debug('equis-api-job:constructing equis api job', [
            'user' => $user,
        ]);

        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // First, retrieve the Bearer token
        $clientId = env('EQUIS_CLIENT_ID'); // Your client ID here
        $clientSecret = env('EQUIS_CLIENT_SECRET'); // Your client secret here

        // First, retrieve the Bearer token
        $tokenResponse = Http::asForm()->post('https://equisfinancialb2c.b2clogin.com/equisfinancialb2c.onmicrosoft.com/B2C_1_SignIn/oauth2/v2.0/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://equisfinancialb2c.onmicrosoft.com/equis-partner-api-uat/.default'
        ]);

        Log::debug('equis-api-job:token response:', [
            'responseBody' => $tokenResponse->body(),
            'responseStatus' => $tokenResponse->status(),
        ]);


        if (!$tokenResponse->successful()) {
            Log::debug('equis-api-job:Failed to retrieve access token on the request to create an agent');
            return;
        }

        $accessToken = $tokenResponse->json()['access_token'];

        $requestData = $this->getRequestData();

        // Log the request data
        Log::debug('equis-api-job:request data to create an agent:', [
            'requestData' => $requestData,
        ]);

        // Now, make the POST request to the API endpoint with the Bearer token to create an agent
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->post('https://equisapipartner-uat.azurewebsites.net/Agent', $requestData);

        Log::debug('equis-api-job:response equis api to create an agent:', [
            'responseBody' => $response->body(),
            'responseStatus' => $response->status(),
        ]);


        if (!$response->successful()) {
            $responseBody = (string) $response->body();

            // In case of a failure to create an agent, check if the agent already exists in Equis API
            // If the agent already exists, send an email to the people and tag the user as equis_duplicate
            // Also, map the agent to Equis API
            if (str_contains($responseBody, 'System.DuplicateAgentException')) {
                Log::debug('equis-api-job:Duplicate agent found in Equis API while creating an agent');
                $this->sendEmailsToPeople();
                $this->tagUserAsEquisDuplicate();
                $this->mapAgentToEquis($accessToken);
            }

            return;
        }
    }

    protected function sendEmailsToPeople()
    {
        Log::debug('equis-api-job:Sending email to people');
        // Mail::to(['bizdev@equisfinancial.com'])
        //     ->cc(['contracting@allcalls.io'])
        //     ->send(new EquisDuplicateMail($this->user->internalAgentContract->first_name . " " . $this->user->internalAgentContract->last_name, 'EF222171', $this->user->internalAgentContract->email));

        Mail::to(['iamfaizahmed123@gmail.com', 'ryan@allcalls.io', 'vince@allcalls.io'])
            ->send(new EquisDuplicateMail($this->user->internalAgentContract->first_name . " " . $this->user->internalAgentContract->last_name, 'EF222171', $this->user->internalAgentContract->email));
    }

    protected function tagUserAsEquisDuplicate()
    {
        Log::debug('equis-api-job:tagging user as equis_duplicate');
        $this->user->equis_duplicate = true;
        $this->user->save();
    }

    protected function mapAgentToEquis($accessToken)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->post('https://equisapipartner-uat.azurewebsites.net/Agent/Map', [
            "userName" => "EF222171",
            "partnerUniqueId" => "AC" . $this->user->id,
        ]);

        // Log the response body and status
        Log::debug('equis-api-job:map agent response:', [
            'responseBody' => $response->body(),
            'responseStatus' => $response->status(),
        ]);

        return;
    }

    protected function getRequestData()
    {
        // This is the sample REQUIRED data that we need to send to Equis API
        // return [
        //     "address" => "787 Pine Rd",
        //     "birthDate" => "1990-04-22",
        //     "city" => "Raleigh",
        //     "currentlyLicensed" => false,
        //     "email" => "emily.smitch@webmail.com",
        //     "firstName" => "Emily",
        //     "languageId" => "es",
        //     "lastName" => "Smitch",
        //     "npn" => "9JL456C",
        //     "partnerUniqueId" => "b3n4k8",
        //     "role" => "Agent",
        //     "state" => "NY",
        //     "uplineAgentEFNumber" => "EF222171",
        //     "zipCode" => "10001"
        // ];


        return [
            "address" => $this->user->internalAgentContract->address ?? null,
            "birthDate" =>  isset($this->user->internalAgentContract->dob) ? Carbon::parse($this->user->internalAgentContract->dob)->format('Y-m-d') : '-',
            "city" =>  $this->user->internalAgentContract->city ?? null,
            "currentlyLicensed" => false,
            "email" =>  $this->user->internalAgentContract->email ?? null,
            "firstName" =>  $this->user->internalAgentContract->first_name ?? null,
            "languageId" => "en",
            "lastName" =>  $this->user->internalAgentContract->last_name ?? null,
            "npn" => $this->user->internalAgentContract->resident_insu_license_no ?? null,
            "partnerUniqueId" => "AC" . $this->user->id,
            "role" => "Agent",
            "state" => isset($this->user->internalAgentContract->state) ? $this->getStateAbbrev($this->user->internalAgentContract->state) : null,
            "uplineAgentEFNumber" => isset($this->user->upline_id)?$this->user->upline_id:"",
            "zipCode" =>  $this->user->internalAgentContract->zip ?? null,
        ];
    }

    protected function getStateAbbrev($stateId)
    {
        return State::find($stateId)->name;
    }
}
