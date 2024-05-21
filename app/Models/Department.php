<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'cataloging_departments';

    protected $fillable = [
        'department',
        'full_department',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class, 'department_id');
    }
}

