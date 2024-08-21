<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class email extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'email',
        'mailer',
        'host',
        'port_no',
        'username',
        'password',
        'encryption',
        'sent',
        'next_in',
        'landed_in_spam'
    ];
}
