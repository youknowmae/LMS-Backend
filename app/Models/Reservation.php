<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
        'title',
        'author',
        'location',
        'start_date',
        'end_date',
        'number_of_books',
        'date_of_expiration',
        'fine',
        'status'
    ];

    protected $casts = [
        'date_requested' => 'datetime',
        'date_of_expiration' => 'datetime'
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
