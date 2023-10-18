<?php

use App\Http\Controllers\InternalAgent\DocusignController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\InternalAgent\RegistrationStepController;
use Inertia\Inertia;
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


Route::prefix('internal-agent')->middleware(['auth', 'verified', 'internal-agent'])->group(function () {
    //Internal Agents Routes
    Route::get('contract-steps', [RegistrationStepController::class, 'contractSteps'])
        ->name('contract.steps');
    Route::post('registration-steps', [RegistrationStepController::class, 'store'])
        ->name('registration.steps');
    Route::post('registration-signature', [RegistrationStepController::class, 'registrationSignature'])
        ->name('registration.signature');

    Route::get('/locked', function () {
        if(auth()->user()->roles->contains('name', 'internal-agent') && !auth()->user()->is_locked) {
            return redirect()->route('dashboard');
        }
        return Inertia::render('Auth/InternalAgentLocked', []);
    })->name('internal.agent.locked');
});

Route::middleware(['auth', 'verified', 'internal-agent'])->group(function () {
    Route::get('docusign', [DocusignController::class, 'index'])->name('internal.agent.docusign');
    Route::get('connect-docusign', [DocusignController::class, 'connectDocusign'])->name('internal.agent.connect.docusign');
    Route::get('docusign/callback', [DocusignController::class, 'callback'])->name('internal.agent.docusign.callback');
    Route::get('sign-document/{key?}', [DocusignController::class, 'signDocument'])->name('internal.agent.docusign.sign');
});
