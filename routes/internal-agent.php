<?php

use App\Http\Controllers\InternalAgent\DocusignController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\InternalAgent\RegistrationStepController;
use Inertia\Inertia;
use App\Http\Controllers\InternalAgent\MyAgencyController;
use App\Http\Controllers\InternalAgent\MyBusinessController;
use App\Http\Controllers\InternalAgent\TrainingController;

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

    Route::get('/training', [TrainingController::class, 'index'])->name('training.index')->middleware(['IsBasicTraining']);


    // Route::get('/training-locked', function () {
    //     if(auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->basic_training) {
    //         return redirect()->route('training');
    //     }
    //     return Inertia::render('Auth/InternalAgentLocked', []);
    // })->name('training.locked');
    

    Route::get('/agent-agency', [MyAgencyController::class, 'index'])->name('internal-agent.agent-agency.index')->middleware(['registration-step-check', 'IsBasicTraining']);
    // get Agent invites
    Route::get('/get-agent-invites', [MyAgencyController::class, 'GetAgentInvites'])->name('internal-agent.get-agent-invites.GetAgentInvites')->middleware(['registration-step-check', 'IsBasicTraining']);

    Route::get('/my-agent', [MyAgencyController::class, 'myAgent'])->name('internal-agent.my-agent')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::post('/agent-invites', [MyAgencyController::class, 'store'])->name('admin.agent-invites.store')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::delete('/agent-invites/{id}', [MyAgencyController::class, 'destroy'])->name('internal-agent.agent-invites.destroy')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::get('/reinvite-agent/{id}', [MyAgencyController::class, 'reInvite'])->name('internal-agent.agent.reinvite')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::get('/agent/tree/{id}', [MyAgencyController::class, 'getAgentTree'])->name('agent.tree')->middleware(['registration-step-check', 'IsBasicTraining']);

    Route::get('/my-business', [MyBusinessController::class, 'index'])->name('agent.my.business')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::post('/business-by-label', [MyBusinessController::class, 'businessByLabel'])->name('business.bylabel')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::post('/get-client-by-name', [MyBusinessController::class, 'getClientByName'])->name('getclient.byname')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::post('/my-business', [MyBusinessController::class, 'store'])->name('agent.my.business.store')->middleware(['registration-step-check', 'IsBasicTraining']);
    Route::get('/download-pdf/{fileName}', [TrainingController::class, 'downloadPdf'])->name('download-pdf')->middleware(['registration-step-check', 'IsBasicTraining']);;
});

Route::middleware(['auth', 'verified', 'internal-agent'])->group(function () {
    Route::get('docusign', [DocusignController::class, 'index'])->name('internal.agent.docusign');
    Route::get('connect-docusign', [DocusignController::class, 'connectDocusign'])->name('internal.agent.connect.docusign');
    Route::get('docusign/callback', [DocusignController::class, 'callback'])->name('internal.agent.docusign.callback');
    Route::get('sign-document/{key?}', [DocusignController::class, 'signDocument'])->name('internal.agent.docusign.sign');

    Route::get('/training-complete/{id}', [TrainingController::class, 'trainingComplete'])->name('training-complete');

    Route::get('/locked', function () {
        if (auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->basic_training && auth()->user()->is_locked) {

            return redirect()->route('training.index');
        }
        if (auth()->user()->legacy_key !== 1) {
            return redirect()->route('contract.steps');
        }
        return Inertia::render('Auth/InternalAgentLocked', []);
    })->name('internal.agent.locked');
});
