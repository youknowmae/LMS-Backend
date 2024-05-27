<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProgramController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController, App\Http\Controllers\CatalogingLogController, App\Http\Controllers\ArticleController,
App\Http\Controllers\BookController, App\Http\Controllers\PeriodicalController, App\Http\Controllers\ProjectController,
App\Http\Controllers\CatalogingReportController, App\Http\Controllers\BorrowBookController,App\Http\Controllers\BorrowMaterialController
,App\Http\Controllers\ReserveBookController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\LockersLogController;

use App\Http\Controllers\CirculationUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatronController;
use App\Http\Controllers\CollegeController; 
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DepartmentController;



use App\Http\Controllers\ReservationController;
use App\Models\Book;

Route::post('/studentlogin', [AuthController::class, 'studentLogin']);
Route::get('/', function (Request $request) {
    return response()->json(['Response' => 'API routes are available']);
});
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\LockerHistoryController;

// logged in user tester
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

// Auth Routes
Route::post('/login/{subsystem}', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/refresh', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Maintenance route
Route::middleware(['auth:sanctum', 'check.access:superadmin'])->group(function () {
    Route::get('/personnels', [UserController::class, 'index']);
    Route::post('/personnels', [UserController::class, 'store']);
    Route::get('/personnels/{personnel}', [UserController::class, 'show']);
    Route::post('/personnels/{personnel}', [UserController::class, 'update']);
    Route::delete('/personnels/{personnel}', [UserController::class, 'destroy']);

    //Inventory routes
    Route::prefix('/inventory')->group(function () {
        Route::prefix('/books')->group(function () {
            Route::get('/clear', [InventoryController::class, 'clearBooksHistory']);
            Route::get('/{filter}', [InventoryController::class, 'getBookInventory']);
            Route::post('/{id}', [InventoryController::class, 'updateBookStatus']);
        });
        // Route::get('/', [InventoryController::class, 'index']);
        // Route::post('/enter', [InventoryController::class, 'enterBarcode']);
        // Route::post('/scan', [InventoryController::class, 'scanBarcode']);
        // Route::post('/clear', [InventoryController::class, 'clearHistory']);
    });

    //circulation 
    Route::get('/patrons', [PatronController::class, 'index']);
    Route::get('/patrons/{id}', [PatronController::class, 'edit']);
    Route::post('/patrons/{id}', [PatronController::class, 'update']);

    //announcements
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);
    Route::post('/announcements/{announcement}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy']);

    //cataloging
    Route::get('/locations', [LocationController::class, 'getLocations']);
    Route::post('/locations', [LocationController::class, 'create']);

    Route::prefix('/lockers')->group(function () {
        Route::get('/logs', [LockerHistoryController::class, 'getLogs']); 

        Route::get('/', [LockerController::class, 'index']);
        Route::post('/', [LockerController::class, 'store']);
        Route::get('/latest', [LockerController::class, 'getStartingLockerNumber']);
        Route::get('/{locker}', [LockerController::class, 'show']);
        Route::post('/{locker}', [LockerController::class, 'update']);
        Route::get('/delete/{locker}', [LockerController::class, 'destroy']);
    });
});


// Cataloging Process routes
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:edit']], function () {
    
    // View cataloging logs
    Route::get('cataloging/logs', [CatalogingLogController::class, 'get']);
    Route::get('books/locations', [BookController::class, 'getLocations']);

    // View Reports
    Route::get('cataloging/reports/material-counts', [CatalogingReportController::class, 'getCount']);
    Route::get('cataloging/reports/pdf/{type}', [CatalogingReportController::class, 'generatePdf']);
    Route::post('cataloging/reports/excel/{type}', [CatalogingReportController::class, 'generateExcel']);

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


