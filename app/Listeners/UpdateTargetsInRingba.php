<?php

namespace App\Listeners;

use Exception;
use App\Models\User;
use App\Models\State;
use App\Models\CallType;
use App\Services\Ringba;
use App\Models\UserCallTypeState;
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


        $ringba = new Ringba;

        $user = User::find($event->userId);
        $targets = $ringba->getAllTargets();

        foreach ($event->toDelete as $record) {
            $callTypeId = $record['call_type_id'];
            $callType = CallType::find($callTypeId);

            $targetName = $user->first_name . ' ' . $user->last_name . ' - ' . $user->id . ' - ' . $callType->type . ' - ' . $callType->id;

            foreach ($targets as $target) {
                if ($target['name'] == $targetName) {
                    $ringba->deleteTarget($target['id']);
                    break;
                }
            }
        }

        foreach ($event->toInsert as $record) {
            $callTypeId = $record['call_type_id'];
            $callType = CallType::find($callTypeId);
            $state = State::find($record['state_id']);

            $targetName = $user->first_name . ' ' . $user->last_name . ' - ' . $user->id . ' - ' . $callType->type . ' - ' . $callType->id;
            $ringba->createTarget($targetName, '+18045172235', [$state->name]);
        }
    }
}
