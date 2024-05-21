<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'cataloging_programs';

    protected $fillable = [
        'program',
        'department_id',
        'category',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
