<?php

namespace App\Listeners;

use Exception;
use Faker\Factory;
use App\Models\State;
use App\Models\CallType;
use App\Services\Ringba;
use App\Models\UsedPhoneNumber;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddTargetsInRingba
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(Registered $event): void
    {
        Log::debug('Add Targets In Ringba Called');

        $user = $event->user;
        Log::debug('Registered User:', ['user' => $user->toArray()]);

        // Fetch all call types associated with it, then fetch all states associated with them.
        $records = UserCallTypeState::forUser($user);
        Log::debug('UserCallTypeState Records:', ['records' => $records->toArray()]);

        $callTypeIds = array_values(array_unique(array_column($records->toArray(), 'call_type_id')));
        Log::debug('Call Type IDs:', ['callTypeIds' => $callTypeIds]);

        $selectedCallTypes = CallType::whereIn('id', $callTypeIds)->get();
        Log::debug('Selected CallTypes:', ['selectedCallTypes' => $selectedCallTypes->toArray()]);

        $allStates = State::all();
        Log::debug('All States:', ['allStates' => $allStates->toArray()]);

        $selectedCallTypes = $selectedCallTypes->map(function ($callType) use ($records, $allStates) {
            $currentCallTypeRecords = $records->filter(function ($record) use ($callType) {
                return $record->call_type_id === $callType->id;
            });

            // compile the state ids into a single array.
            $stateIds = $currentCallTypeRecords->map(function ($record) {
                return $record->state_id;
            });

            $selectedStates = $allStates->whereIn('id', $stateIds);
            $callType->selected_states = $selectedStates->toArray();

            return $callType;
        });

        Log::debug('Mapped Selected CallTypes:', ['selectedCallTypes' => $selectedCallTypes->toArray()]);

        $ringba = new Ringba();
        $faker = Factory::create('en_US');

        foreach ($selectedCallTypes as $type) {
            $targetName = $user->first_name . ' ' . $user->last_name . ' - ' . $user->id . ' - ' . $type->type . ' - ' . $type->id;
            $targetStates = collect($type['selected_states'])->pluck('name')->toArray();

            // Get all used phone numbers.
            $usedPhoneNumbers = UsedPhoneNumber::all()->pluck('phone')->toArray();
            Log::debug('Used Phone Numbers:', ['usedPhoneNumbers' => $usedPhoneNumbers]);

            // Generate a unique phone number.
            $phoneNumber = $faker->phoneNumber;
            while (in_array($phoneNumber, $usedPhoneNumbers)) {
                $phoneNumber = $faker->phoneNumber;
            }
            Log::debug('Generated Phone Number:', ['phoneNumber' => $phoneNumber]);

            // Store the generated number in the database.
            UsedPhoneNumber::create(['phone' => $phoneNumber]);

            try {
                $response = $ringba->createTarget($targetName, $phoneNumber, $targetStates);
                Log::debug('Create Target Response:', ['response' => $response]);

                $targetId = $response['target']['id']; // Fetch the target ID from the response
                Log::debug('Target ID:', ['targetId' => $targetId]);

                // Fetch all call plans
                $responseCallPlans = $ringba->getCallPlans();
                $callPlans = $responseCallPlans['callPlans'];
                Log::debug('Call Plans:', ['callPlans' => $callPlans]);

                // Construct the expected call plan name
                $expectedCallPlanName = $type->type . " - " . $type->id;

                // Find the call plan that matches the expected name
                $matchingCallPlan = collect($callPlans)->firstWhere('name', $expectedCallPlanName);

                Log::debug('matchingCallPlan', $matchingCallPlan);

                if ($matchingCallPlan) {
                    // Add a route to the call plan
                    try {
                        $response = $ringba->addRouteToCallPlan($matchingCallPlan['id'], $targetId, $phoneNumber, $targetStates);
                        Log::debug('Added Route to Call Plan:', ['callPlanId' => $matchingCallPlan['id'], 'targetId' => $targetId, 'phoneNumber' => $phoneNumber, 'targetStates' => $targetStates, 'response' => $response]);
                    } catch (Exception $e) {
                        Log::error('Error adding route to call plan: ' . $e->getMessage());
                    }
                } else {
                    Log::warning("No matching call plan found for type: " . $type->type);
                }

            } catch (Exception $e) {
                Log::error('Error creating target: ' . $e->getMessage());
            }
        }
    }
}