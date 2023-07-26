<?php

use App\Events\UserOnline;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\AutoPayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardsAPIController;
use App\Http\Controllers\TwilioTokenController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsageActivityController;

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/transactions', [TransactionsController::class, 'index'])->middleware(['auth', 'verified'])->name('transactions.index');
Route::delete('/transactions/{transaction}', [TransactionsController::class, 'destroy'])->middleware(['auth', 'verified'])->name('transactions.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile/view', [ProfileController::class, 'view'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::post('/profile/upload', [ProfileController::class, 'uploadProfilePicture'])->name('profile.uploadProfilePicture');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/billing/funds', [FundsController::class, 'index'])->middleware(['auth', 'verified'])->name('billing.funds.index');
Route::post('/billing/funds', [FundsController::class, 'store'])->middleware(['auth', 'verified'])->name('billing.funds.store');
Route::get('/billing/cards', [CardsController::class, 'index'])->middleware(['auth', 'verified'])->name('billing.cards.index');
Route::post('/billing/cards', [CardsController::class, 'store'])->middleware(['auth', 'verified'])->name('billing.cards.store');
Route::delete('/billing/cards/{card}', [CardsController::class, 'destroy'])->middleware(['auth', 'verified'])->name('billing.cards.delete');
Route::get('/billing/autopay', [AutoPayController::class, 'show'])->middleware(['auth', 'verified'])->name('billing.autopay.index');
Route::post('/billing/autopay', [AutoPayController::class, 'store'])->middleware(['auth', 'verified'])->name('billing.autopay.store');

Route::get('/usage-activities', [UsageActivityController::class, 'index'])->middleware(['auth', 'verified'])->name('activities.index');
// Route::post('/billing/autopay', [AutoPayController::class, 'store'])->middleware(['auth', 'verified'])->name('billing.autopay.store');

Route::get('/twiml-example', function() {
    return view('twiml-example');
});

Route::get('/device/token', [TwilioTokenController::class, 'show'])->middleware('auth');
Route::get('/device/incoming', function() {
    return view('incoming');
})->middleware('auth');

Route::get('channel-test', function() {
    UserOnline::dispatch();
})->middleware('auth');

Route::post('pusher-webhook-example', function() {
    Log::debug('pusher webhook fired');
    Log::debug(request()->all());
});

Route::get('allcalls-pusher-client', function() {
    return view('allcalls-pusher-client');
});

require __DIR__.'/auth.php';
