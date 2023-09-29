<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Bid;
use App\Models\User;
use App\Models\State;
use App\Models\CallType;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use App\Models\CallTypeNumber;
use App\Models\AvailableNumber;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Twilio\Security\RequestValidator;

class IncomingCallController extends Controller
{
    public function respond(Request $request)
    {
        $request->validate([
            'To' => 'required',
        ]);

        // // Check for X-Twilio-Signature header
        // $twilioSignature = $request->header('X-Twilio-Signature');
        // if ($twilioSignature) {
        //     Log::debug('X-Twilio-Signature header exists: ' . $twilioSignature);
        //     $validator = new RequestValidator(env('TWILIO_AUTH_TOKEN'));
        //     $url = url()->full() . '?' . http_build_query($request->all());
        //     $variables = $request->all();

        //     Log::debug('URL: ' . $url);
        //     Log::debug($variables);

        //     // Check if the incoming signature is valid for your application URL and the incoming parameters
        //     if ($validator->validate($twilioSignature, $url, $variables)) {
        //         Log::debug('Confirmed to have come from Twilio.');
        //     } else {
        //         Log::debug('NOT VALID. It might have been spoofed!');
        //     }
        // } else {
        //     Log::debug('X-Twilio-Signature header not found.');
        // }


        $twiml = '<?xml version="1.0" encoding="UTF-8"?>';

        $to = $request->input('To');
        Log::debug('To attribute: ' . $to);

        // Remove the "+1" from the beginning of the "To" number
        $to = substr($to, 2);

/*      hardcode call from dialler */
        $isFromClient = strpos($request->input('From'), 'client:') === 0;

        Log::debug('Professional: This is a professional log right before checking dialler call');
        Log::debug('Professional:' . $request->input('From'));
        Log::debug('asdasdasdasdadas');
        if ( $isFromClient ) {
            Log::debug('Omega: call coming from client:');
            $uniqueCallId = uniqid();
            $twiml = '<?xml version="1.0" encoding="UTF-8"?>'; 
            $twiml .= '<Response><Dial answerOnBridge="true"><Client callerId="+15736523170">alice';
            $twiml .= '<Parameter name="unique_call_id" value="' . $uniqueCallId . '"/>';
            $twiml .= '</Client></Dial></Response>';
            Log::debug($twiml);
            // right, so unique_call_id won't be getting passed in because it gets attached in the routing process and currently when you call from the dialler, it's coming directly to you
            return response($twiml, 200)->header('Content-Type', 'text/xml');
        }


        // Check if the number exists in the AvailableNumber model
        $availableNumber = AvailableNumber::where('phone', $to)->first();

        if ($availableNumber) {
            $user = User::find($availableNumber->user_id);
            Log::debug('current available number is: ' . $availableNumber);
            Log::debug('current user based on available number is: ' . $user);

            Log::debug('Number found in AvailableNumber model: ' . $to);
            $twiml .= $this->handleAvailableNumberCall($to);

  

            // if ($user->device_token) {
            //     $response = Http::post(route('call.pushNotification'), [
            //         'deviceToken' => $user->device_token, // Replace with the actual field name
            //     ]);
            //     Log::debug('Notification attempt:' . $response->body());
            // }

            return response($twiml, 200)->header('Content-Type', 'text/xml');
        }

        // Check if the number exists in the CallTypeNumber model
        $callTypeNumber = CallTypeNumber::where('phone', $to)->first();
        if ($callTypeNumber) {
            Log::debug('Number found in CallTypeNumber model: ' . $to);
            $fromAttribute = $this->getFromAttribute($request->input('From'));

            Log::debug($request->input('From'));

            // $isFromClient = strpos($request->input('From'), 'client:') === 0;

            // if ( $isFromClient ) {
            //     Log::debug('Omega: call coming from client:');
            //     // $clientTwiml = "";
            //     // $numberToDial = '+15736523170';

            //     // Manually construct the TwiML
            /*    $clientTwiml = '<?xml version="1.0" encoding="UTF-8"?>'; */ 
            //     $clientTwiml .= '<Response><Dial answerOnBridge="true"><Client callerId="+15736523170">alice</Client></Dial></Response>';

            //     return response($clientTwiml, 200)->header('Content-Type', 'text/xml');
            // }


        

            $twiml .= $this->handleCallTypeNumberCall($to, $fromAttribute);
            return response($twiml, 200)->header('Content-Type', 'text/xml');
        }

        Log::debug('The to attribute did not match any records in the database.');

        $twimlResponse = '<?xml version="1.0" encoding="UTF-8"?>';
        $twimlResponse .= '<Response>';
        $twimlResponse .= '<Say>The to attribute did not match any records in the database.</Say>';
        $twimlResponse .= '</Response>';

        return response($twimlResponse, 200)->header('Content-Type', 'text/xml');
    }

