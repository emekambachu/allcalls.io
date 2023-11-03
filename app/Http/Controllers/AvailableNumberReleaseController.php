<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableNumber;

class AvailableNumberReleaseController extends Controller
{
    public function store()
    {
        AvailableNumber::query()->update([
            'user_id' => null,
            'from' => null,
            'call_type_id' => null,
        ]);

        return redirect()->back();
    }
}
