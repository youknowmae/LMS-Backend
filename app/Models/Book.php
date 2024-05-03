<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['id', 'isbn', 'call_number', 'title', 'author', 'publisher',
                            'location_id', 'copyright', 'volume', 'edition', 'remarks',
                            'pages', 'content', 'remarks', 'purchased_date', 'source_of_fund',
                            'price'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