    private function getFromAttribute($fromString)
    {
        Log::debug('Entered getFromAttribute function. Input: ' . $fromString);
        
        // Check if the string starts with 'client:'
        if (strpos($fromString, 'client:') === 0) {
            Log::debug('String starts with "client:". Processing accordingly.');
            // return '2055551234';  // Return a dummy number
            return '4793860440'; // from AR
        }
    
        // If it's a phone number
        if (strpos($fromString, '+1') === 0) {
            Log::debug('String starts with "+1". Processing as phone number.');
            return substr($fromString, 2);
        }
    
        Log::debug('Returning the string as-is.');
        return $fromString;  // Return as-is
    }

    public function handleAvailableNumberCall($to)
    {
        // Assume that the user_id is associated with the number in AvailableNumber
        $availableNumber = AvailableNumber::where('phone', $to)->first();
        $userId = (string) $availableNumber->user_id; // Ensure user id is a string
        Log::debug('User ID associated with the number: ' . $userId);

        Log::debug('AVAILABLE NUMBER FROM: ' . $availableNumber->from);
        $phoneState = $this->getStateFromPhoneNumber($availableNumber->from);
        Log::debug('Phone state:');
        Log::debug($phoneState);

        $stateModel = State::whereName($phoneState)->first();
        $callType = CallType::find($availableNumber->call_type_id);

        $onlineUsers = OnlineUser::byCallTypeAndState($callType, $stateModel)
            ->withSufficientBalance()
            ->withCallStatusWaiting()
            ->get();

        $onlineUsers = OnlineUser::prioritizeInternalAgents($onlineUsers);

        if (!$onlineUsers->count()) {
            Log::debug('No online user found.');
            return '<Response><Reject reason="busy" /></Response>';
        }

        $onlineUserIds = $onlineUsers->pluck('user_id')->toArray();

        Log::debug('onlineUserIds:');
        Log::debug($onlineUserIds);

        // Order the relevantBids by the amount column in descending order
        // $relevantBids = Bid::whereIn('user_id', $onlineUserIds)
        //     ->where('call_type_id', $availableNumber->call_type_id)
        //     ->orderBy('amount', 'desc')
        //     ->get();

        $twimlStart = '<Response>';
        $twimlEnd = '</Response>';
        $twimlBody = '';

        foreach ($onlineUserIds as $onlineUserId) {
            $user_id = $onlineUserId;
            $call_type_id = $availableNumber->call_type_id;
            $uniqueCallId = uniqid();

            $twimlBody .= '<Dial record="record-from-answer" recordingStatusCallbackMethod="GET" recordingStatusCallbackEvent="completed" recordingStatusCallback="https://allcalls.io/api/handle-call-recording?user_id=' . $user_id . '&amp;call_type_id=' . $call_type_id . '&amp;unique_call_id=' . $uniqueCallId . '&amp;from=' . urlencode($availableNumber->from) . '" callerId="+12518626328" timeout="20">';
            $twimlBody .= '<Client statusCallbackMethod="GET" statusCallbackEvent="initiated ringing answered completed" statusCallback="https://allcalls.io/api/handle-call-status?user_id=' . $user_id . '&amp;call_type_id=' . $call_type_id . '&amp;from=' . urlencode($availableNumber->from) . '&amp;unique_call_id=' . $uniqueCallId . '">';
            $twimlBody .= '<Identity>' . $user_id . '</Identity>';
            $twimlBody .= '<Parameter name="unique_call_id" value="' . $uniqueCallId . '"/>';
            $twimlBody .= '</Client>';
            $twimlBody .= '</Dial>';
        }

        // If nobody picks up, forward the call to an external number.
        $twimlBody .= '<Dial callerId="+12518626328">+18449831955</Dial>';


        $twiml = $twimlStart . $twimlBody . $twimlEnd;
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

        $stateModel = State::whereName($phoneState)->first();

        Log::debug('State of model:');
        Log::debug($stateModel->toArray());


        // Fetch all online users who have selected the same call type and state
        $onlineUsers = OnlineUser::byCallTypeAndState($callType, $stateModel)
            ->withSufficientBalance()
            ->withCallStatusWaiting()
            ->get();


        Log::debug('onlineUsers:', [
            'onlineUsers' => $onlineUsers,
        ]);

        $onlineUsers = OnlineUser::prioritizeInternalAgents($onlineUsers);

        Log::debug('onlineUsersAfterPrioritize:', [
            'onlineUsers' => $onlineUsers,
        ]);

        if (!$onlineUsers->count()) {
            Log::debug('No online user found.');
            return '<Response><Reject reason="busy" /></Response>';
        }

        $onlineUserIds = $onlineUsers->pluck('user_id')->toArray();
        $users = User::whereIn('id', $onlineUserIds)->get();

        Log::debug('Online users (User Model):');
        Log::debug($users);

        // Select the first user from this array of sorted online users based on who should receive the call first
        $selectedUser = $onlineUsers[0];
        Log::debug($selectedUser);

        // Find one of the available numbers and associate it with the selected user
        $availableNumber = $this->getAvailableNumberForUser($selectedUser->user_id, $from, $callType->id);

        Log::debug('Forwarding call to ' . $availableNumber->phone);

        // Return the available number in a <Dial> verb to forward the call to this number
        $twiml = '<Response><Dial callerId="+441146971410"><Number>' . $availableNumber->phone . '</Number></Dial></Response>';

        Log::debug('TWIML sent: ' . $twiml);

        return $twiml;
    }

