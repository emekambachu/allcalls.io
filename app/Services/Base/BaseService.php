<?php

namespace App\Services\Base;
use Carbon\Carbon;

class BaseService
{
    public static function randomChar(int $length, string $randChar, string $staticChar = null): string
    {
        $charactersLength = strlen($randChar);
        $randomString = $staticChar ?? '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $randChar[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function debugSql($query){
        $rawQuery = str_replace('?', "'?'", $query->toSql());
        logger()->info(vsprintf(str_replace('?', '%s', $rawQuery), $query->getBindings()));
    }

    public static function tryCatchException($e): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error_message' => "Line ".$e->getLine()." of ".$e->getFile().", ".$e->getMessage(),
        ], 500);
    }

    public static function formatFormDateToDbCarbon($date): string
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d:H:i:s');
    }
}
