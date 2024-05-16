<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\LockersLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController, App\Http\Controllers\CatalogingLogController, App\Http\Controllers\ArticleController,
App\Http\Controllers\BookController, App\Http\Controllers\PeriodicalController, App\Http\Controllers\ProjectController,
App\Http\Controllers\CatalogingReportController;

use App\Http\Controllers\UserController;
use App\Models\Book;
use App\Http\Controllers\CirculationLogController;
use App\Http\Controllers\CatalogingFilterController;
use App\Http\Controllers\CatalogingCategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AcademicProjectController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\AnnouncementController;

Route::get('/', function (Request $request) {
    return response()->json(['Response' => 'API routes are available']);
});

// logged in user tester
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tester Routes
Route::get('cataloging/reports/materials', [CatalogingReportController::class, 'count']);
Route::get('cataloging/reports/pdf', [CatalogingReportController::class, 'generatePdf']);

// Cataloging Process routes
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:edit']], function () {

    // View cataloging logs
    Route::get('/cataloging/logs', [CatalogingLogController::class, 'get']);
    Route::get('books/locations', [BookController::class, 'getLocations']);


    Route::get('books/locations', [BookController::class, 'getLocations']);

    // Add Materials
    Route::post('/books/process', [BookController::class, 'add']);
    Route::post('/periodicals/process', [PeriodicalController::class, 'add']);
    Route::post('/articles/process', [ArticleController::class, 'add']);
    Route::post('/projects/process', [ProjectController::class, 'add']);

    // Update Materials
    Route::put('/books/process/{id}', [BookController::class, 'update']);
    Route::put('/periodicals/process/{id}', [PeriodicalController::class, 'update']);
    Route::put('/articles/process/{id}', [ArticleController::class, 'update']);
    Route::put('/projects/process/{id}', [ProjectController::class, 'update']);

    // Delete Materials
    Route::delete('/books/process/{id}', [BookController::class, 'delete']);
    Route::delete('/periodicals/process/{id}', [PeriodicalController::class, 'delete']);
    Route::delete('/articles/process/{id}', [ArticleController::class, 'delete']);
    Route::delete('/projects/process/{id}', [ProjectController::class, 'delete']);
});

// Material viewing routes
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:view']], function () {
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
    Route::get('/articles/type/{type}', [ArticleController::class, 'getByType']);
    Route::get('/projects/type/{type}', [ProjectController::class, 'getByType']);
});

// RED ZONE
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:view']], function () {
    Route::get('/images/delete/single', [ImageController::class, 'delete'])->name('images.delete');
    Route::get('/images/delete/all/{type}', [ImageController::class, 'deleteAll']);
});

use App\Models\Location;
Route::get('/test', function( ) {
    $books = Book::with('location')->find(1);
    return $books;
});

//Routes for Personnels
Route::middleware(['auth:sanctum', 'check.access'])->group(function () {
    Route::get('/personnels', [PersonnelController::class, 'index']);
    Route::post('/personnels', [PersonnelController::class, 'store']);
    Route::get('/personnels/{personnel}', [PersonnelController::class, 'show']);
    Route::put('/personnels/{personnel}', [PersonnelController::class, 'update']);
    Route::delete('/personnels/{personnel}', [PersonnelController::class, 'destroy']);
});

//Routes for Circulation
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/circulation-logs', [CirculationLogController::class, 'index']);
    Route::post('/circulation-logs', [CirculationLogController::class, 'store']);
    Route::get('/circulation-logs/{id}', [CirculationLogController::class, 'show']);
    Route::put('/circulation-logs/{id}', [CirculationLogController::class, 'update']);
    Route::delete('/circulation-logs/{id}', [CirculationLogController::class, 'destroy']);
});

//Routes for Cataloging
Route::prefix('cataloging')->group(function () {
    Route::get('/logs', [CatalogingLogController::class, 'get']);
    Route::post('/logs/{action}/{title}/{type}/{location?}', [CatalogingLogController::class, 'add']);
    Route::get('/reports', [CatalogingReportController::class, 'index']);
    Route::post('/cataloging/filters', [CatalogingLogController::class, 'createFilter']);
    Route::post('/cataloging/academic-projects', [CatalogingLogController::class, 'addAcademicProject']);

    //filters
    Route::get('/materialscontent', [CatalogingLogController::class, 'materialsContent']);
    Route::post('/filters/category', [CatalogingFilterController::class, 'updateCategoryFilters']);
    Route::post('/filters/location', [CatalogingFilterController::class, 'updateLocationFilters']);
    Route::post('/categories', [CatalogingCategoryController::class, 'addCategory']);

    //search for materials
    Route::get('/materials/search', [MaterialController::class, 'search']);

    //search for academic projects
    Route::get('/academic-projects/search', [AcademicProjectController::class, 'search']);
});

// Authentication routes
/**
 * @return void
 */
function authenticationRoutes(): void
{
    Route::post('/login/{subsystem}', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Material routes
    Route::middleware(['auth:sanctum'])->group(function () {
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

//Academic projects routes
    Route::prefix('academic-projects')->group(function () {
        Route::get('/', [AcademicProjectController::class, 'index']);
        Route::post('/', [AcademicProjectController::class, 'store']);
        Route::put('/{academicProject}', [AcademicProjectController::class, 'update']);
        Route::delete('/{academicProject}', [AcademicProjectController::class, 'destroy']);
    });

    //Announcement routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/announcements', [AnnouncementController::class, 'index']);
        Route::post('/announcements', [AnnouncementController::class, 'store']);
        Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);
        Route::post('/announcements/{announcement}', [AnnouncementController::class, 'update']);
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy']);
    });
//Inventory routes
    Route::prefix('inventory')->group(function () {
        Route::post('/enter', [InventoryController::class, 'enterBarcode']);
        Route::post('/scan', [InventoryController::class, 'scanBarcode']);
        Route::post('/clear', [InventoryController::class, 'clearHistory']);
    });

    Route::prefix('lockers')->group(function () {
        Route::get('/', [LockerController::class, 'index']);
        Route::post('/', [LockerController::class, 'store']);
        Route::get('/{locker}', [LockerController::class, 'show']);
        Route::put('/{locker}', [LockerController::class, 'update']);
        Route::delete('/{locker}', [LockerController::class, 'destroy']);

        // Locker history routes
        Route::prefix('{locker}/history')->group(function () {
            Route::get('/', [LockersLogController::class, 'index']);
            Route::post('/', [LockersLogController::class, 'store']);
            Route::get('/{lockersLog}', [LockersLogController::class, 'show']);
            Route::put('/{lockersLog}', [LockersLogController::class, 'update']);
            Route::delete('/{lockersLog}', [LockersLogController::class, 'destroy']);
        });
    });
}
authenticationRoutes();
