<?php

namespace App\Http\Controllers\Admin;

use App\Services\Base\BaseService;
use App\Services\Calls\CallService;
use Exception;
use Carbon\Carbon;
use App\Models\Call;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CallsController extends Controller
{
    protected CallService $call;
    public function __construct(CallService $call){
        $this->call = $call;
    }

    public function index(Request $request)
    {
        $orderColumn = "created_at";
        $orderBy = "DESC";

        if ((isset($request->sortColumn) && $request->sortColumn != '') || (isset($request->sortOrder) && $request->sortOrder != '')) {
            $orderColumn = $request->sortColumn;
            $orderBy = $request->sortOrder;
        }

        $calls = Call::with('user.roles', 'getClient', 'callType')
            ->with('client')
            ->where(function ($query) use ($request) {
                if (isset($request->from) && $request->from != '' && isset($request->to) && $request->to != '') {
                    $fromDate = Carbon::parse($request->from)->startOfDay();
                    $toDate = Carbon::parse($request->to)->endOfDay();
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            })
            ->where(function ($query) use ($request) {
                if (isset($request->status) && $request->status != '') {
                    if ($request->status == 'paid') {
                        $query->where('call_duration_in_seconds', '>', 60);
                    } else if ($request->status == 'unpaid') {
                        $query->where('call_duration_in_seconds', '<=', 60);
                    }
                }
            })
            ->orderBy($orderColumn, $orderBy)
            ->paginate(100);
        return Inertia::render('Admin/Calls/Index', [
            'requestData' => $request->all(),
            'calls' => $calls
        ]);
    }

    public function indexNew(Request $request): \Inertia\Response
    {
        $allCalls = Call::with('user')->get();
        $callsGroupedByUser = $allCalls->groupBy('user_id')->map(function ($calls, $userId) {
            $user = $calls->first()->user; // Assuming each call has a 'user' relation loaded
            $totalCalls = $calls->count();
            $paidCalls = $calls->where('amount_spent', '>', 0)->count();
            $totalRevenue = $calls->sum('amount_spent');
            $totalCallLength = $calls->sum('call_duration_in_seconds');
            $averageCallLength = $totalCalls > 0 ? $totalCallLength / $totalCalls : 0;

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
            ];
        });

        $callsGroupedByPublisherName = $allCalls->groupBy('publisher_name')->map(function ($calls) {
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

        return Inertia::render('Admin/Calls/IndexBeta', [
            'requestData' => $request->all(),
            'totalCalls' => Call::count(),
            'totalRevenue' => round((float) Call::sum('amount_spent'), 2),
            'callsGroupedByUser' => $callsGroupedByUser,
            'callsGroupedByPublisherName' => $callsGroupedByPublisherName,
        ]);
    }

    public function disposition(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $call = Call::where('id', $request->call_id)->first();
            if($call->client) {
                $call->client->status = $request->disposition;
                $call->client->save();
            }
            else {
                return response()->json([
                    'success' => false,
                    'errors' => 'Client not found for this call.',
                ], 400);
            }
            $call->save();
            return response()->json([
                'success' => true,
                'call' => $call,
                'message' => 'Disposition updated successfully.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'errors' => $th->getMessage(),
            ], 400);
        }
    }
    /**
     * @throws \JsonException
     */

    public function exportCalls($export): \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        try {
            $response = $this->call->exportCalls($export, Session::get('all_calls'));

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
        return $response;
    }
}
