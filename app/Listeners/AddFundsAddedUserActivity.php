<?php

namespace App\Listeners;

use App\Models\CallType;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddFundsAddedUserActivity
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
        Log::debug('AddFundsAddedUserActivity::handle');

        UserActivity::create([
            'action' => 'Funds added.',
            'data' => json_encode([
                'subTotal' => $event->subTotal,
                'processingFee' => $event->processingFee,
                'total' => $event->total,
                'bonus' => $event->bonus,
                'card' => $event->card,
            ]),
            'platform' => 'web',
            'user_id' => $event->user->id,
            'ip_address' => request()->ip() ?? null,
            'user_agent' => request()->header('User-Agent') ?? null,
        ]);
    }
}
