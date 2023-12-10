<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebCallsAPIController extends Controller
{
    protected $supportedSortColumns = [
        'id', 'user_id', 'call_taken', 'call_duration_in_seconds', 'hung_up_by',
        'amount_spent', 'recording_url', 'call_type_id', 'created_at', 'updated_at',
        'sid', 'unique_call_id', 'from', 'user_response_time', 'completed_at'
    ];

    public function index(Request $request)
    {
        $query = Call::with('user.roles')->with('callType');

        // Apply sorting
        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'desc');
        if (in_array($sortColumn, $this->supportedSortColumns)) {
            $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sortColumn, $sortDirection);
        }
    
        // Retrieve filters
        $filters = $request->input('filters', []);
        foreach ($filters as $filter) {
            if(isset($filter['name'], $filter['value'], $filter['operator'])) {
                // Apply filter logic here
                // Example for a simple where clause
                // Check if the column exists in your model or handle it as needed
                $query->where($filter['name'], $filter['operator'], $filter['value']);
            }
        }

        Log::debug('Filters: ', [
            'filters' => $filters
        ]);
    
        // Get paginated result
        $calls = $query->paginate();
    
        return ['calls' => $calls];
    }
}
