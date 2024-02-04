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
        $ringbaCallLogs = $this->fetchRingbaCallLogs($call->from, $call->call_type_id, $call->created_at);

        return [
            'ringbaCallLogs' => $ringbaCallLogs,
        ];
    }

    protected function fetchRingbaCallLogs($from, $callTypeId, $createdAt)
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
    
        // Convert $createdAt to DateTime object
        $callCreatedAt = new DateTime($createdAt, new DateTimeZone('UTC'));
    
        // Subtract 30 seconds to get the start date
        $startDate = clone $callCreatedAt; // Clone to avoid modifying original $callCreatedAt
        $startDate = $startDate->sub(new DateInterval('PT30S'))->format(DateTimeInterface::ISO8601);
    
        // Add 30 seconds to $createdAt to get the end date
        $endDate = clone $callCreatedAt; // Clone to ensure we're adding to the original $callCreatedAt
        $endDate = $endDate->add(new DateInterval('PT30S'))->format(DateTimeInterface::ISO8601);
    
        // Your HTTP request and response handling code goes here...
    
        if ($response->successful()) {
            return $response->json();
        } else {
            // Consider logging the error or handling it as needed
            return null;
        }
    }    
}
