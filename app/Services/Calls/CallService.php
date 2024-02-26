<?php

namespace App\Services\Calls;

use App\Models\Call;
use App\Services\Base\BaseService;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Log;

class CallService
{
    public function call(): Call
    {
        return new Call();
    }

    public function getCallsWithRelationsAndFilters(){

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
                ->orWhere('last_name', 'like', '%'. $keyword. '%');
        })->get();

        if($calls->count() > 0) {
            $calls = $calls->transform(function ($call, $index) {
                $call->user_email = $call->user ? $call->user->email : null;
                $call->agent_name = $call->user ? $call->user->first_name.' '.$call->user->last_name : '';
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

}
