<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicProject extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'college',
        'course_1',
        'course_2',
        'course_3',
        'course_4',
        'course_5',
        'course_6',
        ];
}
