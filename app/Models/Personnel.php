<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'access',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'access' => 'array',
    ];

    /**
     * Get the announcements associated with the personnel.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
