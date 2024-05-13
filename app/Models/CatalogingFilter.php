<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogingFilter extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'location_1', 'location_2', 'location_3'];
}

