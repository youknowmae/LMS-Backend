    <?php
    use Illuminate\Support\Facades\Route;

    // Default view
    Route::get('/', function () {
    return response()->json(['Response' => 'Library Information Management System backend is up and running']);
    });

    // Authentication routes
    authenticationRoutes();
