<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\CallStatusUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserCallStatus
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
    public function handle(CallStatusUpdated $event): void
    {
        $newStatus = $event->info['CallStatus'];
        $mappedStatus = $this->mapTwilioStatusToLocalStatus($newStatus);

        $user = User::find($event->user->id);

        if ($user) {
            $user->update(['call_status' => $mappedStatus]);

            // Log the updated status
            Log::debug('User call status updated.', [
                'user_id' => $user->id,
                'new_status' => $mappedStatus,
                'twilio_status' => $newStatus,
            ]);
        } else {
            // Optionally, log that the user was not found
            Log::debug('User not found for status update.', [
                'user_id' => $event->user->id
            ]);
        }
    }


    protected function mapTwilioStatusToLocalStatus($twilioStatus)
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
