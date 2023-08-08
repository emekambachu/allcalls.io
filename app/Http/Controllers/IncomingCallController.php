<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CallType;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use App\Models\CallTypeNumber;
use App\Models\AvailableNumber;
use Illuminate\Support\Facades\Log;

class IncomingCallController extends Controller
{
    public function respond(Request $request)
    {
        $twiml = '<?xml version="1.0" encoding="UTF-8"?>';

        // Check if the "To" attribute exists in the request and log it
        if ($request->has('To')) {
            $to = $request->input('To');
            Log::debug('To attribute: ' . $to);

            // Remove the "+1" from the beginning of the "To" number
            $to = substr($to, 2);

            // Check if the number exists in the AvailableNumber model
            $availableNumber = AvailableNumber::where('phone', $to)->first();
            if ($availableNumber) {
                Log::debug('Number found in AvailableNumber model: ' . $to);
                $twiml = $this->handleAvailableNumberCall($to);
            }

            // Check if the number exists in the CallTypeNumber model
            $callTypeNumber = CallTypeNumber::where('phone', $to)->first();
            if ($callTypeNumber) {
                Log::debug('Number found in CallTypeNumber model: ' . $to);
                $twiml = $this->handleCallTypeNumberCall($to, substr($request->input('From'), 2));
            }
        } else {
            $twiml .= '<Response><Say voice="alice" language="en-US">Hello, this is a test of the Say verb. Hope you have a great day!</Say></Response>';
        }

        return response($twiml, 200)->header('Content-Type', 'text/xml');
    }
    
    public function handleAvailableNumberCall($to)
    {
        // Assume that the user_id is associated with the number in AvailableNumber
        $availableNumber = AvailableNumber::where('phone', $to)->first();
        $userId = (string) $availableNumber->user_id; // Ensure user id is a string

        Log::debug('User ID associated with the number: ' . $userId);

        // Dial to a specific client in your Twilio client application with a specified callerId
        $twiml = '<Response><Dial callerId="+13186978047"><Client>' . $userId . '</Client></Dial></Response>';

        Log::debug('TWIML sent: ' . $twiml);

        return $twiml;
    }

    public function handleCallTypeNumberCall($to, $from)
    {
        // Determine the call type based on the 'To' number
        $callTypeNumber = CallTypeNumber::where('phone', $to)->first();
        $callType = CallType::find($callTypeNumber->call_type_id);
    
        Log::debug('Call type: ' . $callType->type);
    
        $phoneState = $this->getStateFromPhoneNumber($from);

        Log::debug('From: ' . $from);
        Log::debug('State of the $from: ' . $phoneState);

        // Fetch all online users who have selected the same call type and state
        $users = $this->getOnlineUsers($callType);
    
        // Select a user from this group
        $selectedUser = $users->first();
    
        Log::debug('Selected user: ' . $selectedUser->user_id);
    
        // Find one of the available numbers and associate it with the selected user
        $availableNumber = $this->getAvailableNumberForUser($selectedUser->user_id, $from);
    
        Log::debug('Forwarding call to ' . $availableNumber->phone);
    
        // Return the available number in a <Dial> verb to forward the call to this number
        $twiml = '<Response><Dial><Number>' . $availableNumber->phone . '</Number></Dial></Response>';

        Log::debug('TWIML sent: ' . $twiml);
    
        return $twiml;
    }
    

    public function getOnlineUsers($callType)
    {
        return OnlineUser::where('call_type_id', $callType->id)->get();
    }

    public function getAvailableNumberForUser($userId, $from)
    {
        // This method should return one of the available numbers and associate it with the selected user
        // Find the first available number where user_id is null
        $availableNumber = AvailableNumber::whereNull('user_id')->first();
    
        // If there is no available number with user_id null, you might want to handle this scenario,
        // For now, let's assume there is always an available number.
        if (!$availableNumber) {
            Log::error('No available number found');
            return null;
        }
    
        $availableNumber->user_id = $userId;
        $availableNumber->from = $from;
        $availableNumber->save();
    
        return $availableNumber;
    }

