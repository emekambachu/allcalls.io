<?php

namespace App\Http\Controllers;

use App\Services\Base\BaseService;
use App\Services\Calls\CallService;
use Illuminate\Http\Request;

class WebCallsAPIController extends Controller
{
    protected CallService $call;
    public function __construct(
        CallService $call
    ){
        $this->call = $call;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->call->getAllCallsWithDynamicFilterAndSorting($request);
            return response()->json($data);

        }catch (\Exception $e){
            return BaseService::tryCatchException($e);
        }
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
