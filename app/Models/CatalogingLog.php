<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogingLog extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['user_id', 'action', 'log'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

