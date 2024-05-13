<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockersLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'lockerID',
        'status',
        'date_time',
    ];
}
