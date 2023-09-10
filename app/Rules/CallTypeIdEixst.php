<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CallTypeIdEixst implements ValidationRule
{
    private $table;
    private $column;

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
          // Get the keys from the value (array)
          $keys = array_keys($value);

          // Filter out null keys
          $filteredKeys = array_filter($keys, function ($key) {
              return !is_null($key);
          });
  
          // Check if any key exists in the specified table and column
          if(!DB::table($this->table)
          ->whereIn($this->column, $filteredKeys)
          ->exists()) {
                $fail('Some of call types not exist.');
          }
    }
}
