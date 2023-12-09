<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveUsersController;
use App\Http\Controllers\Admin\CallsController;
use App\Http\Controllers\WebCallsAPIController;
use App\Http\Controllers\AgentInvitesController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InternalAgentLevel;
use App\Http\Controllers\ActiveUserChannelController;
use App\Http\Controllers\Admin\AgentMyBusinessController;
use App\Http\Controllers\Admin\OnlineAgentsController;
use App\Http\Controllers\AdminNotificationsController;
use App\Http\Controllers\Admin\InternalAgentController;
use App\Http\Controllers\AdminUserActivitiesController;
use App\Http\Controllers\InternalAgentExportController;
use App\Http\Controllers\Admin\AvailableNumberController;
use App\Http\Controllers\AdminAvaialbleNumbersController;
use App\Http\Controllers\Admin\LegalQuestionPdfController;
use App\Http\Controllers\AvailableNumberReleaseController;

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

    Route::get('/customer/clients/{id}', [CustomerController::class, 'getCustomerClients']);

    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('admin.customer.destroy');

    //Agents
    Route::get('/agents', [InternalAgentController::class, 'index'])->name('admin.agent.index');
    
    Route::get('/internal-agent/tree/{id}', [InternalAgentController::class, 'getAgentTree'])->name('admin.get.agent.tree');

    Route::post('/agent', [InternalAgentController::class, 'store'])->name('admin.agent.store');

    Route::post('/agent/{id}', [InternalAgentController::class, 'update'])->name('admin.agent.update');

    Route::get('/agent/detail/{id}', [InternalAgentController::class, 'show'])->name('admin.agent.detail');

    Route::get('/agent/transactions/{id}', [InternalAgentController::class, 'getTransaction']);

    Route::get('/agent/calls/{id}', [InternalAgentController::class, 'getCall']);

    Route::get('/agent/activities/{id}', [InternalAgentController::class, 'getActivity']);

    Route::get('/agent/clients/{id}', [InternalAgentController::class, 'getAgentClients']);

    Route::get('/download/agent/contract/pdf/{id}', [InternalAgentController::class, 'downloadAgentContractPdf'])->name('admin.agent.contract.pdf');

    Route::get('/download/legal-question/pdf/{id}/{userId}/{serialNo}', [InternalAgentController::class, 'getQuestionPdf'])->name('admin.agent.legal.question.pdf');

    Route::get('/download/signature-authorization/{id}', [InternalAgentController::class, 'signatureAuthorizationPdf'])->name('admin.agent.signature.authorization.pdf');

    Route::get('/approved-internal-agent/{id}', [InternalAgentController::class, 'internalAgentApproved'])->name('admin.approved.internal.agent');

    Route::post('/progress-internal-agent', [InternalAgentController::class, 'internalAgentProgress'])->name('admin.internal.agent.update.progress');

    /// My business 
    Route::get('/my-business', [AgentMyBusinessController::class, 'index'])->name('admin.my-business.index');

    // Available Number
    Route::get('/available-numbers',[AvailableNumberController::class,'index'])->name('admin.available-number.index');
    Route::post('/available-number/store',[AvailableNumberController::class,'store']);
    Route::post('/available-number/release-all', [AvailableNumberReleaseController::class, 'store']);
    Route::post('/available-number/{id}', [AvailableNumberController::class, 'update']);

    // Route::get('/active-users', [ActiveUsersController::class, 'index'])->name('admin.active-users.index');
    // Route::get('/active-users/join', [ActiveUserChannelController::class, 'join']);

    Route::get('/online-agents', [OnlineAgentsController::class, 'index'])->name('admin.online-agents.index');
    Route::delete('/online-agents/{userId}', [OnlineAgentsController::class, 'destroy'])->name('admin.online-agents.destroy');

    Route::get('/agent-invites', [AgentInvitesController::class, 'index'])->name('admin.agent-invites.index');
    Route::post('/agent-invites', [AgentInvitesController::class, 'store'])->name('admin.agent-invites.store');
    Route::delete('/agent-invites/{id}', [AgentInvitesController::class, 'destroy'])->name('admin.agent-invites.destroy');
    Route::get('/reinvite-agent/{id}', [AgentInvitesController::class, 'reInvite'])->name('admin.agent.reinvite');

    //Calls
    Route::get('/calls', [CallsController::class, 'index'])->name('admin.calls.index');
    Route::get('/calls/new', [CallsController::class, 'indexNew'])->name('admin.calls.index-new');

    Route::get('/notifications', [AdminNotificationsController::class, 'create'])->name('admin.notifications.create');

    Route::get('/user-activities', [AdminUserActivitiesController::class, 'index'])->name('admin.user-activities.index');

    // agent levels
    Route::get('/internal-agent-levels', [InternalAgentLevel::class, 'index'])->name('admin.internal.agent.level.index');
    Route::post('/internal-agent-level/store', [InternalAgentLevel::class, 'store'])->name('admin.internal.agent.level.store');
    Route::post('/internal-agent-level/update', [InternalAgentLevel::class, 'update'])->name('admin.internal.agent.level.update');
    Route::delete('/internal-agent-level/destroy/{id}', [InternalAgentLevel::class, 'destroy'])->name('admin.internal.agent.level.destroy');

    Route::delete('/user-activities/clear-all', [AdminUserActivitiesController::class, 'clearAll'])->name('admin.user-activities.clearAll');

   
    Route::get('/web-api/calls', [WebCallsAPIController::class, 'index']);
});
