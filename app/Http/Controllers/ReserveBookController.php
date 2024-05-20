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
    
        // Create Reservation instance
        $reservation = new reservation();
        $reservation -> book_id = $payload->book_id;
        $reservation -> user_id = $payload->user_id;
        $reservation -> start_date = $payload->start_date;
        $reservation -> end_date = $payload->end_date;
        // $reservation -> date_of_expiration= $payload->date_of_expiration;
        $reservation -> save();

        $data = ['Reservation' => $reservation];
        return response()->json($data);
    }

    public function reservelist(Request $request){
        $reservelist = Reservation::with('user.program', 'user.department', 'user.patrons')->get();
        return response()->json($reservelist);
    }

    public function queue(Request $request){
       // Fetch all queue data from the reservations table
            $queueData = Reservation::orderBy('book_id')
            ->orderBy('start_date', 'asc')
            ->get(['user_id', 'book_id', 'start_date']); // Adjust the fields as necessary

            // Return the data as JSON (or any other format required by the front end)
            return response()->json($queueData);
    }

    public function getQueuePosition(Request $request, $id)
{
    // Fetch all reservations for the user's books with a status other than 0
    $userReservations = Reservation::where('user_id', $id)
                        ->where('status', '!=', 0) // Exclude reservations with status 0
                        ->orderBy('start_date')
                        ->get(['book_id', 'start_date']);

    // Initialize the queue positions
    $queuePositions = [];

    // Process the user's reservations and determine the queue position for each book
    foreach ($userReservations as $userReservation) {
        $bookId = $userReservation->book_id;
        
        // Count the number of reservations with earlier start dates for the same book
        $position = Reservation::where('book_id', $bookId)
                        ->where('start_date', '<', $userReservation->start_date)
                        ->where('status', '!=', 0) // Exclude reservations with status 0
                        ->count() + 1; // Add 1 to start positions from 1

        // Assign the queue position for the book
        $queuePositions[$bookId] = $position;
    }

    return response()->json($queuePositions);
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
