<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredInternalAgentController;
use \App\Http\Controllers\InternalAgent\RegistrationStepController;

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


Route::prefix('internal-agent')->group(function () {

    Route::get('register', [RegisteredInternalAgentController::class, 'create'])
    ->name('register');

    Route::post('register', [RegisteredInternalAgentController::class, 'store'])
    ->name('register');




    Route::middleware(['auth', 'verified', 'internal-agent'])->group(function () {
        //Internal Agents Routes
        Route::get('contract-steps', [RegistrationStepController::class, 'contractSteps'])
        ->name('contract.steps');
        Route::post('registration-steps', [RegistrationStepController::class, 'store'])
        ->name('registration.steps');
    });
});
