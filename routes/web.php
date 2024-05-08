<?php

use App\Models\Book;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LockerController;

// default view
Route::get('/', function (Request $request) {
    return response()->json(['Response' => 'Library Information Management System back end is up and running']);
});

Route::middleware(['auth', 'check.access:Circulation'])->group(function () {
    // Circulation related routes here
});

Route::resource('personnel', PersonnelController::class);

Route::resource('patron-types', 'PatronTypeController');

Route::resource('announcements', 'AnnouncementController');

Route::post('/inventory/clear', 'InventoryController@clear')->middleware('auth');

Route::get('/inventory/scan', 'InventoryController@showScanForm');
Route::post('/inventory/scan', 'InventoryController@processScanForm');

Route::get('/lockers/add', [LockerController::class, 'showAddForm'])
    ->name('lockers.add')
    ->middleware('authorizeToAddLockers');

Route::post('/lockers/add', [LockerController::class, 'add'])
    ->name('lockers.store')
    ->middleware('authorizeToAddLockers');
