<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['id', 'category', 'title', 'program_id', 'image_location', 'date_published',
                            'language', 'abstract'];

    public function program(){
        return $this->belongsTo(Program::class);
    }

    public function projectAuthors() {
        return $this->hasMany(ProjectAuthor::class);
    }
}
