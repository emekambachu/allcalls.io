<?php

namespace App\Listeners;

use Exception;
use App\Models\User;
use App\Models\State;
use App\Models\CallType;
use App\Services\Ringba;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use App\Events\UserCallTypeStateUpdated;
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
    public function handle(UserCallTypeStateUpdated $event): void
    {
        Log::debug('Update targets now...');
        Log::debug($event->userId);
        Log::debug($event->toDelete);
        Log::debug($event->toInsert);

        $user = User::find($event->userId);
        $ringba = new Ringba;


        $callTypeIds = array_unique(
            array_merge(
                array_column($event->toDelete, 'call_type_id'),
                array_column($event->toInsert, 'call_type_id')
            )
        );

        // Deleting Targets
        foreach ($callTypeIds as $callTypeId) {
            $callType = CallType::find($callTypeId);
            $targetName = $user->first_name . ' ' . $user->last_name . ' - ' . $user->id . ' - ' . $callType->type . ' - ' . $callType->id;

            $targets = $ringba->getAllTargets();
            foreach ($targets as $target) {
                if ($target['name'] === $targetName) {
                    $ringba->deleteTarget($target['id']);
                    break;
                }
            }
        }

        // Inserting Targets
        foreach ($callTypeIds as $callTypeId) {
            $callType = CallType::find($callTypeId);
            $states = DB::table('users_call_type_state')
                ->where('user_id', $user->id)
                ->where('call_type_id', $callTypeId)
                ->pluck('state_id')
                ->map(function ($stateId) {
                    return State::find($stateId)->name;
                })
                ->toArray();

            $targetName = $user->first_name . ' ' . $user->last_name . ' - ' . $user->id . ' - ' . $callType->type . ' - ' . $callType->id;
            $ringba->createTarget($targetName, '+18045172235', $states);
        }
    }
}
