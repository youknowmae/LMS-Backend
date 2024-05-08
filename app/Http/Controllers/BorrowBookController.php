<?php

namespace App\Http\Controllers;

use App\Models\BorrowMaterial;
use App\Models\BorrowBook;


use Illuminate\Http\Request;

class BorrowBookController extends Controller
{
    public function borrowbook(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'user_id' => 'required|numeric',
        'fine' => 'required|numeric',
        'borrow_date' => 'required|date',
        'borrow_expiration' => 'required|date',
        'book_id' => 'required|exists:books,id',
        // Validation for the new fields
        'name' => 'required|string',
        'patron_type' => 'required|string',
        'department' => 'required|string',
        'name_of_staff' => 'nullable|string',
        'position' => 'nullable|string',
    ]);

    // Create a new borrow request
    $borrowMaterial = new BorrowMaterial();
    $borrowMaterial->user_id = $request->user_id;
    $borrowMaterial->fine = $request->fine;
    $borrowMaterial->borrow_date = $request->borrow_date;
    $borrowMaterial->borrow_expiration = $request->borrow_expiration;
    // Set the values for the new fields
    $borrowMaterial->name = $request->name;
    $borrowMaterial->patron_type = $request->patron_type;
    $borrowMaterial->department = $request->department;
    $borrowMaterial->name_of_staff = $request->name_of_staff;
    $borrowMaterial->position = $request->position;
    $borrowMaterial->save();

    // Associate the book with the borrow request
    $borrowBook = new BorrowBook();
    $borrowBook->borrow_material_id = $borrowMaterial->id;
    $borrowBook->book_id = $request->book_id;
    $borrowBook->save();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Book borrowed successfully!');
}



}

