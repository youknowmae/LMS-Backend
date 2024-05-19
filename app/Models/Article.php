<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['accession', 'material_type', 'title', 'authors', 'language', 'subject', 'date_published',
                            'publisher', 'volume', 'issue', 'pages', 'abstract', 'remarks'];
}