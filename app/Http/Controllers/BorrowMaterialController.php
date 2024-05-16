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
        // Check if the book_id exists in the books table
        $book = Book::find($request->book_id);
        
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        // Check if the book is available
        if ($book->available <= 0) {
            return response()->json(['error' => 'Book is not available for borrowing'], 400);
        }

        // Update the availability of the book
        $book->available -= 1;
        $book->save(); // Save the updated book

        // Create a new BorrowMaterial instance
        $borrowMaterial = new BorrowMaterial();
        // Fill the BorrowMaterial instance with request data excluding 'book_id'
        $borrowMaterial->fill($request->all());
        
        // Save the BorrowMaterial instance
        $borrowMaterial->save();
        
        // Check if the BorrowMaterial was saved successfully
        if (!$borrowMaterial->id) {
            // Rollback the decrement operation if saving BorrowMaterial failed
            $book->available += 1;
            $book->save();
            
            return response()->json(['error' => 'Failed to create borrow material'], 500);
        }
        $data = ['borrow_material' => $borrowMaterial];
        return response()->json($data);
    }

        //get the borrowed book list
        public function borrowlist(Request $request){
            $borrowMaterial = BorrowMaterial::with('user')->get();
            return response()->json($borrowMaterial); 
        }


        //return book
        // public function returnbook(Request $request, $id){
        //     $borrowMaterial = 
        // }

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
    