<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallTypeNumber;
use App\Models\AvailableNumber;
use App\Models\User;
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
                $twiml = $this->handleCallTypeNumberCall($to);
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

        return $twiml;
    }

    public function handleCallTypeNumberCall($to)
    {
        // Determine the call type based on the 'To' number
        $callTypeNumber = CallTypeNumber::where('phone', $to)->first();
        $callType = $callTypeNumber->call_type;
    
        Log::debug('Call type: ' . $callType);
    
        // Fetch all online users who have selected the same call type and state
        $users = $this->getOnlineUsers($callType);
    
        // Select a user from this group
        $selectedUser = $users;
    
        Log::debug('Selected user: ' . $selectedUser->id);
    
        // Find one of the available numbers and associate it with the selected user
        $availableNumber = $this->getAvailableNumberForUser($selectedUser);
    
        Log::debug('Forwarding call to ' . $availableNumber->phone);
    
        // Return the available number in a <Dial> verb to forward the call to this number
        $twiml = '<Response><Dial><Number>' . $availableNumber->phone . '</Number></Dial></Response>';
    
        return $twiml;
    }
    

    public function getOnlineUsers($callType)
    {
        // This method should return all online users who have selected the same call type and state
        // This is just a placeholder method. You will need to implement this logic based on your application.
        // For now, just get the first user
        return User::first();
    }

    public function getAvailableNumberForUser($user)
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
    
        $availableNumber->user_id = $user->id;
        $availableNumber->save();
    
        return $availableNumber;
    }    
}
