<?php

use App\Models\User;
use App\Models\ActiveUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallStatusController;
use App\Http\Controllers\ClientsAPIController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ActiveUsersController;
use App\Http\Controllers\OnlineUsersController;
use App\Http\Controllers\TwilioTokenController;
use App\Http\Controllers\CallTypesAPIController;
use App\Http\Controllers\IncomingCallController;
use App\Http\Controllers\CallRecordingController;
use App\Http\Controllers\LiveCallClientController;
use App\Http\Controllers\CallTypesSelectedAPIController;
use App\Http\Controllers\TwilioIOSAccessTokenController;
use App\Http\Controllers\ActiveUsersPusherWebhookController;
use App\Http\Controllers\TwilioIOSAccessTokenGuestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken($request->device_name)->plainTextToken;
});

Route::post('/twiml', function (Request $request) {
    // The incoming phone number is stored in the "From" field
    $caller = $request->input('From');

    $numberToDial = '+15736523170';

    // Manually construct the TwiML
    $twiml = '<?xml version="1.0" encoding="UTF-8"?>';
    // $twiml .= '<Response><Dial answerOnBridge="true" callerId="' . $numberToDial . '">' . '<Client>+15736523170</Client>' . '</Dial></Response>';
    $twiml .= '<Response><Dial answerOnBridge="true"><Client callerId="+15736523170">5736523170</Client></Dial></Response>';

    return response($twiml, 200)->header('Content-Type', 'text/xml');
});

Route::get('/twiml', function (Request $request) {
    // The incoming phone number is stored in the "From" field
    $caller = $request->input('From');

    $numberToDial = '+15736523170';

    // Manually construct the TwiML
    $twiml = '<?xml version="1.0" encoding="UTF-8"?>';
    // $twiml .= '<Response><Dial answerOnBridge="true" callerId="' . $numberToDial . '">' . '<Client>+15736523170</Client>' . '</Dial></Response>';
    $twiml .= '<Response><Dial answerOnBridge="true"><Client callerId="+15736523170">5736523170</Client></Dial></Response>';

    return response($twiml, 200)->header('Content-Type', 'text/xml');
});

Route::middleware('auth:sanctum')->get('/device/token', [TwilioTokenController::class, 'show']);


Route::get('/call/incoming', [IncomingCallController::class, 'respond'])->middleware('twilio');
// Route::get('/call/incoming', function() {
    // $numberToDial = '+15736523170';

    // Manually construct the TwiML
    /* $twiml = '<?xml version="1.0" encoding="UTF-8"?>'; */
    // $twiml .= '<Response><Dial answerOnBridge="true" callerId="' . $numberToDial . '">' . '<Client>+15736523170</Client>' . '</Dial></Response>';
    // $twiml .= '<Response><Dial answerOnBridge="true"><Client callerId="+15736523170">testneo</Client></Dial></Response>';

    // return response($twiml, 200)->header('Content-Type', 'text/xml');
// })->middleware('twilio');

Route::get('/handle-call-status', [CallStatusController::class, 'update']);
Route::get('/handle-call-recording', [CallRecordingController::class, 'store']);

Route::post('/call/pushNotification', [IncomingCallController::class, 'sendPushNotification'])->name('call.pushNotification');
Route::middleware('auth:sanctum')->post('/userDeviceToken', [IncomingCallController::class, 'saveDeviceToken'])->name('userDeviceToken');

Route::middleware('auth:sanctum')->post('/online-users', [OnlineUsersController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/online-users/{callTypeId}', [OnlineUsersController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/clients', [ClientsAPIController::class, 'index']);

Route::middleware('auth:sanctum')->post('/call-client-info', [LiveCallClientController::class, 'index']);

Route::middleware('auth:sanctum')->get('/callTypes', [CallTypesAPIController::class, 'index']);
Route::middleware('auth:sanctum')->get('/call-types/selected', [CallTypesSelectedAPIController::class, 'index']);

// Route::middleware('auth:sanctum')->patch('/active-users', [ActiveUsersController::class, 'update']);
// Route::patch('/active-users', [ActiveUsersController::class, 'update']);
Route::post('/active-users-pusher-webhook', [ActiveUsersPusherWebhookController::class, 'store']);

Route::get('/twilio-ios-access-token-guest', [TwilioIOSAccessTokenGuestController::class, 'show']);
Route::middleware('auth:sanctum')->get('/twilio-ios-access-token', [TwilioIOSAccessTokenController::class, 'show']);
