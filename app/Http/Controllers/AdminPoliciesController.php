<?php

namespace App\Http\Controllers;

use App\Models\InternalAgentMyBusiness;
use Illuminate\Http\Request;

class AdminPoliciesController extends Controller
{
    public function destroy(InternalAgentMyBusiness $policy)
    {
        dd($policy);
    }
}