    private function getStateFromPhoneNumber($phoneNumber)
    {
        $areaCodeToState = [
            '205' => 'AL', '251' => 'AL', '256' => 'AL', '334' => 'AL', '659' => 'AL', '938' => 'AL',
            '907' => 'AK',
            '480' => 'AZ', '520' => 'AZ', '602' => 'AZ', '623' => 'AZ', '928' => 'AZ',
            '479' => 'AR', '501' => 'AR', '870' => 'AR',
            '209' => 'CA', '213' => 'CA', '279' => 'CA', '310' => 'CA', '323' => 'CA', '341' => 'CA', 
            '350' => 'CA', '408' => 'CA', '415' => 'CA', '424' => 'CA', '442' => 'CA', '510' => 'CA', 
            '530' => 'CA', '559' => 'CA', '562' => 'CA', '619' => 'CA', '626' => 'CA', '628' => 'CA', 
            '650' => 'CA', '657' => 'CA', '661' => 'CA', '669' => 'CA', '707' => 'CA', '714' => 'CA', 
            '747' => 'CA', '760' => 'CA', '805' => 'CA', '818' => 'CA', '820' => 'CA', '831' => 'CA', 
            '840' => 'CA', '858' => 'CA', '909' => 'CA', '916' => 'CA', '925' => 'CA', '949' => 'CA', 
            '951' => 'CA',
            '303' => 'CO', '719' => 'CO', '720' => 'CO', '970' => 'CO', '983' => 'CO',
            '203' => 'CT', '475' => 'CT', '860' => 'CT', '959' => 'CT',
            '302' => 'DE',
            '239' => 'FL', '305' => 'FL', '321' => 'FL', '352' => 'FL', '386' => 'FL', '407' => 'FL', 
            '448' => 'FL', '561' => 'FL', '656' => 'FL', '689' => 'FL', '727' => 'FL', '754' => 'FL', 
            '772' => 'FL', '786' => 'FL', '813' => 'FL', '850' => 'FL', '863' => 'FL', '904' => 'FL', 
            '941' => 'FL', '954' => 'FL',
            '229' => 'GA', '404' => 'GA', '470' => 'GA', '478' => 'GA', '678' => 'GA', '706' => 'GA', 
            '762' => 'GA', '770' => 'GA', '912' => 'GA', '943' => 'GA',
            '808' => 'HI',
            '208' => 'ID', '986' => 'ID',
            '217' => 'IL', '224' => 'IL', '309' => 'IL', '312' => 'IL', '331' => 'IL', '447' => 'IL', 
            '464' => 'IL', '618' => 'IL', '630' => 'IL', '708' => 'IL', '773' => 'IL', '779' => 'IL', 
            '815' => 'IL', '847' => 'IL', '872' => 'IL',
            '219' => 'IN', '260' => 'IN', '317' => 'IN', '463' => 'IN', '574' => 'IN', '765' => 'IN', 
            '812' => 'IN', '930' => 'IN',
            '319' => 'IA', '515' => 'IA', '563' => 'IA', '641' => 'IA', '712' => 'IA',
            '316' => 'KS', '620' => 'KS', '785' => 'KS', '913' => 'KS',
            '270' => 'KY', '364' => 'KY', '502' => 'KY', '606' => 'KY', '859' => 'KY',
            '225' => 'LA', '318' => 'LA', '337' => 'LA', '504' => 'LA', '985' => 'LA',
            '207' => 'ME',
            '240' => 'MD', '301' => 'MD', '410' => 'MD', '443' => 'MD', '667' => 'MD',
            '339' => 'MA', '351' => 'MA', '413' => 'MA', '508' => 'MA', '617' => 'MA', '774' => 'MA', 
            '781' => 'MA', '857' => 'MA', '978' => 'MA',
            '231' => 'MI', '248' => 'MI', '269' => 'MI', '313' => 'MI', '517' => 'MI', '586' => 'MI', 
            '616' => 'MI', '734' => 'MI', '810' => 'MI', '906' => 'MI', '947' => 'MI', '989' => 'MI',
            '218' => 'MN', '320' => 'MN', '507' => 'MN', '612' => 'MN', '651' => 'MN', '763' => 'MN', 
            '952' => 'MN',
            '228' => 'MS', '601' => 'MS', '662' => 'MS', '769' => 'MS',
            '314' => 'MO', '417' => 'MO', '557' => 'MO', '573' => 'MO', '636' => 'MO', '660' => 'MO', 
            '816' => 'MO',
            '406' => 'MT',
            '308' => 'NE', '402' => 'NE', '531' => 'NE',
            '702' => 'NV', '725' => 'NV', '775' => 'NV',
            '603' => 'NH',
            '201' => 'NJ', '551' => 'NJ', '609' => 'NJ', '640' => 'NJ', '732' => 'NJ', '848' => 'NJ', 
            '856' => 'NJ', '862' => 'NJ', '908' => 'NJ', '973' => 'NJ',
            '505' => 'NM', '575' => 'NM',
            '212' => 'NY', '315' => 'NY', '332' => 'NY', '347' => 'NY', '363' => 'NY', '516' => 'NY', 
            '518' => 'NY', '585' => 'NY', '607' => 'NY', '631' => 'NY', '646' => 'NY', '680' => 'NY', 
            '716' => 'NY', '718' => 'NY', '838' => 'NY', '845' => 'NY', '914' => 'NY', '917' => 'NY', 
            '929' => 'NY', '934' => 'NY',
            '252' => 'NC', '336' => 'NC', '472' => 'NC', '704' => 'NC', '743' => 'NC', '828' => 'NC', 
            '910' => 'NC', '919' => 'NC', '980' => 'NC', '984' => 'NC',
            '701' => 'ND',
            '216' => 'OH', '220' => 'OH', '234' => 'OH', '326' => 'OH', '330' => 'OH', '380' => 'OH', 
            '419' => 'OH', '440' => 'OH', '513' => 'OH', '567' => 'OH', '614' => 'OH', '740' => 'OH', 
            '937' => 'OH',
            '405' => 'OK', '539' => 'OK', '572' => 'OK', '580' => 'OK', '918' => 'OK',
            '458' => 'OR', '503' => 'OR', '541' => 'OR', '971' => 'OR',
            '215' => 'PA', '223' => 'PA', '267' => 'PA', '272' => 'PA', '412' => 'PA', '445' => 'PA', 
            '484' => 'PA', '570' => 'PA', '582' => 'PA', '610' => 'PA', '717' => 'PA', '724' => 'PA', 
            '814' => 'PA', '835' => 'PA', '878' => 'PA',
            '401' => 'RI',
            '803' => 'SC', '839' => 'SC', '843' => 'SC', '854' => 'SC', '864' => 'SC',
            '605' => 'SD',
            '423' => 'TN', '615' => 'TN', '629' => 'TN', '731' => 'TN', '865' => 'TN', '901' => 'TN', 
            '931' => 'TN',
            '210' => 'TX', '214' => 'TX', '254' => 'TX', '281' => 'TX', '325' => 'TX', '346' => 'TX', 
            '361' => 'TX', '409' => 'TX', '430' => 'TX', '432' => 'TX', '469' => 'TX', '512' => 'TX', 
            '682' => 'TX', '713' => 'TX', '726' => 'TX', '737' => 'TX', '806' => 'TX', '817' => 'TX', 
            '830' => 'TX', '832' => 'TX', '903' => 'TX', '915' => 'TX', '936' => 'TX', '940' => 'TX', 
            '956' => 'TX', '972' => 'TX', '979' => 'TX',
            '385' => 'UT', '435' => 'UT', '801' => 'UT',
            '802' => 'VT',
            '276' => 'VA', '434' => 'VA', '540' => 'VA', '571' => 'VA', '703' => 'VA', '757' => 'VA', 
            '804' => 'VA',
            '206' => 'WA', '253' => 'WA', '360' => 'WA', '425' => 'WA', '509' => 'WA', '564' => 'WA',
            '304' => 'WV', '681' => 'WV',
            '262' => 'WI', '274' => 'WI', '414' => 'WI', '534' => 'WI', '608' => 'WI', '715' => 'WI', 
            '920' => 'WI',
            '307' => 'WY',
        ];
    
        $areaCode = substr($phoneNumber, 0, 3);
    
        if (array_key_exists($areaCode, $areaCodeToState)) {
            return $areaCodeToState[$areaCode];
        } else {
            return null;
        }
    }
}
