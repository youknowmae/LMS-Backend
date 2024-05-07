<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteBook extends Model
{
    use HasFactory;

    protected $fillable = ['favorite_id', 'book_id'];

    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
