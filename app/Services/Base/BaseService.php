<?php

namespace App\Services\Base;
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
}
