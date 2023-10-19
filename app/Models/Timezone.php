<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    use HasFactory;
    protected $table = "timezone";
    protected $fillable = [
      'name', 'offset', 'diff_from_gtm'
    ];
}
