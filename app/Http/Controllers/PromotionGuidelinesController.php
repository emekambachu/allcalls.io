<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class PromotionGuidelinesController extends Controller
{
    public function show(Request $request)
    {
        $level = $request->user()->getAgentLevel;

        if (!$level) {
            return abort(401);
        }


        dd($level->name);

        // Initialize $showChart as 'unknown' by default
        $showChart = "unknown";

        // Check for Internal levels
        if (in_array($level->name, ["Internal 1", "Internal 2", "Internal 3", "Internal 4", "Internal 5"])) {
            $showChart = "small";
        }
        // Check for AC levels
        elseif (preg_match('/^AC (\d+)$/', $level->name, $matches)) {
            $acLevel = intval($matches[1]);
            if ($acLevel >= 1 && $acLevel <= 11) {
                $showChart = "large";
            }
        }

        dd($showChart);

        return Inertia::render('PromotionGuidelines/Show');
    }
}
