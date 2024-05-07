<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritePeriodical extends Model
{
    use HasFactory;

    protected $fillable = ['favorite_id', 'periodical_id'];

    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
    }

    public function periodical()
    {
        return $this->belongsTo(Periodical::class);
    }
}
