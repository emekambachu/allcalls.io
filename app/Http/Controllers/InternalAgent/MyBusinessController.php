<?php

namespace App\Http\Controllers\InternalAgen;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentMyBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyBusinessController extends Controller
{
    public function index()
    {
        $businesses = InternalAgentMyBusiness::get();
    }

    public function store(Request $request)
    {
        dd($request->all());
        $validate = Validator::make($_GET, [
            'insuranceCompany' => 'required',
            'productName' => 'required',
            'applicationDate' => 'required',
            'coverageAmount' => 'nullable',
            'coverageLength' => 'required|in:N/A,3 Years,5 Years,5 Years w/ 5 Year Guaranty,5 Years w/ ROP,7 Years,10 Years,15 Years,15 Years w/ ROP,20 Years,20 Years w/ 5 Year Guaranty,20 Years w/ ROP,25 Years,25 Years w/ ROP,30 Years,30 Years w/ 5 Year Guaranty,30 Years w/ ROP',
            'premiumAmount' => 'nullable',
            'premiumFrequency' => 'nullable',
            'annualPremiumVolume' => 'nullable',
            'doYouHaveAnEquisWritingNumberWithThisCarrier' => 'nullable',
            'carrierWritingNumber' => 'nullable',
            'wasThisAppFromALead' => 'nullable',
            'sourceOfTheLead' => 'nullable',
            'wasThisAppointmentVirtualOrFaceToFace' => 'nullable',
            'annualTargetPremium' => 'nullable',
            'annualPlannedPremium' => 'nullable',
            'annualExcessPremium' => 'nullable',
            'initialInvestmentAmount' => 'nullable',
            'didAnotherAgentReferThisApplicationToYou' => 'nullable',
            'referringAgentEFNumber' => 'nullable',
            'isThisAnSDIC' => 'nullable',
            'willThereBeARecurringPremium' => 'nullable',
            'firstName' => 'required',
            'MI' => 'nullable',
            'lastName' => 'required',
            'dateOfBirth' => 'required',
            'gender' => 'required | in:Male,Female,Other',
            'streetLine1' => 'required',
            'streetLine2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'phoneNumber' => 'required',
            'email' => 'required|email',
        ]);
        
        

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors(),
            ], 400);
        }
    }
}
