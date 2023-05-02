<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\AutoPayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CardsAPIController;
use App\Http\Controllers\TransactionsController;

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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/billing/funds', [FundsController::class, 'index'])->middleware(['auth', 'verified'])->name('billing.funds.index');
Route::post('/billing/funds', [FundsController::class, 'store'])->middleware(['auth', 'verified'])->name('billing.funds.store');
Route::get('/billing/cards', [CardsController::class, 'index'])->middleware(['auth', 'verified'])->name('billing.cards.index');
Route::post('/billing/cards', [CardsController::class, 'store'])->middleware(['auth', 'verified'])->name('billing.cards.store');
Route::delete('/billing/cards/{card}', [CardsController::class, 'destroy'])->middleware(['auth', 'verified'])->name('billing.cards.delete');
Route::get('/billing/autopay', [AutoPayController::class, 'show'])->middleware(['auth', 'verified'])->name('billing.autopay.index');

require __DIR__.'/auth.php';
