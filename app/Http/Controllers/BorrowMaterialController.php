<?php

namespace App\Http\Controllers;
use App\Models\BorrowMaterial;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use Exeption;
use Storage;


class BorrowMaterialController extends Controller
{
    public function borrowbook(Request $request)
    {
        $payload=json_decode($request->payload);

        // Check if the book_id exists in the books table
        $book = Book::find($payload->book_id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        // Check if the book is available
        if ($book->available <= 0) {
            return response()->json(['error' => 'Book is not available for borrowing'], 400);
        }

        // Update the availability of the book
        // $book->available -= 1;
        // $book->save(); // Save the updated book

        // Create a new BorrowMaterial instance
        $borrowMaterial = new BorrowMaterial();
        $borrowMaterial->book_id = $payload->book_id;
        $borrowMaterial->user_id = $payload->user_id;
        $borrowMaterial->fine = $payload->fine;
        $borrowMaterial->borrow_expiration = $payload->borrow_expiration;
        $borrowMaterial->borrow_date = $payload->borrow_date;
        $borrowMaterial->save();
        
        // Check if the BorrowMaterial was saved successfully
        // if (!$borrowMaterial->id) {
        //     // Rollback the decrement operation if saving BorrowMaterial failed
            $book->available = 0;
            $book->save();
            
        //     return response()->json(['error' => 'Failed to create borrow material'], 500);
        // }
        $data = ['borrow_material' => $borrowMaterial];
        return response()->json($data);
    }

        //get the borrowed book list
        public function borrowlist(Request $request){
            $borrowMaterial = BorrowMaterial::with('user')->get();
            return response()->json($borrowMaterial); 
        }


            //return book
            public function returnbook(Request $request, $id){
                $borrowMaterial = BorrowMaterial::find($id);

                 // Check if the borrowed material exists
                if(!$borrowMaterial){
                    return response()->json(['message' => 'Borrowed material not found'], 404);
                }
                    $borrowMaterial->status = 0;
 
                    $borrowMaterial->date_returned = now();
                // Save the changes
                $borrowMaterial->save();

                // Return a success response
               // return response()->json(['message' => 'Book returned successfully'], 200);
                return response()->json(['message' => $id], 200);
            }

        public function userlist(Request $request){
            $users = User::all();
            return response()->json(['message' => $users], 200);
        }


        public function bookBorrowersReport(Request $request)
{
    $borrowers = BorrowMaterial::with('user.program')
        ->select('user_id')
        ->distinct()
        ->get();

    $borrowersByDepartment = $borrowers->groupBy('user.program.department');
    $borrowersByGender = $borrowers->groupBy('user.gender');

    return response()->json([
        'borrowersByDepartment' => $borrowersByDepartment,
        'borrowersByGender' => $borrowersByGender
    ]);
}
        


}




//edited out
        // // Create a new BorrowBook instance
        // $borrowBook = new BorrowBook();
        // $borrowBook->request_id = $borrowMaterial->id; // Set the request_id
        // $borrowBook->book_id = $request->book_id; // Save the book_id
    
        // // Save the BorrowBook instance
        // $borrowBook->save();
    
        // Check if the BorrowBook was saved successfully
        // if (!$borrowBook->id) {
        //     return response()->json(['error' => 'Failed to create borrow book'], 500);
        // }
    