<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveUsersController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\ActiveUserChannelController;
use App\Http\Controllers\Admin\OnlineAgentsController;

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

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    //Admin Routes
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'show'])->name('admin.dashboard');



    // Customer
    Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customer.index');
    Route::post('/customer/{id}', [CustomerController::class, 'update'])->name('admin.customer.update');
    Route::get('/customer/detail/{id}', [CustomerController::class, 'show'])->name('admin.customer.detail');
    Route::get('/customer/transactions/{id}', [CustomerController::class, 'getTransaction']);

    Route::get('/customer/calls/{id}', [CustomerController::class, 'getUserCall']);

    Route::get('/customer/banned/{id}',[CustomerController::class,'banUser'])->name('admin.ban.customer');
    Route::get('/customer/activities/{id}', [CustomerController::class, 'getActivity']);

    //Agents
    Route::get('/agents', [\App\Http\Controllers\Admin\InternalAgentController::class, 'index'])->name('admin.agent.index');

    Route::post('/agent', [\App\Http\Controllers\Admin\InternalAgentController::class, 'store'])->name('admin.agent.store');

    Route::post('/agent/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'update'])->name('admin.agent.update');

    Route::get('/agent/detail/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'show'])->name('admin.agent.detail');

    Route::get('/agent/transactions/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'getTransaction']);

    Route::get('/agent/calls/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'getCall']);

    Route::get('/agent/activities/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'getActivity']);

    // Route::get('/active-users', [ActiveUsersController::class, 'index'])->name('admin.active-users.index');
    // Route::get('/active-users/join', [ActiveUserChannelController::class, 'join']);

    Route::get('/online-agents', [OnlineAgentsController::class, 'index'])->name('admin.online-agents.index');
});
