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

    public function updatePublisherInfo()
    {
        return $this->fetchRingbaCallLogs();
    }


    public function fetchRingbaCallLogs()
    {
        $from = '+1' . $this->from;
        $callTypeName = optional(CallType::find($this->call_type_id))->type;

        Log::debug('fetchRingbaCallLogs:', [
            'from' => $from,
            'callTypeName' => $callTypeName,
        ]);
    
        if ($callTypeName !== 'Final Expense') {
            Log::debug('Call type is not Final Expense. Skipping Ringba call logs.');
    
            return null;
        }
    
        // Convert $createdAt to DateTime object
        $callCreatedAt = new DateTime($this->created_at, new DateTimeZone('UTC'));
    
        // Subtract 30 seconds to get the start date
        $startDate = clone $callCreatedAt; // Clone to avoid modifying original $callCreatedAt
        $startDate = $startDate->sub(new DateInterval('PT30S'))->format(DateTimeInterface::ISO8601);
    
        // Add 30 seconds to $createdAt to get the end date
        $endDate = clone $callCreatedAt; // Clone to ensure we're adding to the original $callCreatedAt
        $endDate = $endDate->add(new DateInterval('PT30S'))->format(DateTimeInterface::ISO8601);
    
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
                            'value' => 'Allcalls FE',
                            'comparisonType' => 'EQUALS'
                        ],
                        [
                            'column' => 'targetNumber',
                            'value' => '+15736523170',
                            'comparisonType' => 'EQUALS'
                        ]
                    ]
                ],
                [
                    'anyConditionToMatch' => [
                        [
                            'column' => 'inboundPhoneNumber',
                            'value' => $from,
                            'comparisonType' => 'EQUALS'
                        ]
                    ]
                ]
            ]
        ]);
    
        if ($response->successful()) {
            return $response->json();
        } else {
            // Consider logging the error or handling it as needed
            return null;
        }
    }
}
