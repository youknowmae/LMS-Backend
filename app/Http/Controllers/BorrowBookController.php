<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BorrowMaterialController;
use App\Models\BorrowMaterial;
use App\Models\BorrowBook;
use App\Models\User;
use App\Models\Book;
use Exeption;
use Storage;

class BorrowBookController extends Controller
{

    public function borrowbook(Request $request){

        // Check if the book_id exists in the books table
        $book = Book::find($request->book_id);
    
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }
    
        // Create a new BorrowMaterial instance
        $borrowMaterial = new BorrowMaterial();
        // Fill the BorrowMaterial instance with request data excluding 'book_id'
        $borrowMaterial->fill($request->except('book_id'));
        // Save the BorrowMaterial instance
        $borrowMaterial->save();
    
        // Check if the BorrowMaterial was saved successfully
        if (!$borrowMaterial->id) {
            return response()->json(['error' => 'Failed to create borrow material'], 500);
        }
    
        // Create a new BorrowBook instance
        $borrowBook = new BorrowBook();
        $borrowBook->request_id = $borrowMaterial->id; // Set the request_id
        $borrowBook->book_id = $request->book_id; // Save the book_id
    
        // Save the BorrowBook instance
        $borrowBook->save();
    
        // Check if the BorrowBook was saved successfully
        if (!$borrowBook->id) {
            return response()->json(['error' => 'Failed to create borrow book'], 500);
        }
    
        $data = [
            'borrow_material' => $borrowMaterial,
            'borrow_book' => $borrowBook,
        ];
    
        return response()->json($data);

        
    //return response()->json(['message'=> 'borrow record created','data' => $borrowMaterial], 201);

        // // Validate the incoming request data
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'fine' => 'required|numeric',
        //     'borrow_date' => 'required|date',
        //     'borrow_expiration' => 'required|date',
        //     'book_id' => 'required|exists:books,id',
        //     // // Validation for the new fields
        //     // 'name' => 'required|string',
        //     // 'patron_type' => 'required|string',
        //     // 'department' => 'required|string',
        //     // 'name_of_staff' => 'nullable|string',
        //     // 'position' => 'nullable|string',
        // ]);

        // // Get the BorrowMaterialController instance
        // $borrowMaterialController = new BorrowMaterialController();

        // // Check if user_id exists in the users table
        // $userExists = User::find($request->user_id);

        // if (!$userExists) {
        //     return response()->json(['error' => 'User not found'], 404);
        // }

        // // // Check if user_id exists in the $users array
        // // $userExists = $borrowMaterialController->checkUserExists($request->user_id);

        // // if (!$userExists) {
        // //     return response()->json(['error' => 'User not found'], 404);
        // // }
        // //proceed

        // $borrow = new BorrowMaterial();
        // $borrow->user_id = $request->user_id;
        // $borrow->fine = $request->fine;
        // $borrow->borrow_date = $request->borrow_date;
        // $borrow->borrow_expiration = $request->borrow_expiration;
        // error_log($borrow);
        // $borrow->save();
        // // return response()->json($borrow);

        // $book = new BorrowBook();
        // $book-> request_id = $borrow->id;
        // $book-> book_id = $request-> book_id;
        // $book->save();
        // error_log($book);

        // // Create an array containing both borrow material and borrow book objects
        // $data = [
        //     'borrow_material' => $borrow,
        //     'borrow_book' => $book,
        // ];

        // // Return the array as JSON response
        // return response()->json($data);
    }

    public function borrowlist(Request $request){
        $borrowMaterial = BorrowMaterial::with('user')->get();
        return response()->json($borrowMaterial); 
    }

}

