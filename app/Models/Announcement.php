<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    protected $fillable = ['title', 'category', 'date', 'author', 'blurb', 'file_path'];

    public function setFilePathAttribute($file)
    {
        if ($file) {
            $path = Storage::disk('public')->put('announcements', $file);
            $this->attributes['file_path'] = $path;
        }
    }
}
