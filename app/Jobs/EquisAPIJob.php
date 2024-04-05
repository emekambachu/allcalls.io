<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\State;
use App\Mail\EquisApiError;
use Illuminate\Bus\Queueable;
use App\Models\EquisDuplicate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EquisAPIJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user;
    public $partnerUniqueId;
    public $managerPartnerUniqueId;

    public function __construct($user)
    {
        Log::debug('equis-api-job:constructing equis api job', [
            'user' => $user,
        ]);
        $this->user = $user;
        $this->partnerUniqueId = "AC" . $this->user->id;

        if ($this->user->invited_by) {
            $this->managerPartnerUniqueId = "AC" . $this->user->invited_by;
        } else {
            throw new Exception('This user does not have an invited by person.');
            // $this->managerPartnerUniqueId = "AC73";
        }

        // $this->managerPartnerUniqueId = "AC636";
        // $this->managerPartnerUniqueId = "AC71";
        $this->managerPartnerUniqueId = isset($this->user->invitedBy) && isset($this->user->invitedBy->equis_number) ? $this->user->invitedBy->equis_number : null;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // First, retrieve the Bearer token
        $clientId = env('EQUIS_CLIENT_ID'); // Your client ID her
        $clientSecret = env('EQUIS_CLIENT_SECRET'); // Your client secret here
        // First, retrieve the Bearer token
        $tokenResponse = Http::asForm()->post('https://equisfinancialb2c.b2clogin.com/equisfinancialb2c.onmicrosoft.com/B2C_1_SignIn/oauth2/v2.0/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => env('EQUIS_SCOPE'),
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


        // $this->mapManager($accessToken);

        $requestData = $this->getRequestData();
        // Log the request data
        Log::debug('equis-api-job:request data to create an agent:', [
            'requestData' => $requestData,
        ]);

        // Now, make the POST request to the API endpoint with the Bearer token to create an agent
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->post(env('EQUIS_BASE_URL') . '/Agent', $requestData);

        Log::debug('equis-api-job:response equis api to create an agent:', [
            'responseBody' => $response->body(),
            'responseStatus' => $response->status(),
        ]);

        if ($response->status() !== 200) {
            Mail::to(EQUIS_JOB_ERROR_EMAILS)->send(new EquisApiError($response->body()));
            Log::debug('Equis API error email triggered.');
            return;
        }

        if (!$response->successful()) {
            $responseBody = (string)$response->body();
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
        // $this->saveManagerIdForUser($accessToken);
    }

    protected function sendEmailsToPeople()
    {
        Log::debug('equis-api-job:EquisDuplicate');
        // Mail::to(['bizdev@equisfinancial.com'])
        //     ->cc(['contracting@allcalls.io'])
        //     ->send(new EquisDuplicateMail($this->user->internalAgentContract->first_name . " " . $this->user->internalAgentContract->last_name, 'EF222171', $this->user->internalAgentContract->email));

        // Mail::to(['iamfaizahmed123@gmail.com', 'ryan@allcalls.io', 'vince@allcalls.io'])
        // ->send(new EquisDuplicateMail($this->user->internalAgentContract->first_name . " " . $this->user->internalAgentContract->last_name, 'EF222171', $this->user->internalAgentContract->email));
        EquisDuplicate::create([
            'email' => $this->user->email,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'upline_code' => $this->user->upline_id,
        ]);
    }

    protected function tagUserAsEquisDuplicate()
    {
        Log::debug('equis-api-job:tagging user as equis_duplicate');
        $this->user->equis_duplicate = true;
        $this->user->save();
    }

    protected function mapAgentToEquis($accessToken)
    {
        if (! $this->user->equis_number) {
            $equisNumber = $this->findExistingEFNumber();
        } else {
            $equisNumber = $this->user->equis_number;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->post(env('EQUIS_BASE_URL') . '/Agent/Map', [
            "userName" => $equisNumber,
            "partnerUniqueId" => "AC" . $this->user->id,
        ]);

        // Log the response body and status
        Log::debug('equis-api-job:map agent response:', [
            'responseBody' => $response->body(),
            'responseStatus' => $response->status(),
        ]);

        return;
    }

    // protected function getRequestData()
    // {
    //     return [
    //         "address" => fake()->address(),
    //         "birthDate" => fake()->date($format = 'Y-m-d', $max = 'now'),
    //         "city" => fake()->city(),
    //         "currentlyLicensed" => true,
    //         "email" => fake()->safeEmail(),
    //         "firstName" => fake()->firstName(),
    //         "languageId" => "en",
    //         "lastName" => fake()->lastName(),
    //         "npn" => fake()->numerify('############'),
    //         "partnerUniqueId" => fake()->regexify('[A-Z0-9]{5}'),
    //         "role" => "Agent",
    //         "details" => "A New Agent Registered.",
    //         "state" => fake()->stateAbbr(),
    //         "managerPartnerUniqueId" => 'AC73',
    //         "zipCode" => fake()->postcode(),
    //     ];
    // }


    protected function getRequestData()
    {
        // This is the sample REQUIRED data that we need to send to Equis API
        return [
            "address" => $this->user->internalAgentContract->address ?? null,
            "birthDate" => isset($this->user->internalAgentContract->dob) ? Carbon::parse($this->user->internalAgentContract->dob)->format('Y-m-d') : null,
            "city" => $this->user->internalAgentContract->city ?? null,
            "currentlyLicensed" => true,
            "email" => $this->user->internalAgentContract->email ?? null,
            "firstName" => $this->user->internalAgentContract->first_name ?? null,
            "languageId" => "en",
            "lastName" => $this->user->internalAgentContract->last_name ?? null,
            "npn" => $this->user->internalAgentContract->resident_insu_license_no ?? null,
            "partnerUniqueId" => $this->partnerUniqueId,
            "role" => "Agent",
            "details" => "A New Agent Registered.",
            "state" => isset($this->user->internalAgentContract->state) ? $this->getStateAbbrev($this->user->internalAgentContract->state) : null,
            "managerPartnerUniqueId" => $this->managerPartnerUniqueId,
            "zipCode" => $this->user->internalAgentContract->zip ?? null,
        ];
    }

    protected function getStateAbbrev($stateId)
    {
        return State::find($stateId)->name;
    }

    protected function saveManagerIdForUser($accessToken)
    {
        $url = env('EQUIS_BASE_URL') . "/Agent/{$this->managerPartnerUniqueId}/UserName";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->get($url);

        if ($response->successful()) {
            $this->user->upline_id = $this->partnerUniqueId;
            $this->user->manager_id = $this->managerPartnerUniqueId;
            $this->user->save();

            Log::debug('EF Number saved for user', ['Invitee' => $this->user->id, 'Manager ID' => $this->managerPartnerUniqueId]);
        } else {
            // Handle the error scenario
            Log::debug('Failed to save EF Number for user', ['Invitee' => $this->user->id, 'Server error response' => $response->body()]);
        }
    }

    protected function mapManager($accessToken)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withToken($accessToken)->post(env('EQUIS_BASE_URL') . '/Agent/Map', [
            "userName" => isset($this->user->upline_id) ? $this->user->upline_id : "",
            "partnerUniqueId" => $this->managerPartnerUniqueId,
        ]);

        // Log the response body and status
        Log::debug('equis-api-job:map manager response:', [
            'responseBody' => $response->body(),
            'responseStatus' => $response->status(),
        ]);

        return;
    }

    /**
     * Finds and returns the userName for a given user based on their email or phone.
     *
     * @param User $user The user object containing email and phone.
     * @return string|null The userName if found, or null if not.
     */
    protected function findExistingEFNumber()
    {
        $filePath = __DIR__ . '/AgentsAndEFNumbers.csv';

        // Open the file
        if (($fileHandle = fopen($filePath, 'r')) !== false) {
            // Skip the header row
            fgetcsv($fileHandle);

            // Loop through each row of the CSV
            while (($row = fgetcsv($fileHandle)) !== false) {
                // Check if all expected columns are present
                if (count($row) >= 5) {
                    list($userName, $firstName, $lastName, $email, $phoneNumber) = $row;

                    // Check if the current row's email or phone matches the user's
                    if ($email === $this->user->email || $phoneNumber === $this->user->phone) {
                        fclose($fileHandle);
                        return $userName; // Return the userName if a match is found
                    }
                }
            }

            // Close the file after reading
            fclose($fileHandle);
        }

        return null; // Return null if no match is found
    }
}
