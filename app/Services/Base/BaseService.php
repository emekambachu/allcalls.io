<?php

namespace App\Services\Base;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

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

    public static function tryCatchException($e, $identifier = null): \Illuminate\Http\JsonResponse
    {
        Log::error('SERVER: Line ' . $e->getLine() . ' of ' . $e->getFile() . ', ' . $e->getMessage() . ', Identifier: ' . ($identifier !== null && isset($identifier['identifier']) ? $identifier['identifier'] : 'None'));

        return response()->json([
            'success' => false,
            'server_error' => "Line ".$e->getLine()." of ".$e->getFile().", ".$e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function formatFormDateToDbCarbon($date): string
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d:H:i:s');
    }
}