    /**
     * Get the user with the highest bid for a specific call type.
     *
     * @param Collection $users    Users to check bids for
     * @param Model      $callType The type of call to match bids against
     *
     * @return User The user with the highest bid or a random user in case of a tie
     */
    public function getHighestBidder($users, $callType)
    {
        $highestBidderId = 0;
        $highestBidAmount = 0;
        $tiedBidderIds = [];

        Log::debug('Entered getHighestBidder');
        Log::debug('Total users: ' . $users->count());
        Log::debug('Call type ID: ' . $callType->id);

        // Loop through each user and get their bids
        foreach ($users as $user) {
            $bids = Bid::where('user_id', $user->id)
                ->where('call_type_id', $callType->id)
                ->get();

            // Compare each bid to find the highest bidder
            foreach ($bids as $bid) {
                if ($bid->amount > $highestBidAmount) {
                    $highestBidderId = $bid->user_id;
                    $highestBidAmount = $bid->amount;
                } elseif ($bid->amount == $highestBidAmount) {
                    $tiedBidderIds[] = $bid->user_id;
                }
            }
        }

        // Include the highest bidder in the tie-breakers list, if they exist
        if ($highestBidderId) {
            $tiedBidderIds[] = $highestBidderId;
            Log::debug('Highest bidder added to tie-breakers list');
        }

        // If there's a tie, select a random user from the tied bidders
        if (count($tiedBidderIds) > 0) {
            $highestBidderId = $tiedBidderIds[array_rand($tiedBidderIds)];
            Log::debug('Tie-breaker selected. Chosen user ID: ' . $highestBidderId);
        } else {
            Log::debug('No bidders found matching the criteria');
        }

        $user = User::find($highestBidderId);
        Log::debug($user ? 'Returning user ID: ' . $user->id : 'User not found in database');

        return $user;
    }

    // public function getAvailableNumberForUser($userId, $from, $callTypeId)
    // {
    //     // This method should return one of the available numbers and associate it with the selected user
    //     // Find the first available number where user_id is null
    //     $availableNumber = AvailableNumber::whereNull('user_id')->first();

    //     // THE FOLLOWING LINE IS TEMPORARY, WILL REMOVE AFTER TESTING PHASE:+441156471655
    //     // $availableNumber = AvailableNumber::wherePhone('+441156471655')->first();
    //     // $availableNumber = AvailableNumber::wherePhone('+441146971410')->first();
    //     // $availableNumber = AvailableNumber::wherePhone('7542270877')->first();

