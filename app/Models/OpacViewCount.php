<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpacViewCount extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'date',
        'view_count'
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];
    

    use HasFactory;
}
