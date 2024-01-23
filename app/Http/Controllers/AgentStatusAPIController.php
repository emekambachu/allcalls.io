<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Bid;
use App\Models\State;
use App\Models\CallType;
use App\Models\OnlineUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AgentStatusAPIController extends Controller
{
    private $affiliates = [
        '1' => ['api_key' => 'b6GHX82jfd83DNsk29JKm39Fna8z', 'affiliate_percentage' => 10, 'fixed_price' => 20],
        '2' => ['api_key' => 'c9JDk28SkxJD28XsK02JdOksI83D', 'affiliate_percentage' => 10, 'fixed_price' => 20],
        '3' => ['api_key' => 'iPQJ39dS82jNfn30Ns82kPqJ8x7L', 'affiliate_percentage' => 10],
        '4' => ['api_key' => '8sKkX82jKS93Kds82kDn8sKd8sKd', 'affiliate_percentage' => 10, 'fixed_price' => 50],
        '5' => ['api_key' => '3jNdo82JD8sK2Mx8s2kMnD8sMx9j', 'affiliate_percentage' => 10],
        '6' => ['api_key' => '0Jdj2Ks98sKdj28xS82jsXo92Hs7', 'affiliate_percentage' => 10],
        '7' => ['api_key' => 'x8Ksk29MnD8Ks92Jx82kMx7kOsJ7', 'affiliate_percentage' => 10],
        '8' => ['api_key' => 'kPQJ7xSkD8s9Mdx7KsJd8Ks7Jd82', 'affiliate_percentage' => 10],
        '9' => ['api_key' => '82JDks8KjMnDx7LsKd8S2LmXj82K', 'affiliate_percentage' => 10],
        '10' => ['api_key' => '82jKdS7Mx8Ks92LkDs8xM7kDj82N', 'affiliate_percentage' => 10],

        '11' => ['api_key' => '4sJd82KxMnD7LsK8PQJx9Mdx7KsJ', 'affiliate_percentage' => 10],
        '12' => ['api_key' => '7xSkD8sJd82KjMnDx9LsKd8S2LmX', 'affiliate_percentage' => 10],
        '13' => ['api_key' => '82jKsMx8Ks92LkDs8xM7kDj82N8J', 'affiliate_percentage' => 10],
        '14' => ['api_key' => 'x8Ks92Jx82kMx7kOsJ7kPQJ7xSkD', 'affiliate_percentage' => 10],
        '15' => ['api_key' => '9Mdx7KsJd8Ks7Jd82JDks8KjMnDx', 'affiliate_percentage' => 10],
        '16' => ['api_key' => '7LsKd8S2LmXj82K82jKdS7Mx8Ks9', 'affiliate_percentage' => 10],
        '17' => ['api_key' => '2LkDs8xM7kDj82N0Jdj2Ks98sKdj', 'affiliate_percentage' => 10],
        '18' => ['api_key' => '28xS82jsXo92Hs7x8Ksk29MnD8Ks', 'affiliate_percentage' => 10],
        '19' => ['api_key' => '92Jx82kMx7kOsJ73jNdo82JD8sK2', 'affiliate_percentage' => 10],
        '20' => ['api_key' => 'Mx8s2kMnD8sMx9j8sKkX82jKS93K', 'affiliate_percentage' => 10],

        '21' => ['api_key' => 'KsJd82Kx8Jd8sMdx7Ls92Jx82kMo', 'affiliate_percentage' => 10],
        '22' => ['api_key' => 'MnDx9LsK8S2LmXj82KdS7Mx8Ks92', 'affiliate_percentage' => 10],
        '23' => ['api_key' => '8sKdj28xS82jsXo92Hs7x8Ksk29M', 'affiliate_percentage' => 10],
        '24' => ['api_key' => 'N0Jdj2Ks98sKdj28xS82jsXo92Hs', 'affiliate_percentage' => 10],
        '25' => ['api_key' => '8Ks92Jx82kMx7kOsJ7kPQJ7xSkD8', 'affiliate_percentage' => 10],
        '26' => ['api_key' => 'KjMnDx7LsKd8S2LmXj82K82jKdS7', 'affiliate_percentage' => 10],
        '27' => ['api_key' => 'Mx8Ks92LkDs8xM7kDj82N0Jdj2Ks', 'affiliate_percentage' => 10],
        '28' => ['api_key' => '98sKdj28xS82jsXo92Hs7x8Ksk29', 'affiliate_percentage' => 10],
        '29' => ['api_key' => 'MnD8Ks92Jx82kMx7kOsJ73jNdo82', 'affiliate_percentage' => 10],
        '30' => ['api_key' => 'JD8sK2Mx8s2kMnD8sMx9j8sKkX82', 'affiliate_percentage' => 10],
        
    ];

    /**
     * Check if an agent is available for a given phone number's area code and vertical.
     *
     * @param  Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function show(Request $request): JsonResponse
    {
        Log::debug('api-logs:agent-status-price: Request Data', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'query_string' => $request->getQueryString(),
        ]);

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
            'affiliate_id' => 'required',
            'api_key' => 'required',
        ], $customMessages);

        $vertical = $verticalMapping[$request->input('vertical')];


        if ($request->has('phone')) {
            $normalizedPhone = $this->normalizePhoneNumber($request->input('phone'));
            $state = config("states.area_codes.{$this->extractAreaCode($normalizedPhone)}");
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
        $price = $this->getPriceForVertical($vertical);

        if ($request->has('affiliate_id') && $request->has('api_key')) {
            $affiliate = $this->affiliates[$request->input('affiliate_id')] ?? null;

            if ($affiliate && $affiliate['api_key'] == $request->input('api_key')) {
                // Check if the affiliate has a fixed_price property
                if (isset($affiliate['fixed_price'])) {
                    $price = $affiliate['fixed_price'];
                } else {
                    $percentage = (100 - $affiliate['affiliate_percentage']) / 100;
                    $price *= $percentage;
                }
            } else {
                return response()->json(['message' => 'Invalid affiliate credentials.'], 400);
            }
        }

        $response = $agentAvailable
            ? response()->json(['online' => true, 'price' => $price], 200)
            : response()->json(['online' => false, 'price' => $price], 200);

        Log::debug('api-logs:agent-status-price: Response Data', [
            'data' => json_decode($response->getContent(), true),
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }

    public function showWithoutPrice(Request $request): JsonResponse
    {
        Log::debug('api-logs:agent-status: Request Data', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'query_string' => $request->getQueryString(),
        ]);

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
            'affiliate_id' => 'required',
            'api_key' => 'required',
        ], $customMessages);

        $vertical = $verticalMapping[$request->input('vertical')];


        if ($request->has('phone')) {
            $normalizedPhone = $this->normalizePhoneNumber($request->input('phone'));
            $state = config("states.area_codes.{$this->extractAreaCode($normalizedPhone)}");
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
        $price = $this->getPriceForVertical($vertical);

        if ($request->has('affiliate_id') && $request->has('api_key')) {
            $affiliate = $this->affiliates[$request->input('affiliate_id')] ?? null;

            if ($affiliate && $affiliate['api_key'] == $request->input('api_key')) {
                // Check if the affiliate has a fixed_price property
                if (isset($affiliate['fixed_price'])) {
                    $price = $affiliate['fixed_price'];
                } else {
                    $percentage = (100 - $affiliate['affiliate_percentage']) / 100;
                    $price *= $percentage;
                }
            } else {
                return response()->json(['message' => 'Invalid affiliate credentials.'], 400);
            }
        }

        $response = $agentAvailable
            ? response()->json(['online' => true], 200)
            : response()->json(['online' => false], 200);

        Log::debug('api-logs:agent-status:', [
            'data' => json_decode($response->getContent(), true),
            'status' => $response->getStatusCode(),
        ]);

        return $response;
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
    
        // Initial Online Users Query
        $onlineUsersQuery = OnlineUser::query();
    
        // Apply byCallTypeAndState scope and log count
        $onlineUsersQuery->byCallTypeAndState($callTypeModel, $stateModel);
        $countAfterStateAndCallType = $onlineUsersQuery->count();
        Log::debug('Count after applying byCallTypeAndState', ['count' => $countAfterStateAndCallType]);
    
        // Apply withSufficientBalance scope and log count
        $onlineUsersQuery->withSufficientBalance($callTypeModel);
        $countAfterSufficientBalance = $onlineUsersQuery->count();
        Log::debug('Count after applying withSufficientBalance', ['count' => $countAfterSufficientBalance]);
    
        // Apply withCallStatusWaiting scope and log count
        $onlineUsersQuery->withCallStatusWaiting();
        $countAfterCallStatusWaiting = $onlineUsersQuery->count();
        Log::debug('Count after applying withCallStatusWaiting', ['count' => $countAfterCallStatusWaiting]);
    
        // Final count
        $finalCount = $onlineUsersQuery->count();
        Log::debug('Final count of online users', ['count' => $finalCount]);
    
        // Return true if there are any users, false otherwise
        return $finalCount > 0;
    }    

    private function getPriceForVertical(string $vertical): float
    {
        // Query for the call type
        $callType = CallType::whereType($vertical)->firstOrFail();
    
        // Get the top two highest bid amounts for the call type
        $highestBids = Bid::where('call_type_id', $callType->id)
            ->orderBy('amount', 'desc')
            ->take(2)
            ->get();
    
        // If there are no bids, or only one bid, return $25
        if ($highestBids->count() <= 1) {
            return 35;
        }
    
        $highestBid = $highestBids[0]->amount;
        $secondHighestBid = $highestBids[1]->amount;
    
        // If the second highest bid is the same as the highest bid
        // Or if the difference between the highest bid and the second highest bid is $1
        if ($secondHighestBid == $highestBid || $highestBid - $secondHighestBid == 1) {
            return $highestBid;
        }
    
        // Otherwise, return the second highest bid amount + 1
        return $secondHighestBid + 1;
    }

    protected function normalizePhoneNumber($inputNumber)
    {
        // Remove any non-numeric characters
        $cleanNumber = preg_replace('/\D/', '', $inputNumber);

        // Check if the number starts with '1' (U.S. country code) and remove it
        if (strlen($cleanNumber) == 11 && substr($cleanNumber, 0, 1) == '1') {
            $cleanNumber = substr($cleanNumber, 1);
        }

        return $cleanNumber;
    }
}