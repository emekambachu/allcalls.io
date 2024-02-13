<?php

namespace App\Services\Calls;

use App\Models\Call;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Log;

class CallService
{
    public function call(): Call
    {
        return new Call();
    }

    /**
     * @throws \JsonException
     */
    public function exportCalls($export, $allCalls): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $input = json_decode($export, true, 512, JSON_THROW_ON_ERROR);
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=pre-diagnostic-user-answers.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

//        dd($allCalls);

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
//                        Log::debug('Export Loop: ', [
//                            'column' => $column,
//                            'call' => $value->{$column['name']},
//                        ]);

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

}
