<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebCallsAPIController extends Controller
{
    // Add all columns to the supported sort and filter arrays
    protected $supportedSortColumns = [
        'id', 'user_id', 'call_taken', 'call_duration_in_seconds', 'hung_up_by',
        'amount_spent', 'recording_url', 'call_type_id', 'created_at', 'updated_at',
        'sid', 'unique_call_id', 'from', 'user_response_time', 'completed_at'
    ];
    protected $supportedFilters = [
        'id', 'user_id', 'call_taken', 'call_duration_in_seconds', 'hung_up_by',
        'amount_spent', 'recording_url', 'call_type_id', 'created_at', 'updated_at',
        'sid', 'unique_call_id', 'from', 'user_response_time', 'completed_at'
    ];

    public function index(Request $request)
    {
        $query = Call::with('user.role')->with('callType');

        // Apply sorting
        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'desc');
        if (in_array($sortColumn, $this->supportedSortColumns)) {
            $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Apply filtering
        foreach ($this->supportedFilters as $filterKey) {
            if ($request->has($filterKey)) {
                $query->where($filterKey, $request->input($filterKey));
            }
        }

        $calls = $query->paginate();

        return ['calls' => $calls];
    }
}
