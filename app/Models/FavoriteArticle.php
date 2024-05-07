<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteArticle extends Model
{
    use HasFactory;

    protected $fillable = ['favorite_id', 'article_id'];

    public function favorite()
    {
        return $this->belongsTo(Favorite::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
