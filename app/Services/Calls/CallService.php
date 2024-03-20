<?php

namespace App\Services\Calls;

use App\Models\Call;
use App\Models\CallType;
use App\Models\User;
use App\Services\Client\ClientService;
use App\Services\User\UserService;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CallService
{
    protected ClientService $client;
    protected UserService $user;
    public function __construct(
        ClientService $client,
        UserService $user,
    ) {
        $this->client = $client;
        $this->user = $user;
    }

    public function call(): Call
    {
        return new Call();
    }

    protected array $supportedSortColumns = [
        'serial_number', 'id', 'user_id', 'call_taken', 'call_duration_in_seconds', 'cost',
        'publisher_name', 'publisher_id', 'hung_up_by',
        'amount_spent', 'recording_url', 'call_type_id', 'created_at', 'updated_at',
        'sid', 'unique_call_id', 'from', 'user_response_time', 'ringing_duration', 'completed_at'
    ];

    protected array $specialFilters = [
        'user_email' => 'applyUserEmailFilter',
        'agent_name' => 'applyAgentNameFilter',
        'user_role' => 'applyUserRoleFilter',
        'vertical' => 'applyVerticalFilter',
        'disposition' => 'applyDispositionFilter',
    ];

    protected array $columnsWithJoins = [
        'vertical',
        'disposition',
        'agent_name',
    ];

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

        if (empty($names)) {
            return;
        }

        $userIds = $this->user->user()->select('id', 'first_name', 'last_name')
            ->where(function ($q) use ($names) {
                if (!empty($names[0])) {
                    $q->where('first_name', $names[0]);
                }
                if (!empty($names[1])) {
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

    /**
     * @throws \JsonException
     */
    public function exportCalls($export, $allCalls): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $input = json_decode($export, true, 512, JSON_THROW_ON_ERROR);
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=calls.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        return response()->stream(function () use ($input, $allCalls) {
            $file = fopen('php://output', 'wb');
            // Write headers
            $header = array_map(static function ($column) {
                return $column['label'];
            }, $input['columns']);

            fputcsv($file, $header);
            // Write rows
            foreach ($allCalls as $call) {
                foreach ($call as $value) {
                    $rowData = [];

                    foreach ($input['columns'] as $column) {
                        try {
                            if (isset($value->{$column['name']})) {
                                $rowData[] = $value->{$column['name']};
                            } else {
                                $rowData[] = null;
                            }
                        } catch (\Exception $e) {
                            Log::error("Export Error " . $column['name'] . " in call object: " . $e->getMessage());
                        }
                    }
                    fputcsv($file, $rowData);
                }
            }
            fclose($file);
        }, 200, $headers);
    }

    public function searchAutocompleteForCalls($request): \Illuminate\Database\Eloquent\Collection|array
    {
        $keyword = $request->keyword;
        $calls = $this->call()->with('user')->whereHas('user', function (Builder $query) use ($keyword) {
            $query->where('first_name', 'like', '%' . $keyword . '%')
                ->orWhere('last_name', 'like', '%' . $keyword . '%');
        })->get();

        if ($calls->count() > 0) {
            $calls = $calls->transform(function ($call, $index) {
                $call->user_email = $call->user ? $call->user->email : null;
                $call->agent_name = $call->user ? $call->user->first_name . ' ' . $call->user->last_name : '';
                return $call;
            });

            return [
                'success' => true,
                'calls' => $calls,
            ];
        }

        return [
            'success' => false,
            'results' => [],
        ];
    }

    public function getAllCallsWithDynamicFilterAndSorting($request): array
    {
        $query = Call::with('user.roles', 'callType', 'client', 'getBusiness');

        // ringing_duration is a custom attribute, therefore it's not available in the database table
        // Had to use raw queries to get it to work
        if ($request->input('sort_column') === 'ringing_duration') {
            $query->select('*', DB::raw('IFNULL(TIMESTAMPDIFF(SECOND, created_at, user_response_time), 20) AS ringing_duration'));
        }

        // Apply sorting for columns with or without joins
        if (in_array($request->input('sort_column'), $this->columnsWithJoins, true)) {
            $sortColumn = $request->input('sort_column', 'calls.created_at');
        } else {
            $sortColumn = $request->input('sort_column', 'created_at');
        }
        $sortDirection = $request->input('sort_direction', 'desc');

        if (in_array($sortColumn, $this->supportedSortColumns, true)) {
            $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sortColumn, $sortDirection);

            // apply joins to columns with relationships
        } else if ($sortColumn === 'disposition') {
            $query->join('clients', 'clients.call_id', '=', 'calls.id')
                ->orderBy('clients.status', $sortDirection)
                ->select('calls.*', 'clients.call_id AS clients_call_id', 'clients.status');

            // apply joins to columns with relationships
        } else if ($sortColumn === 'agent_name') {
            $query->join('users', 'users.id', '=', 'calls.user_id')
                ->orderBy('users.first_name', $sortDirection)
                ->select('calls.*', 'users.id AS users_id', 'users.first_name');

            // apply joins to columns with relationships
        } else if ($sortColumn === 'vertical') {
            $query->join('call_types', 'call_types.id', '=', 'calls.call_type_id')
                ->orderBy('call_types.type', $sortDirection)
                ->select('calls.*', 'call_types.id AS call_types_id', 'call_types.type');
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

        Log::debug('CallService:DatesBeforeTimezoneConversion:', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        // Apply date filters
        if ($startDate && $endDate) {

            $userTimeZone = Auth::user() && !empty(Auth::user()->timezone) ? Auth::user()->timezone : 'America/New_York';

            Log::debug('CallService:UserTimeZone:', [
                'userTimeZone' => $userTimeZone,
            ]);

            // Convert the input dates to Y-m-d format without any timezone conversion
//            $startDate = Carbon::createFromFormat('m/d/Y', $startDate, $userTimeZone)
//                ->setTimezone('UTC')
//                ->format('Y-m-d');
//            $endDate = Carbon::createFromFormat('m/d/Y', $endDate, $userTimeZone)
//                ->setTimezone('UTC')
//                ->format('Y-m-d');

            // Convert the input dates from m/d/Y format to Y-m-d format, considering the user's timezone,
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate, $userTimeZone)
                ->shiftTimezone('UTC')
                ->toDateString();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate, $userTimeZone)
                ->shiftTimezone('UTC')
                ->toDateString();

            Log::debug('CallService:DatesAfterTimezoneConversion:', [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

            if (in_array($request->input('sort_column'), $this->columnsWithJoins, true)) {
                Log::debug('CallService:DatesAppliedToJoinQuery:');

                $query->whereDate('calls.created_at', '>=', $startDate)
                    ->whereDate('calls.created_at', '<=', $endDate);
            } else {
                Log::debug('CallService:DatesApplied:');

                $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            }
        }

        Log::debug('Filters: ', [
            'filters' => $filters
        ]);

        $allCalls = $query->get();
        $perPage = $request->per_page ?? 100;
        $calls = $query->paginate((int)$perPage);

        //Group and Sort calls by agents and publishers
        $sortAgentsColumn = $request->input('sort_agents_column');
        $sortPublishersColumn = $request->input('sort_publishers_column');

        $getCallsGroupedByUser = $this->getCallsGroupedByUserId($allCalls);
        if ($sortAgentsColumn) {
            // Convert from array to collection, sort by column, and convert back to array
            $callsGroupedByUser = collect($getCallsGroupedByUser)
                ->sort(function ($a, $b) use ($sortAgentsColumn, $sortDirection) {
                    return $sortDirection === 'asc' ? $a[$sortAgentsColumn] <=> $b[$sortAgentsColumn] : $b[$sortAgentsColumn] <=> $a[$sortAgentsColumn];
                })->values()->all();
        } else {
            $callsGroupedByUser = $getCallsGroupedByUser;
        }

        $getCallsGroupedByPublisherName = $this->getCallsGroupedByPublisherName($allCalls);
        if ($sortPublishersColumn) {
            // Convert from array to collection, sort by column, and convert back to array
            $callsGroupedByPublisherName = collect($getCallsGroupedByPublisherName)
                ->sort(function ($a, $b) use ($sortPublishersColumn, $sortDirection) {
                    return $sortDirection === 'asc' ? $a[$sortPublishersColumn] <=> $b[$sortPublishersColumn] : $b[$sortPublishersColumn] <=> $a[$sortPublishersColumn];
                })->values()->all();
        } else {
            $callsGroupedByPublisherName = $getCallsGroupedByPublisherName;
        }

//        $getCallsGroupedByUser = $this->getCallsGroupedByUserId($allCalls);
//        if($sortAgentsColumn){
//            // convert from array to collection, sort by name and convert back to array
//            $callsGroupedByUser = collect($getCallsGroupedByUser)
//                ->sortBy($sortAgentsColumn, SORT_REGULAR, $sortDirection)->values()->all();
//        }else{
//            $callsGroupedByUser = $getCallsGroupedByUser;
//        }
//
//        $getCallsGroupedByPublisherName = $this->getCallsGroupedByPublisherName($allCalls);
//        if($sortPublishersColumn){
//            // convert from array to collection, sort by name and convert back to array
//            $callsGroupedByPublisherName = collect($getCallsGroupedByPublisherName)
//                ->sortBy($sortPublishersColumn, SORT_REGULAR, $sortDirection)->values()->all();
//        }else{
//            $callsGroupedByPublisherName = $getCallsGroupedByPublisherName;
//        }

        // For paginated data
        $calls->getCollection()->each(function ($call, $index) {
            $call->serial_number = $index + 1;
            $call->user_email = $call->user ? $call->user->email : null;
            $call->agent_name = $call->user ? $call->user->first_name . ' ' . $call->user->last_name : '';
            return $call;
        });

        // For all data
        $allCalls = $allCalls->transform(function ($call, $index) {
            $call->serial_number = $index + 1;
            $call->user_email = $call->user ? $call->user->email : null;
            $call->agent_name = $call->user ? $call->user->first_name . ' ' . $call->user->last_name : '';
            $call->disposition = $call->client ? $call->client->status : null;
            $call->revenue = '$' . $call->amount_spent;
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
            'calls_grouped_by_user' => $callsGroupedByUser,
            'calls_grouped_by_publisher_name' => $callsGroupedByPublisherName,
            'total_revenue' => round((float) $allCalls->sum('amount_spent'), 2),
            'per_page' => $perPage,
        ];
    }

    public function getCallsGroupedByUserId($allCalls)
    {
        return $allCalls->groupBy('user_id')->map(function ($calls) {
            $user = $calls->first()->user; // Assuming each id group has a user

            $totalCalls = $calls->count();
            $paidCalls = $calls->where('amount_spent', '>', 0)->count();
            $totalRevenue = $calls->sum('amount_spent');
            $totalCallLength = $calls->sum('call_duration_in_seconds');
            $averageCallLength = $totalCalls > 0 ? $totalCallLength / $totalCalls : 0;
            $totalPolicies = $calls->where('policy_id', '!=', null)->count();

            // Manual filtering for pending and declined policies
            $totalPendingPolicies = $calls->filter(function ($call) {
                return $call->getBusiness && in_array($call->getBusiness->status, ['Submitted', 'Approved']);
            })->count();

            $totalDeclinedPolicies = $calls->filter(function ($call) {
                return $call->getBusiness && in_array($call->getBusiness->status, ['Declined', 'Cancelled', 'Withdrawn']);
            })->count();

            $totalSiPolicies = $calls->filter(function ($call) {
                return $call->getBusiness && $call->client && $call->client->status === 'Sale - Simplified Issue';
            })->count();

            $totalGiPolicies = $calls->filter(function ($call) {
                return $call->getBusiness && $call->client && $call->client->status === 'Sale - Guaranteed Issue';
            })->count();

            return [
                'userId' => $user->id,
                'agentName' => $user->first_name . ' ' . $user->last_name,
                'agentEmail' => $user->email,
                'totalCalls' => $totalCalls,
                'paidCalls' => $paidCalls,
                'revenueEarned' => $totalRevenue,
                'revenuePerCall' => $totalCalls > 0 ? $totalRevenue / $totalCalls : 0,
                'totalCallLength' => $totalCallLength,
                'averageCallLength' => $averageCallLength,
                'totalPolicies' => $totalPolicies,
                'totalPendingPolicies' => $totalPendingPolicies,
                'totalDeclinedPolicies' => $totalDeclinedPolicies,
                'totalSiPolicies' => $totalSiPolicies,
                'totalGiPolicies' => $totalGiPolicies,
            ];
        });
    }

    public function getCallsGroupedByPublisherName($allCalls)
    {
        return $allCalls->groupBy('publisher_name')->map(function ($calls) {
            $userIds = $calls->pluck('user_id')->toArray(); // Get ids that belong to the same publisher
            $totalCalls = $calls->count();
            $paidCalls = $calls->where('amount_spent', '>', 0)->count();
            $totalRevenue = $calls->sum('amount_spent');
            $totalCallLength = $calls->sum('call_duration_in_seconds');
            $averageCallLength = $totalCalls > 0 ? $totalCallLength / $totalCalls : 0;
            $publisherName = $calls->first()->publisher_name;

            return [
                'user_ids' => $userIds,
                'publisher_name' => $publisherName,
                'totalCalls' => $totalCalls,
                'paidCalls' => $paidCalls,
                'revenueEarned' => $totalRevenue,
                'revenuePerCall' => $totalCalls > 0 ? $totalRevenue / $totalCalls : 0,
                'totalCallLength' => $totalCallLength,
                'averageCallLength' => $averageCallLength,
            ];
        });
    }
}
