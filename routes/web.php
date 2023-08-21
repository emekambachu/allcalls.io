<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\BidsController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\AutoPayController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefaultCardController;
use App\Http\Controllers\TwilioTokenController;
use App\Http\Controllers\IncomingCallController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\FundsWithCardController;
use App\Http\Controllers\UsageActivityController;
use App\Http\Controllers\Auth\RegisteredUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/registration-steps', [RegisteredUserController::class, 'steps'])->name('registration.steps');
    Route::post('/store-registration-steps', [RegisteredUserController::class, 'storeSteps'])->name('store.registration.steps');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    //Admin Routes
});

Route::middleware(['auth', 'verified', 'user', 'registration-step-check'])->group(function () {
    //User Routes
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::delete('/transactions/{transaction}', [TransactionsController::class, 'destroy'])->name('transactions.destroy');
    Route::patch('/bids', [BidsController::class, 'update'])->name('bids.update');
    Route::get('/billing/funds', [FundsController::class, 'index'])->name('billing.funds.index');
    Route::post('/billing/funds', [FundsController::class, 'store'])->name('billing.funds.store');
    Route::post('/billing/funds-with-card', [FundsWithCardController::class, 'store'])->name('billing.funds-with-card.store');
    Route::get('/billing/cards', [CardsController::class, 'index'])->name('billing.cards.index');
    Route::post('/billing/cards', [CardsController::class, 'store'])->name('billing.cards.store');
    Route::patch('/billing/cards/default/{card}', [DefaultCardController::class, 'update'])->name('billing.cards.default.update');
    Route::delete('/billing/cards/{card}', [CardsController::class, 'destroy'])->name('billing.cards.delete');
    Route::get('/billing/autopay', [AutoPayController::class, 'show'])->name('billing.autopay.index');
    Route::post('/billing/autopay', [AutoPayController::class, 'store'])->name('billing.autopay.store');
    Route::get('/usage-activities', [UsageActivityController::class, 'index'])->name('activities.index');
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::patch('/clients/{client}', [ClientsController::class, 'update'])->name('clients.update');
    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/view', [ProfileController::class, 'view'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/twiml-example', function() {
    return view('twiml-example');
});

Route::get('/device/token', [TwilioTokenController::class, 'show'])->middleware('auth');

Route::get('/device/incoming', function() {
    return view('incoming');
})->middleware('auth');

// Route::get('/clients/support', [SupportController::class, 'clientIndex'])->name('support.index');

// Route::get('/call/pushNotification', [IncomingCallController::class, 'sendPushNotification']);

// Route::get('channel-test', function() {
//     UserOnline::dispatch();
// })->middleware('auth');

// Route::post('pusher-webhook-example', function() {
//     Log::debug('pusher webhook fired');
//     Log::debug(request()->all());
// });

// Route::get('allcalls-pusher-client', function() {
//     return Inertia::render('PusherTest');
// })->middleware('auth');
