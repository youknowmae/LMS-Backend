<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BorrowMaterial extends Model
{
    protected $fillable = [
        'request_id',
        'book_id',
        'user_id', 
        'name',
        'patron_type',
        'department',
        'reason',
        'accession_number',
        'title',
        'location',
        'author',
        'time',
        'num_material',
        'gender',
        'name_staff',
        'position',
        'user_fine',
        'date_of_request',
        'due',
        ];
}
