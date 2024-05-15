<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BorrowMaterialController;
use App\Models\Reservation;
use App\Models\BorrowMaterial; // If you're not using this model in this controller, you can remove this import
use Exception; // Corrected the typo
use Storage;

class ReserveBookController extends Controller
{
    // Function for reserving books
    public function reservebook(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'user_id' => 'required|integer',
            'book_id' => 'required|exists:books,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|boolean',
        ]);

        // Get the BorrowMaterialController instance
        $borrowMaterialController = new BorrowMaterialController();

        // Check if user_id exists in the $users array
        $userExists = $borrowMaterialController->checkUserExists($request->user_id);

        if (!$userExists) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Proceed with reservation

        $reserve = new Reservation();
        $reserve->user_id = $request->user_id;
        $reserve->book_id = $request->book_id;
        $reserve->start_date = $request->start_date; // Corrected field name
        $reserve->end_date = $request->end_date;
        $reserve->status = $request->status;
        
        error_log($reserve);
        $reserve->save();

        return response()->json($reserve);
    }
}