// Circulation Process Routes
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:edit']], function () {

    // display user list
    Route::get('/users', [BorrowMaterialController::class, 'userlist']);

    // borrow list
    Route::get('/borrow-list', [BorrowMaterialController::class, 'borrowlist']);

    // update borrow list
    Route::put('borrow-edit/{id}',[BorrowMaterialController:: class, 'borrowEdit']);
    
    // borrow-list returning book
    Route::put('return-book/{id}', [BorrowMaterialController::class, 'returnbook']);

    //returned book list
    Route::get('returned-list',[BorrowMaterialController::class,'returnedlist']);

    //reservebook
    Route::post('/reserve/book', [ReserveBookController::class, 'reservebook']);

    //reservationlist
    Route::get('reservation-list/{type}', [ReserveBookController::class, 'reservelist']);

    //get queue data
    Route::get('queue', [ReserveBookController::class, 'queue']);
    Route::get('queue-pos/{id}', [ReserveBookController::class, 'getQueuePosition']);
    

    // borrow book 
    Route::post('/borrow/book', [BorrowMaterialController::class, 'borrowbook']);
    Route::post('/fromreserve/book/{id}', [BorrowMaterialController::class, 'fromreservation']);
    Route::get('circulation/get-user/{id}', [CirculationUserController::class, 'getUser']);
    Route::get('circulation/get-book/{id}', [CirculationUserController::class, 'getBook']);

    //get report
    Route::get('report', [BorrowMaterialController::class, 'bookBorrowersReport']);
    Route::get('topborrowers', [BorrowMaterialController::class, 'topborrowers']);
    Route::get('mostborrowed', [BorrowMaterialCOntroller::class, 'mostborrowed']);
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
    Route::get('projects/department/{type}', [ProjectController::class, 'getByDepartment']);

    //Add Programs
    Route::get('/programs', [ProgramController::class, 'get']);
    Route::post('/programs', [ProgramController::class, 'store']);
    
    //Add Departments
    Route::get('/colleges', [CollegeController::class, 'index']);
    Route::post('/colleges', [CollegeController::class, 'store']);
});

/* STUDENT ROUTES */
// Route::group(['middleware' => ['studentauth']], function () {
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:view']], function () { 

    // ROUTES FOR VIEWING 
    Route::get('student/books', [BookController::class, 'viewBooks']);
    Route::get('student/periodicals', [PeriodicalController::class, 'viewPeriodicals']);
    Route::get('student/articles', [ArticleController::class, 'viewArticles']);
    Route::get('student/projects/department/{department}', [ProjectController::class, 'getProjectCategoriesByDepartment']);//'viewProjectsByDepartment']);

    // FOR SINGLE RECORD
    Route::get('student/book/id/{id}', [BookController::class, 'viewBook']);
    Route::get('student/periodical/id/{id}', [PeriodicalController::class, 'viewPeriodical']);
    Route::get('student/article/id/{id}', [ArticleController::class, 'viewArticle']);
    Route::get('student/project/id/{id}', [ProjectController::class, 'viewProject']);//'viewProjectsByDepartment']);

    // FOR FILTERING MATERIAL TYPE
    Route::get('student/periodicals/type/{type}', [PeriodicalController::class, 'viewPeriodicalByType']);
    Route::get('student/articles/type/{type}', [ArticleController::class, 'viewArticlesByType']);
    Route::get('student/projects/type/{type}', [ProjectController::class, 'viewProjectByType']);//'viewP

    // Reservation routes
    Route::post('reservations', [ReservationController::class, 'store']); // Changed from 'reservation/{id}' to 'reservations'
    // Reservation Cancel
    Route::delete('/cancel-reservation/{id}', [ReservationController::class, 'cancelReservation']);
    
    
    // API resource route for reservations
   
    Route::get('reservations/{id}', [ReservationController::class, 'getUserById']);
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy']);
});

// RED ZONE 
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:view']], function () {
    Route::get('images/delete/single', [ImageController::class, 'delete'])->name('images.delete');
    Route::get('images/delete/all/{type}', [ImageController::class, 'deleteAll']);
});

