<?php

namespace App\Listeners;

use App\Models\ActiveUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateActiveUserStatus
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
    public function handle(object $event): void
    {
        // Here, $event->user and $event->info will contain the user and call status info.
        $newStatus = $event->info['CallStatus'];  // Extracting the new CallStatus

        // Update the status in the ActiveUser table
        // Assuming that you might want to map Twilio statuses to your own set of statuses
        $mappedStatus = $this->mapCallStatusToActiveUserStatus($newStatus);

        $activeUser = ActiveUser::whereUserId($event->user->id)->first();

        if ($activeUser) {
            $activeUser->update([
                'status' => $mappedStatus
            ]);

            Log::debug('Status changed for user ' . $activeUser->user_id . ' ' . $mappedStatus);
        }
    }

    protected function mapCallStatusToActiveUserStatus($twilioStatus)
    {
        $statusMap = [
            'ringing' => 'Ringing', // The destination number has started ringing.
            'in-progress' => 'Talking', // The call has been connected, and the connection is currently active.
            'completed' => 'Waiting', // The call was completed (this could map back to waiting)
    
            'busy' => 'Waiting', // Twilio dialed the number, but received a busy response.
            'no-answer' => 'Waiting', // Twilio dialed the number but no one answered before the timeout.
            'canceled' => 'Waiting', // An outbound call was canceled or an incoming call was disconnected.
            'failed' => 'Waiting' // Twilio's carriers could not connect the call.
        ];
    
        return $statusMap[$twilioStatus] ?? 'Waiting';  // default to 'Waiting'
    }

}
