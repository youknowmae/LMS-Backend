<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    use HasFactory;


    public function locker()
    {
        return $this->belongsTo(user::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'lockerNumber',
        'remarks',
        'status'
    ];

    public function programs()
    {
        return $this->belongsTo(User::class);
    }

    public function departments()
    {
        return $this->belongsTo(User::class);
    }

}
