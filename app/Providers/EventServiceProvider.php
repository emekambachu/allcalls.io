<?php

namespace App\Providers;

use App\Events\CompletedCallEvent;
use App\Events\MissedCallEvent;
use App\Events\RingingCallEvent;
use App\Models\Transaction;
use App\Listeners\AddDefaultBids;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\AddTargetsInRingba;
use Illuminate\Support\Facades\Event;
use App\Observers\TransactionObserver;
use Illuminate\Auth\Events\Registered;
use App\Events\UserCallTypeStateUpdated;
use App\Listeners\ChargeUserForCompletedCall;
use App\Listeners\UpdateTargetsInRingba;
use App\Listeners\ChargeUserForMissedCall;
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
            SaveUserCall::class
        ],

        MissedCallEvent::class => [
            ChargeUserForMissedCall::class,
        ],

        CompletedCallEvent::class => [
            ChargeUserForCompletedCall::class,
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
