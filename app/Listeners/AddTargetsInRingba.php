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

        // Fetch all call types associated with it, then fetch all states associated with them.
        $records = UserCallTypeState::forUser($user);
        $callTypeIds = array_values(array_unique(array_column($records->toArray(), 'call_type_id')));
        $selectedCallTypes = CallType::whereIn('id', $callTypeIds)->get();
        $allStates = State::all();

        $selectedCallTypes = $selectedCallTypes->map(function($callType) use ($records, $allStates) {
            $currentCallTypeRecords = $records->filter(function($record) use ($callType) {
                return $record->call_type_id === $callType->id;
            });

            // compile the state ids into a single array.
            $stateIds = $currentCallTypeRecords->map(function($record) {
                return $record->state_id;
            });

            $selectedStates = $allStates->whereIn('id', $stateIds);
            $callType->selected_states = $selectedStates->toArray();

            return $callType;
        });

        $ringba = new Ringba();
        $faker = Factory::create('en_US');

        foreach ($selectedCallTypes as $type) {
            $targetName = $user->first_name . ' ' . $user->last_name . ' - ' . $user->id . ' - ' . $type->type . ' - ' . $type->id;
            $targetStates = collect($type['selected_states'])->pluck('name')->toArray();

            // Get all used phone numbers.
            $usedPhoneNumbers = UsedPhoneNumber::all()->pluck('phone')->toArray();

            // Generate a unique phone number.
            $phoneNumber = $faker->phoneNumber;
            while(in_array($phoneNumber, $usedPhoneNumbers)) {
                $phoneNumber = $faker->phoneNumber;
            }

            // Store the generated number in the database.
            UsedPhoneNumber::create(['phone' => $phoneNumber]);
        
            try {
                $response = $ringba->createTarget($targetName, $phoneNumber, $targetStates);
                $targetId = $response['target']['id']; // Fetch the target ID from the response

                // Fetch all call plans
                $responseCallPlans = $ringba->getCallPlans();
                $callPlans = $responseCallPlans['callPlans'];

                // Find the call plan that matches the current call type
                $matchingCallPlan = collect($callPlans)->firstWhere('name', $type->type);

                if ($matchingCallPlan) {
                    // Add a route to the call plan
                    try {
                        $ringba->addRouteToCallPlan($matchingCallPlan['id'], $targetId, $phoneNumber, $targetStates);
                    } catch (Exception $e) {
                        echo 'Error adding route to call plan: ' . $e->getMessage();
                    }
                } else {
                    echo "No matching call plan found for type: " . $type->type;
                }

            } catch (Exception $e) {
                echo 'Error creating target: ' . $e->getMessage();
            }
        }
    }
}
