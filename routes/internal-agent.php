<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActiveUsersController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\ActiveUserChannelController;
use App\Http\Controllers\Admin\InternalAgentController;
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

Route::prefix('internal-agent')->group(function () {



    Route::middleware(['auth', 'verified', 'internal-agent'])->group(function () {
        //Internal Agents Routes
    });
});