//opac routes
Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'opac'], function () {
    //books
    Route::get('books', [BookController::class, 'opacGetBooks']);
    Route::get('/books/search', [BookController::class, 'opacSearchBooks']);
    Route::get('/book/{id}', [BookController::class, 'getBook']);


    //periodicals
    Route::prefix('/periodicals')->group(function() { 
        Route::get('/{material_type}', [PeriodicalController::class, 'opacGetPeriodicals']);
        Route::get('/{material_type}/search', [PeriodicalController::class, 'opacSearchPeriodicals']);
    });
    Route::get('/periodical/{id}', [PeriodicalController::class, 'getPeriodical']);

    //articles
    Route::get('/articles', [ArticleController::class, 'opacGetArticles']);
    Route::get('/articles/search', [ArticleController::class, 'opacSearchArticles']);
    Route::get('/article/{id}', [ArticleController::class, 'getArticle']);

    //projects
    Route::prefix('/projects')->group(function() { 
        Route::get('/{category}', [ProjectController::class, 'opacGetProjects']);
        Route::get('/{category}/search', [ProjectController::class, 'opacSearch']);
    });
    Route::get('/project/{id}', [ProjectController::class, 'opacGetProject']);
});

// locker routes

Route::get('/lockers-log', [LockersLogController::class, 'getLockerLogs']);
Route::get('/lockers-logs-with-users', [LockersLogController::class, 'fetchLockersLogsWithUsers']);


//LOCKER MAINTENANCE
Route::post('/locker', [LockerController::class, 'locker']);
Route::get('/getlocker', [LockerController::class, 'getlocker']);

Route::get('locker/{lockerid}', [LockerController::class, 'getLockerInfo']);

Route::get('/locker/{id}', 'App\Http\Controllers\LockerController@getLockerInfo');
Route::post('/locker/info', 'LockerController@getLockerInfo');
Route::get('/locker', 'LockerController@getAllLockers');
Route::get('/locker-counts', 'LockerController@getLockerCounts');

Route::get('/locker', [LockerController::class, 'getAllLockers']);

Route::get('/locker/{id}', [LockerController::class, 'getLockerInfo'])->where('id', '[0-9]+'); // Kung ang id ay numerical
Route::get('/locker-counts', [LockerController::class, 'getLockerCounts']);
Route::get('/history', [LockerController::class, 'getLockerHistory']);
Route::get('/gender-counts', [LockerController::class, 'getGenderCounts']);

Route::get('/department-counts', [LockerController::class, 'getDepartmentCounts']);
Route::get('/college-counts', [LockerController::class, 'getCollegeCounts']);


Route::get('/college-program-counts', [LockerController::class, 'getcollegeProgramCounts']);
Route::post('/locker/{lockerId}/scan', [LockerController::class, 'scanLockerQRCode']);


//LOCKERLOG



// logged in user tester
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Login Routes
Route::post('/login/{subsystem}', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Cataloging Process routes
Route::group(['middleware' => ['auth:sanctum', 'ability:materials:edit']], function () {

    // View cataloging logs
    Route::get('/cataloging/logs', [CatalogingLogController::class, 'get']);

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
    Route::get('/projects/type/{type}', [ProjectController::class, 'getByType']);
});

//circulation 
Route::get('/patrons', [PatronController::class, 'index']);
Route::get('/patrons/{id}', [PatronController::class, 'edit']);
Route::post('/patrons/{id}', [PatronController::class, 'update']);

//Maintenance Cataloging Routes
Route::middleware(['auth:sanctum', 'check.access:superadmin'])->group(function () {
    Route::post('/add-programs',[DepartmentController::class, 'AddProgram']);
    Route::get('view/{department}',[ProgramController::class,'viewDepartmentProgram']);
    Route::post('/add-program',[DepartmentController::class, 'store']);
    
    
});