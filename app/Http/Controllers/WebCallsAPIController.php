<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;

class WebCallsAPIController extends Controller
{
    protected $supportedSortColumns = [
        'id', 'user_id', 'call_taken', 'call_duration_in_seconds', 'hung_up_by',
        'amount_spent', 'recording_url', 'call_type_id', 'created_at', 'updated_at',
        'sid', 'unique_call_id', 'from', 'user_response_time', 'completed_at'
    ];

    protected $specialFilters = [
        'user_email' => 'applyUserEmailFilter',
        'user_role' => 'applyUserRoleFilter',
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

        $operatorMap = [
            'is' => '=',
            'is greater than' => '>',
            'is less than' => '<',
            'is greater than or equal to' => '>=',
            'is less than or equal to' => '<=',
        ];

        // Retrieve filters
        $filters = $request->input('filters', []);

        foreach ($filters as $filter) {
            if (isset($filter['name'], $filter['value'], $filter['operator'])) {
                if (array_key_exists($filter['name'], $this->specialFilters)) {
                    $methodName = $this->specialFilters[$filter['name']];
                    $this->$methodName($query, $filter);
                } else {
                    $filter['operator'] = $operatorMap[$filter['operator']] ?? $filter['operator'];
                    $query->where($filter['name'], $filter['operator'], $filter['value']);
                }
            }
        }

        Log::debug('Filters: ', [
            'filters' => $filters
        ]);

        // Get paginated result
        $calls = $query->paginate();

        $calls->getCollection()->transform(function ($call) {
            if (isset($call->user)) {
                $call->user_email = $call->user->email;
            } else {
                $call->user_email = null; // or some default value
            }
            return $call;
        });


        return ['calls' => $calls];
    }

    protected function applyUserEmailFilter($query, $filter)
    {
        $user = User::where('email', $filter['value'])->first();
        if ($user) {
            $query->where('user_id', '=', $user->id);
        }
    }

    protected function applyUserRoleFilter($query, $filter)
    {
        // Get user IDs associated with the specified role
        $userIds = User::whereHas('role', function ($query) {
            $query->where('name', 'internal-agent');
        })->pluck('id');

        if ($filter['value'] === 'internal-agent') {
            $query->whereIn('user_id', $userIds);
        } else {
            $query->whereNotIn('user_id', $userIds);
        }
    }
}
