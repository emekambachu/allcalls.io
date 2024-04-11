<?php

namespace App\Listeners;

use App\Models\UserActivity;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddCompletedCallUserActivity
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
        Log::debug('AddCompletedCallUserActivity::handle');

        // Log::debug('AddCompletedCallUserActivity::type ' . $event->callType->type);

        // if ($event->callType->type === 'NO BUFFER - Final Expense - Fronter') {
        //     Log::debug('AddCompletedCallUserActivity::discontinue is NO BUFFER - Final Expense - Fronter');
        //     return;
        // }

        UserActivity::create([
            'action' => 'Charged for call.',
            'data' => json_encode([
                'name' => $event->user->first_name . ' ' . $event->user->last_name,
            ]),
            'platform' => 'web',
            'user_id' => $event->user->id,
            'ip_address' => request()->ip() ?? null,
            'user_agent' => request()->header('User-Agent') ?? null,
        ]);
    }
}
