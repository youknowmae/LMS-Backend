<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    /**
     * Get the announcements associated with the personnel.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
