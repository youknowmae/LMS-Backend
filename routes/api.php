<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeriodicalController;
use App\Http\Controllers\ProjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
Route::post('/books/add/', [BookController::class, 'add']);
Route::post('/periodicals/add/', [PeriodicalController::class, 'add']);
Route::post('/articles/add/', [ArticleController::class, 'add']);
Route::post('/projects/add/', [ProjectController::class, 'add']);

// Update Materials

// Delete Materials