    //     // If there is no available number with user_id null
    //     if (!$availableNumber) {
    //         Log::error('No available number found');
    //         return '<Response><Say voice="alice" language="en-US">All of our agents are currently busy. Please try again later.</Say></Response>';
    //     }

    //     $availableNumber->user_id = $userId;
    //     $availableNumber->from = $from;
    //     $availableNumber->call_type_id = $callTypeId;
    //     $availableNumber->save();

    //     return $availableNumber;
    // }

    public function getAvailableNumberForUser($userId, $from, $callTypeId)
    {
        // This method should return one of the available numbers and associate it with the selected user

        // First, try to find an available number already associated with the user
        $availableNumber = AvailableNumber::where('user_id', $userId)
            ->orWhere(function ($query) {
                $query->whereNull('user_id');
            })
            ->first();

        if ( $availableNumber ) {
            Log::debug('Updating call type id for available number', [
                'user_id' => $userId,
                'call_type_id' => $callTypeId,
            ]);
            $availableNumber->call_type_id = $callTypeId;
            $availableNumber->save();
        }

        // Uncomment the lines below for temporary testing as needed
        // $availableNumber = AvailableNumber::wherePhone('+441156471655')->first();
        // $availableNumber = AvailableNumber::wherePhone('+441146971410')->first();
        // $availableNumber = AvailableNumber::wherePhone('7542270877')->first();

        // If there is no available number
        if (!$availableNumber) {
            Log::error('No available number found');
            return '<Response><Say voice="alice" language="en-US">All of our agents are currently busy. Please try again later.</Say></Response>';
        }


        if (is_null($availableNumber->user_id)) {
            Log::debug('user_id is null. Setting user_id to: ' . $userId);
            $availableNumber->user_id = $userId;
            $availableNumber->call_type_id = $callTypeId;
        }
        
        $availableNumber->from = $from;
        $availableNumber->save();
        
        return $availableNumber;
    }

