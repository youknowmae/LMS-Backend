<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

<<<<<<<< HEAD:app/Models/Location.php
    public function books(){
        return $this->hasMany(Book::class);
    }
========
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',  // Add this line
        'item_id',
    ];

    // Your existing relationships and methods...
>>>>>>>> student:app/Models/Favorite.php
}

