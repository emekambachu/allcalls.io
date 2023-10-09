<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\BidsController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\AutoPayController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TakeCallsController;
use App\Http\Controllers\StripeTestController;
use App\Http\Controllers\DefaultCardController;
use App\Http\Controllers\StripeFundsController;
use App\Http\Controllers\TwilioTokenController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\FundsWithCardController;
use App\Http\Controllers\UsageActivityController;
use App\Http\Controllers\WebAPIClientsController;
use App\Http\Controllers\CallClientInfoController;
use App\Http\Controllers\AdditionalFilesController;
use App\Http\Controllers\ActiveUserChannelController;
use App\Http\Controllers\TwilioDeviceTokenController;
use App\Http\Controllers\Admin\OnlineAgentsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TakeCallsOnlineUsersController;

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
})->name('home');

Route::get('/pdf', [\App\Http\Controllers\InternalAgent\RegistrationStepController::class, 'pdf'])->name('dashboard');

require __DIR__ . '/auth.php';
require 'admin.php';

require __DIR__ . '/auth.php';
require 'internal-agent.php';

Route::get('/transactions', [TransactionsController::class, 'index'])->middleware(['auth', 'verified', 'notBanned'])->name('transactions.index');
Route::delete('/transactions/{transaction}', [TransactionsController::class, 'destroy'])->middleware(['auth', 'verified', 'notBanned'])->name('transactions.destroy');

Route::middleware(['auth', 'verified', 'notBanned'])->group(function () {
    Route::get('/registration-steps', [RegisteredUserController::class, 'steps'])->name('registration.steps');
    Route::post('/store-registration-steps', [RegisteredUserController::class, 'storeSteps'])->name('store.registration.steps');
});

Route::middleware(['auth', 'verified', 'registration-step-check', 'notBanned'])->group(function () {
    //User Routes
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::delete('/transactions/{transaction}', [TransactionsController::class, 'destroy'])->name('transactions.destroy');
    Route::patch('/bids', [BidsController::class, 'update'])->name('bids.update');

    Route::get('/billing/funds', [FundsController::class, 'index'])->name('billing.funds.index');
    Route::post('/billing/funds', [FundsController::class, 'store'])->name('billing.funds.store');

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
    Route::get('/calls', [CallController::class, 'index'])->name('calls.index');
    Route::get('/take-calls', [TakeCallsController::class, 'show'])->name('take-calls.show');
    Route::post('/take-calls/online-users', [TakeCallsOnlineUsersController::class, 'store'])->name('take-calls.online-users.store');
    Route::delete('/take-calls/online-users/{callTypeId}', [TakeCallsOnlineUsersController::class, 'destroy'])->name('take-calls.online-users.destroy');

    Route::get('/additional-files', [AdditionalFilesController::class, 'index'])->name('additional-files.index');
    Route::post('/additional-files', [AdditionalFilesController::class, 'store'])->name('additional-files.store');
    Route::get('/additional-files/{additionalFile}', [AdditionalFilesController::class, 'show'])->name('additional-files.show');
    Route::delete('/additional-files/{additionalFile}', [AdditionalFilesController::class, 'destroy'])->name('additional-files.destroy');

    Route::get('/twilio-device-token', [TwilioDeviceTokenController::class, 'show']);
    Route::get('/call-client-info', [CallClientInfoController::class, 'show']);
});

Route::middleware(['auth', 'notBanned'])->group(function () {
    Route::get('/profile/view', [ProfileController::class, 'view'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::patch('/bids', [BidsController::class, 'update'])->middleware(['auth', 'verified', 'notBanned'])->name('bids.update');


Route::get('/billing/cards', [CardsController::class, 'index'])->middleware(['auth', 'verified'])->name('billing.cards.index');
Route::post('/billing/cards', [CardsController::class, 'store'])->middleware(['auth', 'verified'])->name('billing.cards.store');
Route::patch('/billing/cards/default/{card}', [DefaultCardController::class, 'update'])->middleware(['auth', 'verified'])->name('billing.cards.default.update');
Route::delete('/billing/cards/{card}', [CardsController::class, 'destroy'])->middleware(['auth', 'verified'])->name('billing.cards.delete');
Route::get('/billing/autopay', [AutoPayController::class, 'show'])->middleware(['auth', 'verified'])->name('billing.autopay.index');
Route::post('/billing/autopay', [AutoPayController::class, 'storeWithStripe'])->middleware(['auth', 'verified'])->name('billing.autopay.store');

Route::get('/usage-activities', [UsageActivityController::class, 'index'])->middleware(['auth', 'verified'])->name('activities.index');

Route::get('/device/token', [TwilioTokenController::class, 'show'])->middleware('auth');

Route::get('/device/incoming', function () {
    return view('incoming');
})->middleware('auth');

Route::get('/clients', [ClientsController::class, 'index'])->middleware(['auth', 'verified', 'registration-step-check'])->name('clients.index');
Route::patch('/clients/{client}', [ClientsController::class, 'update'])->middleware(['auth', 'verified', 'registration-step-check'])->name('clients.update');
Route::patch('/web-api/clients/{client}', [WebAPIClientsController::class, 'update'])->middleware(['auth', 'verified', 'registration-step-check'])->name('clients.web-api.update');

Route::get('/support', [SupportController::class, 'index'])->name('support.index');

Route::get('/stripe-test', [StripeTestController::class, 'show']);
Route::get('/stripe-test-redirect', [StripeTestController::class, 'store']);

Route::get('/vince', function () {
    return redirect('/');
});

Route::get('/ryan', function () {
    return redirect('/');
});