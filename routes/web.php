<?php

use App\Models\Book;
use App\Models\Periodical;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeriodicalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BorrowBookController;
use App\Http\Controllers\BorrowMaterialController;
//circulation
use App\Http\Controllers\CirculationLogController;

// default view
Route::get('/', function (Request $request) {
    return response()->json(['Response' => 'Library Management System back end is up and running']);
});