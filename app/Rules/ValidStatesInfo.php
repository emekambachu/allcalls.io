<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStatesInfo implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        json_decode($value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $fail("The $attribute must be a valid JSON string.");
        }
    }                                                                                                                                                                                                                                                                                                 
}
