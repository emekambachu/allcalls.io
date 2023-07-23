<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\TwilioTokenController;
use App\Http\Controllers\IncomingCallController;

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

Route::get('/device/token', [TwilioTokenController::class, 'show'])->middleware('auth:sanctum');


Route::get('/call/incoming', [IncomingCallController::class, 'respond']);