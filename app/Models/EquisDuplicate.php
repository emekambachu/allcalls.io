<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquisDuplicate extends Model
{
    use HasFactory;
    protected $table = "equis_duplicate";
    // protected $guarded = ['id'];
    protected $fillable = ['email', 'first_name', 'last_name', 'upline_code'];

}
