<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginSmsCode extends Model
{
    use HasFactory;

    protected $table = 'user_login_sms_codes';
    protected $guarded = [];
}
