<?php

namespace App\Providers;

use App\Events\CallStatusUpdated;
use App\Events\FundsAdded;
use App\Models\Transaction;
use App\Events\MissedCallEvent;
use App\Listeners\SaveUserCall;
use App\Events\RingingCallEvent;
use App\Listeners\AddDefaultBids;
use App\Events\CompletedCallEvent;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\AddTargetsInRingba;
use Illuminate\Support\Facades\Event;
use App\Observers\TransactionObserver;
use Illuminate\Auth\Events\Registered;
use App\Events\UserCallTypeStateUpdated;
use App\Listeners\SendFundsReceiptEmail;
use App\Listeners\UpdateTargetsInRingba;
use App\Http\Controllers\FundsController;
use App\Listeners\ChargeUserForMissedCall;
use App\Listeners\ChargeUserForCompletedCall;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendWelcomeEmail::class,
        ],

        RingingCallEvent::class => [
            SaveUserCall::class,
        ],

        MissedCallEvent::class => [
            ChargeUserForMissedCall::class,
        ],

        CompletedCallEvent::class => [
            ChargeUserForCompletedCall::class,
        ],

        FundsAdded::class => [
            SendFundsReceiptEmail::class,
        ],

        CallStatusUpdated::class => [
            LogCallStatusChange::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Transaction::observe(TransactionObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
