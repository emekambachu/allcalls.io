<?php

namespace App\Http\Controllers;

use Exception;
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
            'phone' => 'sometimes|required_without_all:zip,state',
            'zip' => 'sometimes|required_without_all:phone,state',
            'state' => 'sometimes|required_without_all:phone,zip',
            'vertical' => 'required|in:' . $validVerticals,
        ], $customMessages);

        $vertical = $verticalMapping[$request->input('vertical')];


        if ($request->has('phone')) {
            $state = config("states.area_codes.{$this->extractAreaCode($request->input('phone'))}");
        } elseif ($request->has('state')) {
            $state = $request->input('state');
        } elseif ($request->has('zip')) {
            $state = $this->zipToState($request->input('zip'));
        }
        if (!$state) {
            return response()->json(['message' => 'Could not map the state for the given input.'], 400);
        }    
    
        Log::debug('Omega: ' . $state . ', vertical: ' . $vertical);

        // Agent lookup
        $agentAvailable = $this->isAgentAvailable($state, $vertical);

        if ($agentAvailable) {
            return response()->json(['online' => true], 200);
        } else {
            return response()->json(['online' => false], 200);
        }
    }

    private function zipToState(string $zip): ?string
    {
        $url = "https://zip-api.eu/api/v1/info/US-{$zip}";
    
        try {
            $response = file_get_contents($url);
    
            if ($response === false) {
                return null;
            }
    
            $data = json_decode($response, true);
    
            return $data['state'] ?? null;
        } catch (Exception $e) {
            Log::error("Error fetching state from ZIP API: " . $e->getMessage());
            return null;
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
        if (strlen($state) == 2) {
            $stateModel = State::whereName($state)->firstOrFail();
        } else {
            $stateModel = State::whereFullName($state)->firstOrFail();
        }
    
        // Query for the call type
        $callTypeModel = CallType::whereType($vertical)->firstOrFail();
    
        // Check for online users matching the criteria
        $onlineUsers = OnlineUser::byCallTypeAndState($callTypeModel, $stateModel)
            ->withSufficientBalance($callTypeModel)
            ->withCallStatusWaiting()
            ->get();
    
        // Here you can put your actual logic to determine if an agent is available
        return $onlineUsers->count() > 0;
    }    
}
