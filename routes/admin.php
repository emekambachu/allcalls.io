<?php

use Illuminate\Support\Facades\Route;


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
    Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customer.index');
    Route::post('/customer/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('admin.customer.update');
    Route::get('/customers/detail/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('admin.customer.detail');

    Route::get('/customer/transactions/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'getTransaction']);

    Route::get('/customer/calls/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'getUserCall']);

    Route::get('/customer/activities/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'getActivity']);


    //Agents
    Route::get('/agents', [\App\Http\Controllers\Admin\InternalAgentController::class, 'index'])->name('admin.customer.index');

    Route::post('/agent', [\App\Http\Controllers\Admin\InternalAgentController::class, 'store'])->name('admin.customer.store');

    Route::post('/agent/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'update'])->name('admin.customer.update');

    Route::get('/agent/detail/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'show'])->name('admin.customer.detail');

    Route::get('/agent/transactions/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'getTransaction']);

    Route::get('/agent/calls/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'getCall']);

    Route::get('/agent/activities/{id}', [\App\Http\Controllers\Admin\InternalAgentController::class, 'getActivity']);


});
