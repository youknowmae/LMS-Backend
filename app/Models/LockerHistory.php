<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockerHistory extends Model
{
    use HasFactory;

    protected $table = 'locker_history';

    protected $fillable = [
        'user_id',
        'action',
        'log'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
