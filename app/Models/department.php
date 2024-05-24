<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['department', 'full_department'];

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function programs() {
        return $this->hasMany(Program::class);
    }

        public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
