<?php

use App\Models\Book;
use App\Models\Periodical;
use Illuminate\Support\Facades\Route;

// default view
Route::get('/', function (Request $request) {
    return response()->json(['Response' => 'Library Management System back end is up and running']);
});