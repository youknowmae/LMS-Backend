<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BorrowMaterialController;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Book;
use Exception;
use Storage;

class ReserveBookController extends Controller
{
    public function reservebook(Request $request){
        $payload=json_decode($request->payload);

        // Check if the book_id exists in the books table
        $book = Book::find($payload->book_id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }
        // Check if the book is available
        // if ($book->available <= 0) {
        //     return response()->json(['error' => 'Book is not available for reservation'], 400);
        // }
        // if book is available (1), make reserve status (1)
        // Create Reservation instance
        $reservation = new reservation();
        $reservation -> book_id = $payload->book_id;
        $reservation -> user_id = $payload->user_id;
        // $reservation -> start_date = $payload->start_date;
        // $reservation -> end_date = $payload->end_date;
        $reservation -> date_of_expiration= $payload->date_of_expiration;
        $reservation -> save();

        $data = ['Reservation' => $reservation];
        return response()->json($data);
    }

    public function reservelist(Request $request){
    $reservelist = Reservation::with('user.program', 'user.department', 'user.patrons')->get();
    return response()->json($reservelist);
    }
}


//edited out
// // Function for reserving books
    // public function reservebook(Request $request)
    // {
    //     // Validate incoming request
    //     $request->validate([
    //         'user_id' => 'required|integer',
    //         'book_id' => 'required|exists:books,id',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date',
    //         'status' => 'required|boolean',
    //     ]);

    //     // Get the BorrowMaterialController instance
    //     $borrowMaterialController = new BorrowMaterialController();

    //     // Check if user_id exists in the $users array
    //     $userExists = $borrowMaterialController->checkUserExists($request->user_id);

    //     if (!$userExists) {
    //         return response()->json(['error' => 'User not found'], 404);
    //     }

    //     // Proceed with reservation

    //     $reserve = new Reservation();
    //     $reserve->user_id = $request->user_id;
    //     $reserve->book_id = $request->book_id;
    //     $reserve->start_date = $request->start_date; // Corrected field name
    //     $reserve->end_date = $request->end_date;
    //     $reserve->status = $request->status;
        
    //     error_log($reserve);
    //     $reserve->save();

    //     return response()->json($reserve);
    // }

    // // Create a new BorrowMaterial instance
        // $reservation = new Reservation();
        // // Fill the BorrowMaterial instance with request data excluding 'book_id'
        // $reservation->fill($request->all());
        
        // // Save the BorrowMaterial instance
        // $reservation->save();
        
        // $data = ['Reservation' => $reservation];
        // return response()->json($data);
