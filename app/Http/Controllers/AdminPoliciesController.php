<?php

namespace App\Http\Controllers;

use App\Models\InternalAgentMyBusiness;
use Illuminate\Http\Request;

class AdminPoliciesController extends Controller
{
    public function destroy($policyId)
    {
        $policy = InternalAgentMyBusiness::find($policyId);

        $policy->delete();

        return redirect()->back()->with('message', 'Policy deleted successfully.');
    }
}
