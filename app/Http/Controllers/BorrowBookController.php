<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BorrowMaterialController;
use App\Models\BorrowMaterial;
use App\Models\BorrowBook;
use App\Models\User;
use Exeption;
use Illuminate\Http\Request;
use Storage;

class BorrowBookController extends Controller
{
    //function for borrowing books
    public function borrowbook(Request $request){
        // Validate the incoming request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fine' => 'required|numeric',
            'borrow_date' => 'required|date',
            'borrow_expiration' => 'required|date',
            'book_id' => 'required|exists:books,id',
            // // Validation for the new fields
            // 'name' => 'required|string',
            // 'patron_type' => 'required|string',
            // 'department' => 'required|string',
            // 'name_of_staff' => 'nullable|string',
            // 'position' => 'nullable|string',
        ]);

        // Get the BorrowMaterialController instance
        $borrowMaterialController = new BorrowMaterialController();

        // Check if user_id exists in the users table
        $userExists = User::find($request->user_id);

        if (!$userExists) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // // Check if user_id exists in the $users array
        // $userExists = $borrowMaterialController->checkUserExists($request->user_id);

        // if (!$userExists) {
        //     return response()->json(['error' => 'User not found'], 404);
        // }
        //proceed

        $borrow = new BorrowMaterial();
        $borrow->user_id = $request->user_id;
        $borrow->fine = $request->fine;
        $borrow->borrow_date = $request->borrow_date;
        $borrow->borrow_expiration = $request->borrow_expiration;
        error_log($borrow);
        $borrow->save();
        // return response()->json($borrow);

        $book = new BorrowBook();
        $book-> request_id = $borrow->id;
        $book-> book_id = $request-> book_id;
        $book->save();
        error_log($book);

        // Create an array containing both borrow material and borrow book objects
        $data = [
            'borrow_material' => $borrow,
            'borrow_book' => $book,
        ];

        // Return the array as JSON response
        return response()->json($data);
    }


    public function borrowlist(Request $request){
        //oy yung array data update yan dapat
    }
}

