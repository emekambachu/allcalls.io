<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\User;
use App\Models\CallType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        'vertical' => 'applyVerticalFilter',
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

        // Extract the start and end date from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Apply date filters
        if ($startDate && $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
        }


        Log::debug('Filters: ', [
            'filters' => $filters
        ]);

        // Get paginated result
        $calls = $query->paginate(100);

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
        $userIds = User::whereHas('roles', fn ($query) => $query->where('name', 'internal-agent'))
            ->pluck('id');

        if ($filter['value'] === 'internal-agent') {
            $query->whereIn('user_id', $userIds);
        } else {
            $query->whereNotIn('user_id', $userIds);
        }
    }

    protected function applyVerticalFilter($query, $filter)
    {
        $callType = CallType::where('type', $filter['value'])->first();

        if (!$callType) {
            return;
        }

        $query->where('call_type_id', $callType->id);
    }
}
