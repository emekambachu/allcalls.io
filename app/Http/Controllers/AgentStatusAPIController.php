<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\CallType;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AgentStatusAPIController extends Controller
{
    /**
     * Check if an agent is available for a given phone number's area code and vertical.
     *
     * @param  Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function show(Request $request): JsonResponse
    {
        // Define vertical mapping
        $verticalMapping = [
            'auto_insurance' => 'Auto Insurance',
            'final_expense' => 'Final Expense',
            'u65_health' => 'U65 Health',
            'aca' => 'ACA',
            'medicare' => 'Medicare',
        ];

        // Validate inputs
        $validVerticals = implode(',', array_keys($verticalMapping));
        $customMessages = [
            'vertical.in' => 'The selected vertical is invalid. Valid options are: ' . $validVerticals,
        ];

        $request->validate([
            'phone' => 'required',
            'vertical' => 'required|in:' . $validVerticals,
        ], $customMessages);


        $phone = $request->input('phone');
        $vertical = $verticalMapping[$request->input('vertical')];

        // Get state of the phone number
        $state = config("states.area_codes.{$this->extractAreaCode($phone)}");

        if (! $state) {
            return response()->json(['message' => 'Could not map the state for the given phone number.'], 400);
        }

        Log::debug('Omega: ' . $state . ', vertical: ' . $vertical);

        // Agent lookup
        $agentAvailable = $this->isAgentAvailable($state, $vertical);

        if ($agentAvailable) {
            return response()->json(['status' => 'success', 'online' => true], 200);
        } else {
            return response()->json(['status' => 'success', 'online' => false], 200);
        }
    }

    /**
     * Extract the first 3 characters (area code) from a phone number.
     *
     * @param  string $phoneNumber
     * @return string
     */
    private function extractAreaCode(string $phoneNumber): string
    {
        return substr($phoneNumber, 0, 3);
    }

    private function isAgentAvailable(string $state, string $vertical): bool
    {
        // Define vertical mapping
        $verticalMapping = [
            'auto_insurance' => 'Auto Insurance',
            'final_expense' => 'Final Expense',
            'u65_health' => 'U65 Health',
            'aca' => 'ACA',
            'medicare' => 'Medicare',
        ];
    
        // Map the state string to the corresponding State model
        $stateModel = State::whereName($state)->firstOrFail();
        
        // Remap the vertical string to the corresponding model name using the mapping
        $mappedVertical = $verticalMapping[$vertical] ?? null;
    
        if (!$mappedVertical) {
            // Handle invalid vertical here
            return false;
        }
    
        // Query for the call type
        $callTypeModel = CallType::whereType($mappedVertical)->firstOrFail();
    
        // Check for online users matching the criteria
        $onlineUsers = OnlineUser::byCallTypeAndState($callTypeModel, $stateModel)
            ->withSufficientBalance()
            ->withCallStatusWaiting()
            ->get();
    
        // Here you can put your actual logic to determine if an agent is available
        return $onlineUsers->count() > 0;
    }    
}
