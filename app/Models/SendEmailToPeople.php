<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendEmailToPeople extends Model
{
    use HasFactory;
    protected $table = "send_email_to_peoples";
    protected $guarded = ['id'];
}
