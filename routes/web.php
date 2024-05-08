<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeriodicalController;
use App\Http\Controllers\ProjectController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

//circulation
use App\Http\Controllers\CirculationLogController;

// default view
Route::get('/', function () {
    return view('welcome');
});

// Get Materials
Route::get('/books', [BookController::class, 'getBooks']);
Route::get('/book/{id}', [BookController::class, 'getBook']);
Route::get('/periodicals', [PeriodicalController::class, 'getPeriodicals']);
Route::get('/periodical/{id}', [PeriodicalController::class, 'getPeriodical']);
Route::get('/articles', [ArticleController::class, 'getArticles']);
Route::get('/article/{id}', [ArticleController::class, 'getArticle']);
Route::get('/projects', [ProjectController::class, 'getProjects']);
Route::get('/project/{id}', [ProjectController::class, 'getProject']);

// Add Materials
//circulation
        //users
        Route::get('/users', [BorrowMaterialController::class, 'userlist']);
        
        //returned
        Route::get('/borrow-list', [BorrowMaterialController::class, 'borrowlist']);

        //borrow
        Route::post('/borrow-request/book', [BorrowMaterialController::class, 'request']);

        Route::get('/token', function() {
            return response()->json(['csrf_token' => csrf_token()]);
        });
// Update Materials

// Delete Materials
