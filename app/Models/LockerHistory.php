<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockerHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_of_lockers',
        'added_at',
    ];
}
