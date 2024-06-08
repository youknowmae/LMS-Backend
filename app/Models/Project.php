<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    
    protected $table = 'academic_projects';

    protected $primaryKey = 'accession';
    protected $keyType = 'string';

    protected $fillable = ['accession', 'category', 'authors', 'title', 'program_id', 'image_location', 'date_published',
                           'keywords', 'language', 'abstract'];

    public function program(){
        return $this->belongsTo(Program::class, 'program', 'program_short');
    }
}
