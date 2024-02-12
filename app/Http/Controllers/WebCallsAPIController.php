<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\User;
use App\Models\CallType;
use App\Services\Base\BaseService;
use App\Services\Client\ClientService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebCallsAPIController extends Controller
{
    protected ClientService $client;
    protected UserService $user;
    public function __construct(
        ClientService $client,
        UserService $user
    ){
        $this->client = $client;
        $this->user = $user;
    }

    protected array $supportedSortColumns = [
        'id', 'user_id', 'call_taken', 'call_duration_in_seconds', 'hung_up_by',
        'amount_spent', 'recording_url', 'call_type_id', 'created_at', 'updated_at',
        'sid', 'unique_call_id', 'from', 'user_response_time', 'completed_at'
    ];

    protected array $specialFilters = [
        'user_email' => 'applyUserEmailFilter',
        'agent_name' => 'applyAgentNameFilter',
        'user_role' => 'applyUserRoleFilter',
        'vertical' => 'applyVerticalFilter',
        'disposition' => 'applyDispositionFilter',
    ];

    public function index(Request $request)
    {
        $query = Call::with('user.roles', 'callType', 'client');

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

        $allCalls = $query->get();
        $calls = $query->paginate(100);

        $calls->getCollection()->transform(function ($call){
            if (isset($call->user)) {
                $call->user_email = $call->user->email;
            } else {
                $call->user_email = null; // or some default value
            }
            return $call;
        });

        return [
            'calls' => $calls,
            'total' => $allCalls->count(),
            'total_revenue' => round((float) $allCalls->sum('amount_spent'), 2),
        ];
    }

    protected function applyUserEmailFilter($query, $filter): void
    {
        $user = User::where('email', $filter['value'])->first();
        if ($user) {
            $query->where('user_id', '=', $user->id);
        }
    }

    protected function applyAgentNameFilter($query, $filter): void
    {
        $names = explode(' ', $filter['value']);

        if(empty($names)) {
            return;
        }

        $userIds = $this->user->user()->select('id', 'first_name', 'last_name')
            ->where(function ($q) use ($names) {
                if(!empty($names[0])) {
                    $q->where('first_name', $names[0])
                        ->orWhere('last_name', $names[0]);
                }
                if(!empty($names[1])) {
                    $q->where('last_name', $names[1]);
                }
        })->pluck('id');

        $query->whereIn('user_id', $userIds);
    }

    protected function applyDispositionFilter($query, $filter): void
    {
        $clients = $this->client->client()->with('call')
            ->has('call')->select('id', 'call_id', 'status')
            ->where('status', $filter['value'])->pluck('call_id');

        $query->whereIn('id', $clients);
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
