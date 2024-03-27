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
use App\Http\Controllers\FundsController;
use App\Http\Controllers\IOSLogController;
use App\Http\Controllers\CareersController;
use App\Http\Controllers\PingAPIController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\AppEventsController;
use App\Http\Controllers\CallRejectedByAgent;
use App\Http\Controllers\TwilioSMSController;
use App\Http\Controllers\CallHungUpController;
use App\Http\Controllers\CallStatusController;
use App\Http\Controllers\ClientsAPIController;
use App\Http\Controllers\DevicesAPIController;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ActiveUsersController;
use App\Http\Controllers\OnlineUsersController;
use App\Http\Controllers\TextMessageController;
use App\Http\Controllers\TwilioTokenController;
use App\Http\Controllers\CallTypesAPIController;
use App\Http\Controllers\DispositionsController;
use App\Http\Controllers\IncomingCallController;
use App\Http\Controllers\LaravelTokenController;
use App\Http\Controllers\SendBirdUserController;
use App\Http\Controllers\CallRecordingController;
use App\Http\Controllers\RingyResponseController;
use App\Http\Controllers\AgentStatusAPIController;
use App\Http\Controllers\LiveCallClientController;
use App\Http\Controllers\DatalotResponseController;
use App\Http\Controllers\IOSVersionCheckController;
use App\Http\Controllers\ListFlexResponseController;
use App\Http\Controllers\NotificationsAPIController;
use App\Http\Controllers\OverseerResponseController;
use App\Http\Controllers\CallDeviceActionsController;
use App\Http\Controllers\AvailableAgentsAPIController;
use App\Http\Controllers\OnScriptDelayedAPIController;
use App\Http\Controllers\TwilioWebhookErrorController;
use App\Http\Controllers\CallUserResponseAPIController;
use App\Http\Controllers\CallTypesSelectedAPIController;
use App\Http\Controllers\TwilioConferenceCallController;
use App\Http\Controllers\TwilioIOSAccessTokenController;
use App\Http\Controllers\CustomBroadcastingAuthController;
use App\Http\Controllers\ZoomMeetingNotificationController;
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

Route::post('/save-card', [FundsController::class, 'saveCardDetails']);

