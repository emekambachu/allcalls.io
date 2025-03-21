<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class Ringba
{
    private $apiUrl = 'https://api.ringba.com/v2/';
    private $apiToken;
    private $accountId;

    public function __construct()
    {
        $this->apiToken = config('services.ringba.token');
        $this->accountId = config('services.ringba.accountId');
    }

    public function createTarget($name, $phoneNumber, $states)
    {
        $url = $this->apiUrl . $this->accountId . '/targets';

        $headers = [
            'Authorization' => 'Token ' . $this->apiToken,
            'Content-Type' => 'application/json',
        ];

        $payload = [
            'name' => $name,
            'instructions' => [
                'callType' => 'number',
                'number' => $phoneNumber,
                'connectionTimeOut' => 0,
            ],
            'criteria' => [
                [
                    'tagCriteria' => [],
                ],
            ],
            'schedule' => [
                'timeZoneId' => 'Pacific Standard Time',
            ],
            'targetCallIncrement' => 'onConvert',
            'isCriteriaInverted' => false,
            'blockRecordings' => false,
            'rpcSetting' => null,
            'shareableTagSetting' => null,
            'priorityBump' => '0',
        ];

        foreach ($states as $state) {
            $payload['criteria'][0]['tagCriteria'][] = [
                'tagId' => '',
                'tagIds' => ['InboundNumber:State'],
                'comparisonType' => 'EQUALS',
                'comparisonTypeTemp' => [
                    'key' => 'EQUALS',
                    'value' => 'EQUALS_SINGLE_VALUE',
                    'isNegativeMatch' => false,
                    'forGeoTags' => false,
                    'forBulkTags' => true,
                    'forMultipleValues' => false,
                ],
                'value' => $state,
                'valueList' => [$state],
                'isNegativeMatch' => false,
            ];
        }

        $response = Http::withHeaders($headers)->post($url, $payload);

        if ($response->failed()) {
            throw new Exception('Error making API request: ' . $response->body());
        }

        return $response->json();
    }

    public function getAllTargets()
    {
        $url = $this->apiUrl . $this->accountId . '/targets';
    
        $headers = [
            'Authorization' => 'Token ' . $this->apiToken,
        ];
    
        $response = Http::withHeaders($headers)->get($url);
    
        if ($response->failed()) {
            throw new Exception('Error making API request: ' . $response->body());
        }
    
        // This will return an array of targets
        return $response->json()['targets'];
    }

    public function deleteTarget($targetId)
    {
        $url = $this->apiUrl . $this->accountId . '/targets/' . $targetId;
    
        $headers = [
            'Authorization' => 'Token ' . $this->apiToken,
        ];
    
        $response = Http::withHeaders($headers)->delete($url);
    
        if ($response->failed()) {
            throw new Exception('Error making API request: ' . $response->body());
        }
    
        // This will return the response as an array, where 'isSuccessful' indicates if the deletion was successful
        return $response->json();
    }

    public function getCallPlans()
    {
        $url = $this->apiUrl . $this->accountId . '/callplan';
    
        $headers = [
            'Authorization' => 'Token ' . $this->apiToken,
        ];
    
        $response = Http::withHeaders($headers)->get($url);
    
        if ($response->failed()) {
            throw new Exception('Error making API request: ' . $response->body());
        }
    
        // This will return the call plan as an array
        return $response->json();
    }

    public function createCallPlan($planName)
    {
        $url = $this->apiUrl . $this->accountId . '/callplan';
    
        $headers = [
            'Authorization' => 'Token ' . $this->apiToken,
            'Content-Type' => 'application/json',
        ];
    
        $payload = [
            'name' => $planName,
        ];
    
        $response = Http::withHeaders($headers)->post($url, $payload);
    
        if ($response->failed()) {
            throw new Exception('Error making API request: ' . $response->body());
        }
    
        // This will return the newly created call plan as an array
        return $response->json();
    }

    public function addRouteToCallPlan($callPlanId, $targetId, $phoneNumber, $states)
    {
        $url = $this->apiUrl . $this->accountId . '/callroutes';
    
        $headers = [
            'Authorization' => 'Token ' . $this->apiToken,
            'Content-Type' => 'application/json',
        ];
    
        $payload = [
            'callConversion' => [
                'deDupeSetting' => ['secondsFromLastCall' => 0],
                'conversionArgs' => ['startTimerEvent' => 'OnConnectedCall'],
                'conversionType' => 'connectedCall',
                'conversionValue' => 10,
            ],
            'priority' => ['priority' => 1, 'weight' => 1],
            'targetid' => $targetId,
            'callTarget' => [
                'instructions' => [
                    'connectionTimeOut' => 0,
                    'callType' => 'number',
                    'number' => $phoneNumber,
                ],
                'isHighRateTarget' => false,
                'highRateCost' => 0,
                'ringbaNumberId' => '',
                'targetCallIncrement' => 'onConvert',
                '_SearchNumer' => 'number:'.$phoneNumber,
                'capsMigrated' => true,
                'priorityBump' => 0,
                'conversionTimerOffset' => 0,
                'schedule' => [
                    'concurrencyCap' => -1,
                    'timeZoneId' => 'Pacific Standard Time',
                    'allTimeSumCap' => -1,
                    'monthlySumCap' => -1,
                    'dailySumCap' => -1,
                    'hourlySumCap' => -1,
                    'allTimeCap' => -1,
                    'monthlyCap' => -1,
                    'dailyCap' => -1,
                    'hourlyCap' => -1,
                ],
                'criteria' => [],
                'isCriteriaInverted' => false,
                'blockRecordings' => false,
            ],
        ];
    
        foreach ($states as $state) {
            $payload['callTarget']['criteria'][] = [
                'tagCriteria' => [
                    'tagId' => '',
                    'tagIds' => ['InboundNumber:State'],
                    'comparisonType' => 'EQUALS',
                    'value' => $state,
                    'isNegativeMatch' => false,
                    'isNumber' => false,
                ],
            ];
        }
    
        $response = Http::withHeaders($headers)->post($url, $payload);
    
        if ($response->failed()) {
            throw new Exception('Error making API request: ' . $response->body());
        }
    
        // This will return the newly added route as an array
        return $response->json();
    }

    public function getCallPlanRoutes($callPlanId)
    {
        $callPlansData = $this->getCallPlans();
        $callPlans = $callPlansData['callPlans'] ?? [];
    
        $callPlan = collect($callPlans)->firstWhere('id', $callPlanId);
    
        return $callPlan['routes'] ?? [];
    }

    public function deleteRouteFromCallPlan($callPlanId, $routeId)
    {
        $url = $this->apiUrl . $this->accountId . '/callplan/' . $callPlanId . '/callRoute/' . $routeId;
    
        $headers = [
            'Authorization' => 'Token ' . $this->apiToken,
        ];
    
        $response = Http::withHeaders($headers)->delete($url);
    
        if ($response->failed()) {
            throw new Exception('Error making API request: ' . $response->body());
        }
    
        // This will return the response as an array
        return $response->json();
    }
}
