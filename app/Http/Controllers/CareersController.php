<?php

namespace App\Http\Controllers;

use App\Events\CareerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CareersController extends Controller
{
    public function careers(Request $request)
    {
        $carreerValidation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email',
            'phone' => 'required',
            'life_insurance' => 'required',
        ]);
        if ($carreerValidation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $carreerValidation->errors(),
            ], 400);
        };
        // dd($request->all());

        $applicantData = $request->only('first_name', 'last_name', 'email', 'phone', 'life_insurance');
        try {
            event(new CareerEvent($applicantData));
            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'errors' => $th->getMessage(),
            ], 400);
        }
    }
}
