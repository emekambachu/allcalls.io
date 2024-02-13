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

    Route::get('/training-lower-balance', [TrainingController::class, 'trainingLowerBalance'])
    ->name('training.lower.balance')->middleware(['registration-step-check']);

    Route::get('/training', [TrainingController::class, 'index'])->name('training.index')->middleware(['registration-step-check', 'IsBasicTraining']);

    Route::get('/agent-agency', [MyAgencyController::class, 'index'])->name('internal-agent.agent-agency.index')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    // get Agent invites
    Route::get('/get-agent-invites', [MyAgencyController::class, 'GetAgentInvites'])->name('internal-agent.get-agent-invites')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);

    Route::get('/my-agent', [MyAgencyController::class, 'myAgent'])->name('internal-agent.my-agent')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::post('/agent-invites', [MyAgencyController::class, 'store'])->name('admin.agent-invites.store')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::delete('/agent-invites/{id}', [MyAgencyController::class, 'destroy'])->name('internal-agent.agent-invites.destroy')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::get('/reinvite-agent/{id}', [MyAgencyController::class, 'reInvite'])->name('internal-agent.agent.reinvite')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::get('/agent/tree/{id}', [MyAgencyController::class, 'getAgentTree'])->name('agent.tree')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);

    Route::get('/my-business', [MyBusinessController::class, 'index'])->name('agent.my.business')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::post('/business-by-label', [MyBusinessController::class, 'businessByLabel'])->name('business.bylabel')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::post('/get-client-by-name', [MyBusinessController::class, 'getClientByName'])->name('getclient.byname')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::post('/my-business', [MyBusinessController::class, 'store'])->name('agent.my.business.store')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
    Route::get('/download-pdf/{fileName}', [TrainingController::class, 'downloadPdf'])->name('download-pdf')->middleware(['registration-step-check', 'agentOnTraining', 'IsBasicTraining']);
});

Route::middleware(['auth', 'verified', 'internal-agent'])->group(function () {
    Route::get('docusign', [DocusignController::class, 'index'])->name('internal.agent.docusign');
    Route::get('connect-docusign', [DocusignController::class, 'connectDocusign'])->name('internal.agent.connect.docusign');
    Route::get('docusign/callback', [DocusignController::class, 'callback'])->name('internal.agent.docusign.callback');
    Route::get('sign-document/{key?}', [DocusignController::class, 'signDocument'])->name('internal.agent.docusign.sign');
    Route::get('/training-complete/{id}', [TrainingController::class, 'trainingComplete'])->name('training-complete');

    Route::get('/basic-training', function () {
        if (auth()->user()->roles->contains('name', 'internal-agent') && auth()->user()->basic_training) {
            return redirect()->route('dashboard');
        }
        return Inertia::render('InternalAgent/Traning/BasicTraining', []);
    })->name('internal.agent.locked');
});
