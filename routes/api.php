<?php

use Pusher\Pusher;
use App\Models\User;
use App\Models\Device;
use App\Models\ActiveUser;
use App\Events\ExampleTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PingAPIController;
use App\Http\Controllers\CallStatusController;
use App\Http\Controllers\ClientsAPIController;
use App\Http\Controllers\DevicesAPIController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ActiveUsersController;
use App\Http\Controllers\OnlineUsersController;
use App\Http\Controllers\TwilioTokenController;
use App\Http\Controllers\CallTypesAPIController;
use App\Http\Controllers\DispositionsController;
use App\Http\Controllers\IncomingCallController;
use App\Http\Controllers\LaravelTokenController;
use App\Http\Controllers\CallRecordingController;
use App\Http\Controllers\RingyResponseController;
use App\Http\Controllers\AgentStatusAPIController;
use App\Http\Controllers\LiveCallClientController;
use App\Http\Controllers\IOSVersionCheckController;
use App\Http\Controllers\ListFlexResponseController;
use App\Http\Controllers\OverseerResponseController;
use App\Http\Controllers\AvailableAgentsAPIController;
use App\Http\Controllers\CallUserResponseAPIController;
use App\Http\Controllers\CallTypesSelectedAPIController;
use App\Http\Controllers\TwilioIOSAccessTokenController;
use App\Http\Controllers\CustomBroadcastingAuthController;
use App\Http\Controllers\ActiveUsersPusherWebhookController;
use App\Http\Controllers\CallCenterDispositionAPIController;
use App\Http\Controllers\TwilioAndroidAccessTokenController;
use App\Http\Controllers\TwilioIOSAccessTokenGuestController;
use App\Http\Controllers\TwilioIOSSandboxAccessTokenController;
use App\Http\Controllers\TwilioAndroidAccessTokenGuestController;
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

    $device = Device::create([
        'user_id' => $user->id,
        'device_type' => $request->device_name,
        'fcm_token' => $request->fcm_token
    ]);

    Log::debug('devices-log:sign-in', $device->toArray());
 
    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->delete('/sanctum/token', function(Request $request) {

    Log::debug('devices-log:sign-out', [
        'user' => $request->user()->toArray(),
    ]);

    $devices = Device::whereUserId($request->user()->id)->get();

    foreach($devices as $device) {
        Log::debug('devices-log:deleting', [
            'device' => $device->toArray()
        ]);
        $device->delete();
    }

    return ['message' => 'done'];
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
//     $twiml .= '<Response><Dial answerOnBridge="true"><Client callerId="+15736523170">alice</Client></Dial></Response>';
    
//     Log::debug($twiml);
    
//     return response($twiml, 200)->header('Content-Type', 'text/xml');
//  })->middleware('twilio');

Route::get('/handle-call-status', [CallStatusController::class, 'update']);
Route::get('/handle-call-recording', [CallRecordingController::class, 'store']);

Route::post('/call/pushNotification', [IncomingCallController::class, 'sendPushNotification'])->name('call.pushNotification');
Route::middleware('auth:sanctum')->post('/userDeviceToken', [IncomingCallController::class, 'saveDeviceToken'])->name('userDeviceToken');

Route::middleware('auth:sanctum')->get('/online-users', [OnlineUsersController::class, 'index']);
Route::middleware('auth:sanctum')->post('/online-users', [OnlineUsersController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/online-users/{callTypeId}', [OnlineUsersController::class, 'destroy']);
Route::middleware('auth:sanctum')->delete('/online-users', [OnlineUsersController::class, 'destroyOnLogout']);

Route::middleware('auth:sanctum')->get('/clients', [ClientsAPIController::class, 'index']);
Route::middleware('auth:sanctum')->patch('/clients/{client}', [ClientsAPIController::class, 'update']);

Route::middleware('auth:sanctum')->post('/call-client-info', [LiveCallClientController::class, 'index']);

Route::middleware('auth:sanctum')->get('/callTypes', [CallTypesAPIController::class, 'index']);
Route::middleware('auth:sanctum')->get('/call-types/selected', [CallTypesSelectedAPIController::class, 'index']);

// Route::middleware('auth:sanctum')->patch('/active-users', [ActiveUsersController::class, 'update']);
// Route::patch('/active-users', [ActiveUsersController::class, 'update']);
Route::post('/active-users-pusher-webhook', [ActiveUsersPusherWebhookController::class, 'store']);

Route::get('/twilio-ios-access-token-guest', [TwilioIOSAccessTokenGuestController::class, 'show']);
Route::middleware('auth:sanctum')->get('/laravel-token-verification', [LaravelTokenController::class, 'validateToken']);
Route::middleware('auth:sanctum')->get('/twilio-ios-access-token', [TwilioIOSAccessTokenController::class, 'show']);
Route::middleware('auth:sanctum')->get('/twilio-ios-sandbox-access-token', [TwilioIOSSandboxAccessTokenController::class, 'show']);
Route::middleware('auth:sanctum')->get('/ios-version-check', [IOSVersionCheckController::class, 'checkVersion']);
Route::middleware('auth:sanctum')->get('/twilio-android-access-token', [TwilioAndroidAccessTokenController::class, 'show']);
Route::middleware('auth:sanctum')->get('/twilio-android-access-token-guest', [TwilioAndroidAccessTokenGuestController::class, 'show']);

Route::match(['get', 'post'], '/agent-status-price', [AgentStatusAPIController::class, 'show']);
Route::match(['get', 'post'], '/agent-status', [AgentStatusAPIController::class, 'showWithoutPrice']);
Route::match(['get', 'post'], '/call-center/disposition', [CallCenterDispositionAPIController::class, 'update']);
Route::match(['get', 'post'], '/listflex/api-mme-bpo', [ListFlexResponseController::class, 'store']);
Route::match(['get', 'post'], '/ringy', [RingyResponseController::class, 'store']);
Route::match(['get', 'post'], '/overseer', [OverseerResponseController::class, 'store']);
Route::match(['get', 'post'], '/available-agents', [AvailableAgentsAPIController::class, 'show']);

Route::middleware('auth:sanctum')->post('/app-events', [AppEventsController::class, 'store']);

Route::match(['get', 'post'], '/ping', [PingAPIController::class, 'show']);
Route::match(['get', 'post'], '/disposition', [DispositionsController::class, 'update']);


Route::post('/custom-pusher-auth', function (Request $request) {
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID')
    );

    $channelName = $request->request->get('channel_name');
    $socketId = $request->request->get('socket_id');

    return $pusher->socket_auth($channelName, $socketId);
});

Route::get('/pusher-private-test', function(){
    ExampleTest::dispatch(User::whereEmail('john@example.com')->first());
});

Route::get('/custom-broadcasting-auth', [CustomBroadcastingAuthController::class, 'store']);
Route::post('/custom-broadcasting-auth', [CustomBroadcastingAuthController::class, 'store']);

Route::middleware('auth:sanctum')->patch('/calls/{uniqueCallId}/user-response', [CallUserResponseAPIController::class, 'update']);

Route::middleware('auth:sanctum')->post('/devices', [DevicesAPIController::class, 'store']);
Route::middleware('auth:sanctum')->patch('/devices/{device}', [DevicesAPIController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/devices/{device}', [DevicesAPIController::class, 'destroy']);