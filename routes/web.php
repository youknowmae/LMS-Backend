<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;

// Default view
Route::get('/', function () {
return response()->json(['Response' => 'Library Information Management System backend is up and running']);
});

// Authentication routes
Route::post('/login/{subsystem}', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Material routes
Route::middleware(['auth'])->group(function () {
Route::get('/books', [MaterialController::class, 'getAllBooks']);
Route::get('/periodicals', [MaterialController::class, 'getAllPeriodicals']);
Route::get('/articles', [MaterialController::class, 'getAllArticles']);
Route::get('/projects', [MaterialController::class, 'getAllProjects']);

Route::get('/book/id/{id}', [MaterialController::class, 'getBookById']);
Route::get('/periodical/id/{id}', [MaterialController::class, 'getPeriodicalById']);
Route::get('/article/id/{id}', [MaterialController::class, 'getArticleById']);
Route::get('/project/id/{id}', [MaterialController::class, 'getProjectById']);

Route::get('/book/image/{id}', [MaterialController::class, 'getBookImage']);
Route::get('/periodical/image/{id}', [MaterialController::class, 'getPeriodicalImage']);
Route::get('/project/image/{id}', [MaterialController::class, 'getProjectImage']);

Route::get('/periodicals/type/{type}', [MaterialController::class, 'getPeriodicalsByType']);
Route::get('/projects/type/{type}', [MaterialController::class, 'getProjectsByType']);

Route::post('/books/process', [MaterialController::class, 'addBook']);
Route::post('/periodicals/process', [MaterialController::class, 'addPeriodical']);
Route::post('/articles/process', [MaterialController::class, 'addArticle']);
Route::post('/projects/process', [MaterialController::class, 'addProject']);

Route::post('/books/process/{id}', [MaterialController::class, 'updateBook']);
Route::post('/periodicals/process/{id}', [MaterialController::class, 'updatePeriodical']);
Route::post('/articles/process/{id}', [MaterialController::class, 'updateArticle']);
Route::post('/projects/process/{id}', [MaterialController::class, 'updateProject']);

Route::delete('/books/process/{id}', [MaterialController::class, 'deleteBook']);
Route::delete('/periodicals/process/{id}', [MaterialController::class, 'deletePeriodical']);
Route::delete('/articles/process/{id}', [MaterialController::class, 'deleteArticle']);
Route::delete('/projects/process/{id}', [MaterialController::class, 'deleteProject']);
});