    private function getStateFromPhoneNumber($phoneNumber)
    {
        $areaCodeToState = [
            '205' => 'AL',
            '251' => 'AL',
            '256' => 'AL',
            '334' => 'AL',
            '659' => 'AL',
            '938' => 'AL',
            '907' => 'AK',
            '480' => 'AZ',
            '520' => 'AZ',
            '602' => 'AZ',
            '623' => 'AZ',
            '928' => 'AZ',
            '479' => 'AR',
            '501' => 'AR',
            '870' => 'AR',
            '209' => 'CA',
            '213' => 'CA',
            '279' => 'CA',
            '310' => 'CA',
            '323' => 'CA',
            '341' => 'CA',
            '350' => 'CA',
            '408' => 'CA',
            '415' => 'CA',
            '424' => 'CA',
            '442' => 'CA',
            '510' => 'CA',
            '530' => 'CA',
            '559' => 'CA',
            '562' => 'CA',
            '619' => 'CA',
            '626' => 'CA',
            '628' => 'CA',
            '650' => 'CA',
            '657' => 'CA',
            '661' => 'CA',
            '669' => 'CA',
            '707' => 'CA',
            '714' => 'CA',
            '747' => 'CA',
            '760' => 'CA',
            '805' => 'CA',
            '818' => 'CA',
            '820' => 'CA',
            '831' => 'CA',
            '840' => 'CA',
            '858' => 'CA',
            '909' => 'CA',
            '916' => 'CA',
            '925' => 'CA',
            '949' => 'CA',
            '951' => 'CA',
            '303' => 'CO',
            '719' => 'CO',
            '720' => 'CO',
            '970' => 'CO',
            '983' => 'CO',
            '203' => 'CT',
            '475' => 'CT',
            '860' => 'CT',
            '959' => 'CT',
            '302' => 'DE',
            '239' => 'FL',
            '305' => 'FL',
            '321' => 'FL',
            '352' => 'FL',
            '386' => 'FL',
            '407' => 'FL',
            '448' => 'FL',
            '561' => 'FL',
            '656' => 'FL',
            '689' => 'FL',
            '727' => 'FL',
            '754' => 'FL',
            '772' => 'FL',
            '786' => 'FL',
            '813' => 'FL',
            '850' => 'FL',
            '863' => 'FL',
            '904' => 'FL',
            '941' => 'FL',
            '954' => 'FL',
            '229' => 'GA',
            '404' => 'GA',
            '470' => 'GA',
            '478' => 'GA',
            '678' => 'GA',
            '706' => 'GA',
            '762' => 'GA',
            '770' => 'GA',
            '912' => 'GA',
            '943' => 'GA',
            '808' => 'HI',
            '208' => 'ID',
            '986' => 'ID',
            '217' => 'IL',
            '224' => 'IL',
            '309' => 'IL',
            '312' => 'IL',
            '331' => 'IL',
            '447' => 'IL',
            '464' => 'IL',
            '618' => 'IL',
            '630' => 'IL',
            '708' => 'IL',
            '773' => 'IL',
            '779' => 'IL',
            '815' => 'IL',
            '847' => 'IL',
            '872' => 'IL',
            '219' => 'IN',
            '260' => 'IN',
            '317' => 'IN',
            '463' => 'IN',
            '574' => 'IN',
            '765' => 'IN',
            '812' => 'IN',
            '930' => 'IN',
            '319' => 'IA',
            '515' => 'IA',
            '563' => 'IA',
            '641' => 'IA',
            '712' => 'IA',
            '316' => 'KS',
            '620' => 'KS',
            '785' => 'KS',
            '913' => 'KS',
            '270' => 'KY',
            '364' => 'KY',
            '502' => 'KY',
            '606' => 'KY',
            '859' => 'KY',
            '225' => 'LA',
            '318' => 'LA',
            '337' => 'LA',
            '504' => 'LA',
            '985' => 'LA',
            '207' => 'ME',
            '240' => 'MD',
            '301' => 'MD',
            '410' => 'MD',
            '443' => 'MD',
            '667' => 'MD',
            '339' => 'MA',
            '351' => 'MA',
            '413' => 'MA',
            '508' => 'MA',
            '617' => 'MA',
            '774' => 'MA',
            '781' => 'MA',
            '857' => 'MA',
            '978' => 'MA',
            '231' => 'MI',
            '248' => 'MI',
            '269' => 'MI',
            '313' => 'MI',
            '517' => 'MI',
            '586' => 'MI',
            '616' => 'MI',
            '734' => 'MI',
            '810' => 'MI',
            '906' => 'MI',
            '947' => 'MI',
            '989' => 'MI',
            '218' => 'MN',
            '320' => 'MN',
            '507' => 'MN',
            '612' => 'MN',
            '651' => 'MN',
            '763' => 'MN',
            '952' => 'MN',
            '228' => 'MS',
            '601' => 'MS',
            '662' => 'MS',
            '769' => 'MS',
            '314' => 'MO',
            '417' => 'MO',
            '557' => 'MO',
            '573' => 'MO',
            '636' => 'MO',
            '660' => 'MO',
            '816' => 'MO',
            '406' => 'MT',
            '308' => 'NE',
            '402' => 'NE',
            '531' => 'NE',
            '702' => 'NV',
            '725' => 'NV',
            '775' => 'NV',
            '603' => 'NH',
            '201' => 'NJ',
            '551' => 'NJ',
            '609' => 'NJ',
            '640' => 'NJ',
            '732' => 'NJ',
            '848' => 'NJ',
            '856' => 'NJ',
            '862' => 'NJ',
            '908' => 'NJ',
            '973' => 'NJ',
            '505' => 'NM',
            '575' => 'NM',
            '212' => 'NY',
            '315' => 'NY',
            '332' => 'NY',
            '347' => 'NY',
            '363' => 'NY',
            '516' => 'NY',
            '518' => 'NY',
            '585' => 'NY',
            '607' => 'NY',
            '631' => 'NY',
            '646' => 'NY',
            '680' => 'NY',
            '716' => 'NY',
            '718' => 'NY',
            '838' => 'NY',
            '845' => 'NY',
            '914' => 'NY',
            '917' => 'NY',
            '929' => 'NY',
            '934' => 'NY',
            '252' => 'NC',
            '336' => 'NC',
            '472' => 'NC',
            '704' => 'NC',
            '743' => 'NC',
            '828' => 'NC',
            '910' => 'NC',
            '919' => 'NC',
            '980' => 'NC',
            '984' => 'NC',
            '701' => 'ND',
            '216' => 'OH',
            '220' => 'OH',
            '234' => 'OH',
            '326' => 'OH',
            '330' => 'OH',
            '380' => 'OH',
            '419' => 'OH',
            '440' => 'OH',
            '513' => 'OH',
            '567' => 'OH',
            '614' => 'OH',
            '740' => 'OH',
            '937' => 'OH',
            '405' => 'OK',
            '539' => 'OK',
            '572' => 'OK',
            '580' => 'OK',
            '918' => 'OK',
            '458' => 'OR',
            '503' => 'OR',
            '541' => 'OR',
            '971' => 'OR',
            '215' => 'PA',
            '223' => 'PA',
            '267' => 'PA',
            '272' => 'PA',
            '412' => 'PA',
            '445' => 'PA',
            '484' => 'PA',
            '570' => 'PA',
            '582' => 'PA',
            '610' => 'PA',
            '717' => 'PA',
            '724' => 'PA',
            '814' => 'PA',
            '835' => 'PA',
            '878' => 'PA',
            '401' => 'RI',
            '803' => 'SC',
            '839' => 'SC',
            '843' => 'SC',
            '854' => 'SC',
            '864' => 'SC',
            '605' => 'SD',
            '423' => 'TN',
            '615' => 'TN',
            '629' => 'TN',
            '731' => 'TN',
            '865' => 'TN',
            '901' => 'TN',
            '931' => 'TN',
            '210' => 'TX',
            '214' => 'TX',
            '254' => 'TX',
            '281' => 'TX',
            '325' => 'TX',
            '346' => 'TX',
            '361' => 'TX',
            '409' => 'TX',
            '430' => 'TX',
            '432' => 'TX',
            '469' => 'TX',
            '512' => 'TX',
            '682' => 'TX',
            '713' => 'TX',
            '726' => 'TX',
            '737' => 'TX',
            '806' => 'TX',
            '817' => 'TX',
            '830' => 'TX',
            '832' => 'TX',
            '903' => 'TX',
            '915' => 'TX',
            '936' => 'TX',
            '940' => 'TX',
            '956' => 'TX',
            '972' => 'TX',
            '979' => 'TX',
            '385' => 'UT',
            '435' => 'UT',
            '801' => 'UT',
            '802' => 'VT',
            '276' => 'VA',
            '434' => 'VA',
            '540' => 'VA',
            '571' => 'VA',
            '703' => 'VA',
            '757' => 'VA',
            '804' => 'VA',
            '206' => 'WA',
            '253' => 'WA',
            '360' => 'WA',
            '425' => 'WA',
            '509' => 'WA',
            '564' => 'WA',
            '304' => 'WV',
            '681' => 'WV',
            '262' => 'WI',
            '274' => 'WI',
            '414' => 'WI',
            '534' => 'WI',
            '608' => 'WI',
            '715' => 'WI',
            '920' => 'WI',
            '307' => 'WY',
            '202' => 'DC',
        ];

        $areaCode = substr($phoneNumber, 0, 3);

        if (array_key_exists($areaCode, $areaCodeToState)) {
            return $areaCodeToState[$areaCode];
        } else {
            return null;
        }
    }

    public function sendPushNotification(Request $request)
    {
        $data = $request->json()->all();

        $deviceToken = $data['deviceToken'];
        $title = 'Incoming Call...';
        $message = 'You have a client call incoming';

        $serverKey = env('FCM_SERVER_KEY'); // Replace with your OAuth 2.0 access token

        $notification = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $message,
            ],
            'android' => [
                'direct_boot_ok' => true,
            ],
        ];

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init('https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Error sending push notification: ' . curl_error($ch);
        } else {
            echo 'Push notification sent: ' . $response;
        }

        curl_close($ch);
    }

    public function saveDeviceToken(Request $request)
    {
        $user = auth()->user();
        $token = $request->input('token');

        if ($user) {
            $user->update([
                'device_token' => $token,
            ]);

            return response()->json(['message' => 'Device token saved successfully.']);
        } else {
            return response()->json(['message' => 'User not found.'], 404);
        }
    }
}
