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
        '4' => ['api_key' => '8sKkX82jKS93Kds82kDn8sKd8sKd', 'affiliate_percentage' => 10],
        '5' => ['api_key' => '3jNdo82JD8sK2Mx8s2kMnD8sMx9j', 'affiliate_percentage' => 10],
        '6' => ['api_key' => '0Jdj2Ks98sKdj28xS82jsXo92Hs7', 'affiliate_percentage' => 10],
        '7' => ['api_key' => 'x8Ksk29MnD8Ks92Jx82kMx7kOsJ7', 'affiliate_percentage' => 10],
        '8' => ['api_key' => 'kPQJ7xSkD8s9Mdx7KsJd8Ks7Jd82', 'affiliate_percentage' => 10],
        '9' => ['api_key' => '82JDks8KjMnDx7LsKd8S2LmXj82K', 'affiliate_percentage' => 10],
        '10' => ['api_key' => '82jKdS7Mx8Ks92LkDs8xM7kDj82N', 'affiliate_percentage' => 10],
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
        Log::debug('api-logs: Request Data', [
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

        Log::debug('api-logs: Response Data', [
            'data' => json_decode($response->getContent(), true),
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }

    public function showWithoutPrice(Request $request): JsonResponse
    {
        Log::debug('api-logs: Request Data', [
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

        Log::debug('api-logs: Response Data', [
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

        // Check for online users matching the criteria
        $onlineUsers = OnlineUser::byCallTypeAndState($callTypeModel, $stateModel)
            ->withSufficientBalance($callTypeModel)
            ->withCallStatusWaiting()
            ->get();

        // Here you can put your actual logic to determine if an agent is available
        return $onlineUsers->count() > 0;
    }

    private function getPriceForVertical(string $vertical): float
    {
        // Query for the call type
        $callType = CallType::whereType($vertical)->firstOrFail();

        // Get the total number of bids for this call type
        $bidCount = Bid::where('call_type_id', $callType->id)->count();

        // If there are no bids or only one bid, return $25
        if ($bidCount <= 1) {
            return 25;
        }

        // Get the highest and second highest bid amounts for the call type
        $highestBids = Bid::where('call_type_id', $callType->id)
            ->orderBy('amount', 'desc')
            ->take(2) // This will take the highest and second highest bids
            ->get();

        // If there are not enough bids to compare, return $25
        if ($highestBids->count() < 2) {
            return 25;
        }

        $highestBid = $highestBids[0];
        $secondHighestBid = $highestBids[1];

        // Check if the second highest bid is the same as the highest bid, or only $1 less
        if ($secondHighestBid->amount == $highestBid->amount || $highestBid->amount - $secondHighestBid->amount == 1) {
            return $highestBid->amount;
        }

        // Otherwise, return the second highest bid amount + 1
        return $secondHighestBid->amount + 1;
    }
}