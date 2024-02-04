<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use DateTimeZone;
use App\Models\Call;
use DateTimeInterface;
use App\Models\CallType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PublisherInfoController extends Controller
{
    public function show(Call $call)
    {
        $ringbaCallLogs = $this->fetchRingbaCallLogs($call->from, $call->call_type_id);

        return [
            'ringbaCallLogs' => $ringbaCallLogs,
        ];
    }

    protected function fetchRingbaCallLogs($from, $callTypeId)
    {
        $from = '+1' . $from;
        $callTypeName = optional(CallType::find($callTypeId))->type;

        Log::debug('fetchRingbaCallLogs:', [
            'from' => $from,
            'callTypeName' => $callTypeName,
        ]);

        if ($callTypeName !== 'Final Expense') {
            Log::debug('Call type is not Final Expense. Skipping Ringba call logs.');

            return null;
        }

        return ['logs' => 'here'];

        $utcTimeZone = new DateTimeZone('UTC');
        $now = new DateTime('now', $utcTimeZone);

        // Subtract 30 seconds to get the start date
        $startDate = clone $now; // Clone to avoid modifying original $now
        $startDate = $startDate->sub(new DateInterval('PT30S'))->format(DateTimeInterface::ISO8601);

        // Add 30 seconds to the original "now" to get the end date
        $endDate = clone $now; // Clone to ensure we're adding to the original "now"
        $endDate = $endDate->add(new DateInterval('PT30S'))->format(DateTimeInterface::ISO8601);


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
                            'value' => '+19168771246',
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
