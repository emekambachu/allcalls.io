<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\User;
use App\Models\CallType;
use App\Services\Base\BaseService;
use App\Services\Calls\CallService;
use App\Services\Client\ClientService;
use App\Services\User\UserService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class WebCallsAPIController extends Controller
{
    protected ClientService $client;
    protected CallService $call;
    protected UserService $user;
    public function __construct(
        ClientService $client,
        UserService $user,
        CallService $call
    ){
        $this->client = $client;
        $this->user = $user;
        $this->call = $call;
    }

    protected array $supportedSortColumns = [
        'serial_number', 'id', 'user_id', 'call_taken', 'call_duration_in_seconds', 'hung_up_by',
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
        $sortColumn = $request->input('sort_column', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        if (in_array($sortColumn, $this->supportedSortColumns, true)) {
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
        $perPage = $request->per_page ?? 100;
        $calls = $query->paginate((int)$perPage);

        // For paginated data
        $calls->getCollection()->each(function ($call, $index){
            $call->serial_number = $index + 1;
            $call->user_email = $call->user ? $call->user->email : null;
            $call->agent_name = $call->user ? $call->user->first_name.' '.$call->user->last_name : '';
            return $call;
        });

        // For all data
        $allCalls = $allCalls->transform(function ($call, $index) {
            $call->serial_number = $index + 1;
            $call->user_email = $call->user ? $call->user->email : null;
            $call->agent_name = $call->user ? $call->user->first_name.' '.$call->user->last_name : '';
            $call->disposition = $call->client ? $call->client->status : null;
            $call->revenue = '$'.$call->amount_spent;
            $call->call_type = $call->call_type ? $call->call_type->type : null;
            return $call;
        });

        // Save collection in session for search
        Session::put('all_calls', [
            'calls' => $allCalls,
        ]);

        return [
            'calls' => $calls,
            'all_calls' => $allCalls, // Get a better solution for this
            'total' => $allCalls->count(),
            'total_revenue' => round((float) $allCalls->sum('amount_spent'), 2),
            'per_page' => $perPage,
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
                    $q->where('first_name', $names[0]);
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

    public function getAutocompleteOptions(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->call->searchAutocompleteForCalls($request);
            return response()->json($data);

        }catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }

    }
}
