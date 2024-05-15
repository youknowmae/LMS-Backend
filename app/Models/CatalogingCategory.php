<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogingCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function filters()
    {
        return $this->hasMany(CatalogingFilter::class);
    }
}

