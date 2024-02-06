<?php

namespace App\Providers;

use App\Events\FundsAdded;
use App\Events\CareerEvent;
use App\Events\FundsTooLow;
use App\Events\InviteAgent;
use App\Models\Transaction;
use App\Events\MissedCallEvent;
use App\Listeners\SaveUserCall;
use App\Events\RingingCallEvent;
use App\Events\CallStatusUpdated;
use App\Listeners\AddDefaultBids;
use App\Listeners\CareerListener;
use App\Events\CompletedCallEvent;
use App\Events\InitiatedCallEvent;
use App\Listeners\MakeUserOffline;
use App\Events\OnboardingCompleted;
use App\Listeners\SendWelcomeEmail;
use App\Events\OnlineUserListUpdated;
use App\Listeners\AddTargetsInRingba;
use Illuminate\Support\Facades\Event;
use App\Listeners\LogCallStatusChange;
use App\Listeners\UnlockClientForUser;
use App\Observers\TransactionObserver;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UpdateUserCallStatus;
use App\Events\UserCallTypeStateUpdated;
use App\Listeners\SendFundsReceiptEmail;
use App\Listeners\UpdateTargetsInRingba;
use App\Http\Controllers\FundsController;
use App\Listeners\UpdateActiveUserStatus;
use App\Listeners\ChargeUserForMissedCall;
use Illuminate\Mail\Events\MessageSending;
use App\Listeners\CheckDispositionListener;
use App\Listeners\PreventBlacklistedEmails;
use App\Listeners\SendCallInfoToOnScriptAI;
use App\Listeners\AddFundsAddedUserActivity;
use App\Listeners\AddMissedCallUserActivity;
use App\Listeners\AddUnsubscribeTokenToUser;
use App\Listeners\AddFundsTooLowUserActivity;
use App\Listeners\ChargeUserForCompletedCall;
use App\Listeners\OnboardingCompletedTrigger;
use App\Listeners\NotifyUserForLowFundsViaSMS;
use App\Listeners\AddCompletedCallUserActivity;
use App\Listeners\NotifyUserForLowFundsViaEmail;
use App\Listeners\InviteAgent as ListenersInviteAgent;
use App\Listeners\DispatchDispositionUpdateNotification;
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
        MessageSending::class => [
            // AddUnsubscribeTokenToUser::class,
            PreventBlacklistedEmails::class,
        ],
    
        Registered::class => [
            AddUnsubscribeTokenToUser::class,
            SendEmailVerificationNotification::class,
            SendWelcomeEmail::class,
        ],

        InviteAgent::class => [
            AddUnsubscribeTokenToUser::class,
            ListenersInviteAgent::class,
        ],

        InitiatedCallEvent::class => [
            SaveUserCall::class,

        ],

        RingingCallEvent::class => [
            // SaveUserCall::class,
        ],

        MissedCallEvent::class => [
            // ChargeUserForMissedCall::class,
            MakeUserOffline::class,
            AddMissedCallUserActivity::class,
        ],

        CompletedCallEvent::class => [
            ChargeUserForCompletedCall::class,
            UnlockClientForUser::class,
            AddCompletedCallUserActivity::class,
            SendCallInfoToOnScriptAI::class,
            // DispatchDispositionUpdateNotification::class,
            // CheckDispositionListener::class,
        ],

        FundsAdded::class => [
            SendFundsReceiptEmail::class,
            AddFundsAddedUserActivity::class
        ],

        FundsTooLow::class => [
            NotifyUserForLowFundsViaEmail::class,
            NotifyUserForLowFundsViaSMS::class,
            AddFundsTooLowUserActivity::class,
        ],

        CallStatusUpdated::class => [
            LogCallStatusChange::class,
            UpdateUserCallStatus::class,
        ],

        OnboardingCompleted::class => [
            OnboardingCompletedTrigger::class,
        ],

        CareerEvent::class => [
            CareerListener::class,
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
