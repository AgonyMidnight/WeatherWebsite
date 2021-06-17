<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'access_log';
    protected $fillable = [
        'ip',
        'device',
        'browser',
        'city',
        'created_at',
        'updated_at'
    ];
}
