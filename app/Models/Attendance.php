<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['api_key', 'userId',  'deviceId', 'device_ip', 'punchTime', 'punchMode', 'punchType', 'status'];
}
