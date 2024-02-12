<?php

namespace App\Models;

use DateTime;
use DateInterval;
use DateTimeZone;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use DateTimeInterface;
use App\Models\CallType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Call extends Model
{
    use HasFactory;
    protected $table = "calls";
    protected $guarded = ['id'];

    protected $appends = ['ringing_duration', 'role'];

    public function callType()
    {
        return $this->belongsTo(CallType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getClient()
    {
        return $this->hasOne(Client::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Get the ringing duration for the call.
     *
     * @return int
     */
    public function getRingingDurationAttribute()
    {
        // If user_response_time is null, return 20
        if (is_null($this->user_response_time)) {
            return 20;
        }

        // Calculate the difference in seconds between created_at and user_response_time
        return $this->created_at->diffInSeconds($this->user_response_time);
    }

    public function getCallTakenAttribute($value)
    {
        // Parse the timestamp
        $timestamp = Carbon::parse($value);

        // Check if there's an authenticated user
        if (auth()->user()) {
            // Get the user's timezone
            $timezone = auth()->user()->timezone;

            // Apply the timezone to the timestamp
            $timestamp->timezone($timezone);
        }


        return $timestamp->diffForHumans() . ' (' . $timestamp->format('H:i d/m/Y') . ')';
    }

    /**
     * Get the role type for the call's user.
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        if ($this->user && $this->user->roles->contains('name', 'internal-agent')) {
            return 'Internal Agent';
        }

        return 'Regular User';
    }


    /**
     * Update the publisher info for the call.
     * 
     * @return bool
     */
    public function updatePublisherInfo($callerId = null)
    {
        $callLogs = $this->fetchRingbaCallLogs($callerId ?? null);


        if (isset($callLogs['report'], $callLogs['report']['records']) && !empty($callLogs['report']['records'])) {
            $callLog = $callLogs['report']['records'][0];

            $this->publisher_name = $callLog['publisherName'];
            $this->publisher_id = $callLog['publisherId'];
            $this->cost = $callLog['totalCost'];

            return $this->save();
        }

        // Now let's try the same with fetchRetrieverCallLogs
        $callLogs = $this->fetchRetrieverCallLogs();

        Log::debug('updatePublisherInfo:retreaver:', [
            'callLogs' => $callLogs,
        ]);

        if (sizeof($callLogs) > 0) {
            Log::debug('updatePublisherInfo:retreaver:found', [
                'affiliateId' => $callLogs[0]['call']['afid'],
                'charge_total' => $callLogs[0]['call']['charge_total'],
            ]);

            $response = self::getRetreaverAffiliateById($callLogs[0]['call']['afid']);

            Log::debug('getRetreaverAffiliateById', [
                'response' => $response->body(),
            ]);
        }

        return false;
    }

    public static function getRetreaverAffiliateById($affiliateId)
    {
        $retreaverAPIKey = env('RETREAVER_API_KEY');
        $retreaverCompanyId = env('RETREAVER_COMPANY_ID');

        return Http::get("https://api.retreaver.com/affiliates/afid/{$affiliateId}.xml?api_key={$retreaverAPIKey}&company_id={$retreaverCompanyId}");
    }

    public function fetchRingbaCallLogs($callerId = null)
    {
        $from = '+1' . $this->from;
        $callTypeName = optional(CallType::find($this->call_type_id))->type;

        Log::debug('fetchRingbaCallLogs:foobar:', [
            'from' => $from,
            'callTypeName' => $callTypeName,
        ]);

        // if ($callTypeName !== 'Final Expense') {
        //     Log::debug('Call type is not Final Expense. Skipping Ringba call logs.');

        //     return null;
        // }

        // Convert $createdAt to DateTime object
        $callCreatedAt = new DateTime($this->created_at, new DateTimeZone('UTC'));

        // Subtract 2 minutes to get the start date
        $startDate = clone $callCreatedAt; // Clone to avoid modifying original $callCreatedAt
        $startDate = $startDate->sub(new DateInterval('PT2M'))->format(DateTimeInterface::ISO8601);

        // Add 2 minutes to $createdAt to get the end date
        $endDate = clone $callCreatedAt; // Clone to ensure we're adding to the original $callCreatedAt
        $endDate = $endDate->add(new DateInterval('PT2M'))->format(DateTimeInterface::ISO8601);

        if (!in_array($this->from, ['8045172235'])) {
            $targetName = 'Allcalls FE';
            $targetNumber = '+15736523170';
        } else {
            $targetName = 'testtestest';
            $targetNumber = '+16787232049';
        }

        Log::debug('fetchRingbaCallLogs:targetInfo', [
            'targetName' => $targetName,
            'targetNumber' => $targetNumber,
        ]);

        // Your HTTP request and response handling code goes here...
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . env('RINGBA_API_KEY'),
        ])->post('https://api.ringba.com/v2/' . env('RINGBA_ACCOUNT_ID') . '/calllogs', [
            'reportStart' => $startDate,
            'reportEnd' => $endDate,
            'filters' => [
                [
                    'anyConditionToMatch' => [
                        [
                            'column' => 'targetName',
                            'value' => $targetName,
                            'comparisonType' => 'EQUALS'
                        ],
                        [
                            'column' => 'targetNumber',
                            'value' => $targetNumber,
                            'comparisonType' => 'EQUALS'
                        ]
                    ],
                ],
                [
                    'anyConditionToMatch' => [
                        [
                            'column' => 'inboundPhoneNumber',
                            'value' => $callerId ?? $from,
                            'comparisonType' => 'EQUALS'
                        ]
                    ]
                ]
            ]
        ]);

        Log::debug('fetchRingbaCallLogs:response:', [
            'response' => $response->json(),
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            // Consider logging the error or handling it as needed
            return null;
        }
    }



    public function fetchRetrieverCallLogs()
    {
        // Format the created_at date to use in the API request
        // $callCreatedAt = new DateTime($this->created_at, new DateTimeZone('UTC'));
        $callCreatedAt = new DateTime($this->created_at);
        $startDate = $callCreatedAt->sub(new DateInterval('PT30S'))->format(DateTimeInterface::ISO8601); // 30 seconds before
        $endDate = $callCreatedAt->add(new DateInterval('PT1M'))->format(DateTimeInterface::ISO8601); // 30 seconds after (total 1 minute from start)

        // Construct the URL for the API request
        $url = 'https://api.retreaver.com/calls.json';
        $queryParams = http_build_query([
            'api_key' => env('RETREAVER_API_KEY'), // Assuming you have the API key stored in your .env file
            'company_id' => env('RETREAVER_COMPANY_ID'), // Assuming you have the company ID stored in your .env file
            'created_at_start' => $startDate,
            'created_at_end' => $endDate,
        ]);

        // Make the HTTP GET request to the Retreaver API
        $response = Http::get($url . '?' . $queryParams);

        // Check if the request was successful
        if ($response->successful()) {
            Log::debug('RetreaverCallResponse:', [
                'response' => $response->body(),
                'call_id' => $this->id,
            ]);

            // Return the response data as an array
            return $response->json();
        } else {
            // Log the error or handle it as needed
            Log::error('Failed to fetch Retreaver call logs', [
                'response' => $response->body(),
                'call_id' => $this->id,
            ]);
            return null;
        }
    }
}
