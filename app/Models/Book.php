<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['call_number', 'title', 'author', 'image_location', 'language',
                            'location_id', 'publisher', 'copyright', 'volume', 'issue', 
                            'pages', 'content', 'remarks', 'date_published'];

    public function category()
    {
        return $this->belongsTo(Location::class);
    }
}