Route::middleware(['auth:sanctum', 'notBanned'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum', 'notBanned'])->get('user/roles', [UserRoleController::class, 'getUserRoles']);

//middleware(['notBanned'])->

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // $device = Device::create([
    //     'user_id' => $user->id,
    //     'device_type' => $request->device_name,
    //     'fcm_token' => $request->fcm_token
    // ]);

    // Log::debug('devices-log:sign-in', $device->toArray());

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware(['auth:sanctum'])->delete('/sanctum/token', function (Request $request) {

    Log::debug('devices-log:sign-out', [
        'user' => $request->user()->toArray(),
    ]);

    $devices = Device::whereUserId($request->user()->id)->get();

    foreach ($devices as $device) {
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

Route::middleware(['auth:sanctum', 'notBanned'])->get('/device/token', [TwilioTokenController::class, 'show']);


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
Route::middleware(['auth:sanctum', 'notBanned'])->post('/userDeviceToken', [IncomingCallController::class, 'saveDeviceToken'])->name('userDeviceToken');

// Route::middleware('auth:sanctum')->patch('/active-users', [ActiveUsersController::class, 'update']);
// Route::patch('/active-users', [ActiveUsersController::class, 'update']);
Route::post('/active-users-pusher-webhook', [ActiveUsersPusherWebhookController::class, 'store']);

Route::get('/twilio-ios-access-token-guest', [TwilioIOSAccessTokenGuestController::class, 'show']);

Route::match(['get', 'post'], '/agent-status-price', [AgentStatusAPIController::class, 'show']);
Route::match(['get', 'post'], '/agent-status', [AgentStatusAPIController::class, 'showWithoutPrice']);
Route::match(['get', 'post'], '/call-center/disposition', [CallCenterDispositionAPIController::class, 'update']);
Route::match(['get', 'post'], '/listflex/api-mme-bpo', [ListFlexResponseController::class, 'store']);
Route::match(['get', 'post'], '/ringy', [RingyResponseController::class, 'store']);
Route::match(['get', 'post'], '/overseer', [OverseerResponseController::class, 'store']);
Route::match(['get', 'post'], '/med', [DatalotResponseController::class, 'store']);
Route::match(['get', 'post'], '/available-agents', [AvailableAgentsAPIController::class, 'show']);

//Route::middleware(['auth:sanctum', 'notBanned'])->post('/app-events', [AppEventsController::class, 'store']);

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

Route::get('/pusher-private-test', function () {
    ExampleTest::dispatch(User::whereEmail('john@example.com')->first());
});

Route::get('/custom-broadcasting-auth', [CustomBroadcastingAuthController::class, 'store']);
Route::post('/custom-broadcasting-auth', [CustomBroadcastingAuthController::class, 'store']);

// Sanctum auth and notBanned Middleware
Route::middleware(['auth:sanctum', 'notBanned'])->group(function () {
// Online Users
    Route::get('/online-users', [OnlineUsersController::class, 'index']);
    Route::post('/online-users', [OnlineUsersController::class, 'store']);
    Route::delete('/online-users/{callTypeId}', [OnlineUsersController::class, 'destroy']);
    Route::delete('/online-users', [OnlineUsersController::class, 'destroyOnLogout']);

    // Clients
    Route::get('/clients', [ClientsAPIController::class, 'index']);
    Route::patch('/clients/{client}', [ClientsAPIController::class, 'update']);

    // Call Client Info
    Route::post('/call-client-info', [LiveCallClientController::class, 'index']);

    // Call Types
    Route::get('/callTypes', [CallTypesAPIController::class, 'index']);
    Route::get('/call-types/selected', [CallTypesSelectedAPIController::class, 'index']);
    Route::post('/call-types/states/update', [CallTypesSelectedAPIController::class, 'updateUserStates']);

    // Calls
    Route::patch('/calls/{uniqueCallId}/user-response', [CallUserResponseAPIController::class, 'update']);

    // Devices
    Route::post('/devices', [DevicesAPIController::class, 'store']);
    Route::patch('/devices/{device}', [DevicesAPIController::class, 'update']);
    Route::delete('/devices/{device}', [DevicesAPIController::class, 'destroy']);

    // Notifications
    Route::get('/notifications', [NotificationsAPIController::class, 'index']);
    Route::patch('/notifications/{notificationId}/mark-as-read', [NotificationsAPIController::class, 'markNotificationAsRead']);
    Route::patch('/notifications/mark-all-as-read', [NotificationsAPIController::class, 'markAllNotificationsAsRead']);
    Route::delete('/notifications/clear-all', [NotificationsAPIController::class, 'destroyAll']);
    Route::delete('/notifications/{notificationId}', [NotificationsAPIController::class, 'destroy']);


    Route::post('/ios-logs', [IOSLogController::class, 'log']);

    Route::post('/calls/{uniqueCallId}/reject', [CallHungUpController::class, 'update']);

    // Send Bird
    Route::post('/sendbird-user/create', [SendBirdUserController::class, 'createSendBirdUser']);
    Route::get('/sendbird-user/check', [SendBirdUserController::class, 'checkSendBirdUser']);
    Route::delete('/sendbird-user/delete', [SendBirdUserController::class, 'deleteSendBirdUser']);

    Route::post('/profile/upload-image', [ProfileController::class, 'uploadProfilePicture']);
//    Route::post('/profile/update', [ProfileController::class, 'api_update']);

    Route::post('/web-api/careers', [CareersController::class, 'careers']);

    // Laravel Token Verification
    Route::get('/laravel-token-verification', [LaravelTokenController::class, 'validateToken']);

    // Twilio iOS Access Tokens
    Route::get('/twilio-ios-access-token', [TwilioIOSAccessTokenController::class, 'show']);
    Route::get('/twilio-ios-sandbox-access-token', [TwilioIOSSandboxAccessTokenController::class, 'show']);

    // iOS Version Check
    Route::get('/ios-version-check', [IOSVersionCheckController::class, 'checkVersion']);

    // Twilio Android Access Tokens
    Route::get('/twilio-android-access-token', [TwilioAndroidAccessTokenController::class, 'show']);
    Route::get('/twilio-android-access-token-guest', [TwilioAndroidAccessTokenGuestController::class, 'show']);

    // call device actions
    Route::post('/call-device-actions', [CallDeviceActionsController::class, 'store']);
    Route::post('/call-device-actions/unique', [CallDeviceActionsController::class, 'storeWithUniqueCallId']);

    Route::post('/app-events', [AppEventsController::class, 'store']);

    // Handle incoming calls and add them directly to the conference
    Route::post('/conference/convert', [TwilioConferenceCallController::class, 'convertToConference'])->name('conference.convert');
    Route::post('/conference/convert/withNumber', [TwilioConferenceCallController::class, 'convertToConferenceWithNewNumber'])->name('conference.convertWithThirdNumber');
    Route::post('/conference/convert/withUnique', [TwilioConferenceCallController::class, 'convertToConferenceWithUniqueCallId'])->name('conference.convertToConferenceWithUniqueCallId');    
});

Route::post('/sendbird-user/blahblahblah', static function (Request $request) {
    return response()->json([
        'message' => 'Yo Yo Yo successfully'
    ], 200);
});

Route::get('/test-call', static function () {
    return 'All good!';
});

Route::post('/conference/direct', [TwilioConferenceCallController::class, 'directToConference'])->name('conference.direct');
Route::post('/conference-status-callback', [TwilioConferenceCallController::class, 'handleConferenceStatusCallback'])->name('conference.statusCallback');
Route::post('/hangup-third-party', [TwilioConferenceCallController::class, 'hangUpThirdParty'])->name('conference.hangupThirdParty');

Route::post('/twilio/sms/receive', [TwilioSMSController::class, 'receiveSMS']);
Route::post('/commio/sms/send', [TextMessageController::class, 'sendMessage']);
Route::post('/commio/sms/receive', [TextMessageController::class, 'receiveMessage']);

Route::post('/twilio-webhook-error', [TwilioWebhookErrorController::class, 'store']);

Route::post('/send/batch/sms/', [ZoomMeetingNotificationController::class, 'sendBatchTextMessages']);

Route::get('/onscript/delay', [OnScriptDelayedAPIController::class, 'delayDispatch']);
