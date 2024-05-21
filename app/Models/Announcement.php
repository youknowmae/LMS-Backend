<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    use HasFactory;
    /**
     * @var bool|mixed
     */
    public mixed $file_path;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'category',
        'date', 
        'author', 
        'blurb', 
        'file_path',
        'content'
    ];
    /**
     * Automatically set the file path attribute when a file is uploaded.
     *
     * @param mixed $file
     * @return void
     */
    public function setFilePathAttribute($file)
    {
        if ($file) {
            $path = Storage::disk('public')->put('announcements', $file);
            $this->attributes['file_path'] = $path;
        }
    }
}
