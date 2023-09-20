<?php

namespace App\Http\Controllers;

use App\Models\State;
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

        Log::debug('Omega: ' . $state . ', vertical: ' . $vertical);

        // Agent lookup
        $agentAvailable = $this->isAgentAvailable($state, $vertical);

        if (true) {
            return response()->json(['status' => 'success', 'message' => 'Agent available'], 200);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'No agent available'], 404);
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
}
