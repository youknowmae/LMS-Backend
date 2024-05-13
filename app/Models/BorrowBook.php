<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowBook extends Model
{

    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'request_id',
        'book_id',
        ];
}
