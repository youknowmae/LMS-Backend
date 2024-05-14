<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProgramController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController, App\Http\Controllers\CatalogingLogController, App\Http\Controllers\ArticleController,
App\Http\Controllers\BookController, App\Http\Controllers\PeriodicalController, App\Http\Controllers\ProjectController,
App\Http\Controllers\CatalogingReportController;

use App\Http\Controllers\ReservationController;
use App\Models\Book;

Route::post('/studentlogin', [AuthController::class, 'studentLogin']);
Route::get('/', function (Request $request) {
    return response()->json(['Response' => 'API routes are available']);
});

// logged in user tester
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

// Auth Routes
Route::post('/login/{subsystem}', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/refresh', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Tester Routes


// Cataloging Process routes
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:edit']], function () {
    
    // View cataloging logs
    Route::get('cataloging/logs', [CatalogingLogController::class, 'get']);
    Route::get('books/locations', [BookController::class, 'getLocations']);

    // View Reports
    Route::get('cataloging/reports/material-counts', [CatalogingReportController::class, 'getCount']);
    Route::get('cataloging/reports/pdf', [CatalogingReportController::class, 'generatePdf']);

    // View locations
    Route::get('books/locations', [BookController::class, 'getLocations']);
    
    // Add Materials
    Route::post('books/process', [BookController::class, 'add']);
    Route::post('periodicals/process', [PeriodicalController::class, 'add']);
    Route::post('articles/process', [ArticleController::class, 'add']);
    Route::post('projects/process', [ProjectController::class, 'add']);

    // Update Materials
    Route::put('books/process/{id}', [BookController::class, 'update']);
    Route::put('periodicals/process/{id}', [PeriodicalController::class, 'update']);
    Route::put('articles/process/{id}', [ArticleController::class, 'update']);
    Route::put('projects/process/{id}', [ProjectController::class, 'update']);

    // Delete Materials
    Route::delete('books/process/{id}', [BookController::class, 'delete']);
    Route::delete('periodicals/process/{id}', [PeriodicalController::class, 'delete']);
    Route::delete('articles/process/{id}', [ArticleController::class, 'delete']);
    Route::delete('projects/process/{id}', [ProjectController::class, 'delete']);
});

// Material viewing routes
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:read']], function () {

    Route::get('books', [BookController::class, 'getBooks']);
    Route::get('periodicals', [PeriodicalController::class, 'getPeriodicals']);
    Route::get('articles', [ArticleController::class, 'getArticles']);
    Route::get('projects', [ProjectController::class, 'getProjects']);

    // Get Materials Using ID 
    Route::get('book/id/{id}', [BookController::class, 'getBook']);
    Route::get('periodical/id/{id}', [PeriodicalController::class, 'getPeriodical']);
    Route::get('article/id/{id}', [ArticleController::class, 'getArticle']);
    Route::get('project/id/{id}', [ProjectController::class, 'getProject']);

    // Get Periodicals and Projects Using Type
    Route::get('periodicals/type/{type}', [PeriodicalController::class, 'getByType']);
    Route::get('articles/type/{type}', [ArticleController::class, 'getByType']);
    Route::get('projects/type/{type}', [ProjectController::class, 'getByType']);

    Route::get('programs', [ProgramController::class, 'get']);
});

/* STUDENT ROUTES */
Route::group(['middleware' => ['studentauth']], function () {

    // ROUTES FOR VIEWING 
    Route::get('student/books', [BookController::class, 'viewBooks']);
    Route::get('student/periodicals', [PeriodicalController::class, 'viewPeriodicals']);
    Route::get('student/articles', [ArticleController::class, 'viewArticles']);
    Route::get('student/projects/{department}', [ProjectController::class, 'viewProjectsByDepartment']);

    // Reservation routes
    Route::post('reservation/{id}', [ReservationController::class, 'reserve']);
    // Reservation Cancel
    Route::delete('/cancel-reservation/{id}', [ReservationController::class, 'cancelReservation']);
    
    
    // API resource route for reservations
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::get('reservations/{reservation}', [ReservationController::class, 'show']);
    Route::put('reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy']);
    
});

// RED ZONE 
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:view']], function () {
    Route::get('images/delete/single', [ImageController::class, 'delete'])->name('images.delete');
    Route::get('images/delete/all/{type}', [ImageController::class, 'deleteAll']);
});

Route::post('cataloging/reports/excel/{type}', [CatalogingReportController::class, 'excel']);

Route::get('test/{text}', function(string $text) {
    $pass = Hash::make('password');
    return response()->json(['text' => Hash::check($text, $pass)], 200);
    if(Hash::make($text) == Hash::make('password'))
        return response()->json(['response' => 'match'], 200);
    else 
        return response()->json(['response' => 'not a match'], 400);
});