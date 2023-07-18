<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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

Route::post('/api/twiml', function (Request $request) {
    // The incoming phone number is stored in the "From" field
    $caller = $request->input('From');

    // Prepare the message
    if ($caller) {
        $message = "The caller's phone number is " . $caller;
    } else {
        $message = 'No caller phone number found.';
    }

    // Manually construct the TwiML
    $twiml = '<?xml version="1.0" encoding="UTF-8"?>';
    $twiml .= '<Response><Say>' . $message . '</Say></Response>';

    return response($twiml, 200)->header('Content-Type', 'text/xml');
});
