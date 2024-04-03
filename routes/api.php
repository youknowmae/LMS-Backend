<?php

use App\Http\Controllers\CatalogingLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeriodicalController;
use App\Http\Controllers\ProjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Cataloging
Route::get('/cataloging/logs', [CatalogingLogController::class, 'get']);
Route::get('/books', [BookController::class, 'getBooks']);
Route::get('/periodicals', [PeriodicalController::class, 'getPeriodicals']);
Route::get('/articles', [ArticleController::class, 'getArticles']);
Route::get('/projects', [ProjectController::class, 'getProjects']);

// Get Materials Using ID 
Route::get('/book/id/{id}', [BookController::class, 'getBook']);
Route::get('/periodical/id/{id}', [PeriodicalController::class, 'getPeriodical']);
Route::get('/article/id/{id}', [ArticleController::class, 'getArticle']);
Route::get('/project/id/{id}', [ProjectController::class, 'getProject']);

// Get Material Image
Route::get('/book/image/{id}', [BookController::class, 'image']);
Route::get('/periodical/image/{id}', [PeriodicalController::class, 'image']);
Route::get('/project/image/{id}', [ProjectController::class, 'image']);

// Get Periodicals and Projects Using Type
Route::get('/periodicals/type/{type}', [PeriodicalController::class, 'getByType']);
Route::get('/projects/type/{type}', [ProjectController::class, 'getByType']);

// Add Materials
Route::post('/books/add/', [BookController::class, 'add']);
Route::post('/periodicals/add/', [PeriodicalController::class, 'add']);
Route::post('/articles/add/', [ArticleController::class, 'add']);
Route::post('/projects/add/', [ProjectController::class, 'add']);

// Update Materials
Route::match(['put', 'patch'], '/books/update/{id}', [BookController::class, 'update']);
Route::match(['put', 'patch'], '/periodicals/update/{id}', [PeriodicalController::class, 'update']);
Route::match(['put', 'patch'], '/articles/update/{id}', [ArticleController::class, 'update']);
Route::match(['put', 'patch'], '/projects/update/{id}', [ProjectController::class, 'update']);

// Delete Materials
Route::delete('/books/delete/{id}', [BookController::class, 'delete']);
Route::delete('/periodicals/delete/{id}', [PeriodicalController::class, 'delete']);
Route::delete('/articles/delete/{id}', [ArticleController::class, 'delete']);
Route::delete('/projects/delete/{id}', [ProjectController::class, 'delete']);
