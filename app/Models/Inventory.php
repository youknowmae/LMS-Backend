<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventory_items';

    protected $fillable = [
        'barcode',
        'accession_number',
        'title',
        'author',
        'location',
        'status',
    ];

    protected $casts = [
        'barcode' => 'string',
        'accession_number' => 'string',
        'title' => 'string',
        'author' => 'string',
        'location' => 'string',
        'status' => 'string',
    ];
}
