<?php

namespace App\Listeners;

use Exception;
use App\Models\State;
use App\Models\CallType;
use App\Services\Ringba;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTargetsInRingba
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
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

        foreach ($selectedCallTypes as $type) {
            $targetName = $user->first_name . ' ' . $user->last_name . ' - ' . $user->id . ' - ' . $type->type;
            $targetStates = collect($type['selected_states'])->pluck('name')->toArray();
        
            try {
                $response = $ringba->createTarget($targetName, '+18045172235', $targetStates);
            } catch (Exception $e) {
                echo 'Error creating target: ' . $e->getMessage();
            }
        }
    }
}
