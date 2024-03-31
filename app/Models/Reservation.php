<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guard = 'Reservation';

    protected $table = 'reservations';

    protected $fillable = ['user_id', 'reservation_date', 'status', ];
   
}
