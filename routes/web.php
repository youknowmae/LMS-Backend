<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeriodicalController;
use App\Http\Controllers\ProjectController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

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

// Update Materials

// Delete Materials
