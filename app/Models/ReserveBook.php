<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveBook extends Model
{
    use HasFactory;

    protected $table = 'reserve_books';

    protected $primaryKey = 'id';

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'request_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
