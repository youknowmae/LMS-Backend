<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'location',
        'full_location'
    ];
    
    public function books(){
        return $this->hasMany(Book::class);
    }
}

