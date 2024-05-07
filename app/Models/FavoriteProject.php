<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProject extends Model
{
    use HasFactory;

    protected $fillable = ['favorite_id', 'project_id'];

    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
