<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function getBooks() {
        return Book::all();
    }

    public function getBook($id) {
        // Find the book by its ID
        $book = Book::find($id);

        // Check if the book exists
        if (!$book) {
            // Return a response indicating that the book was not found
            return response()->json(['message' => 'Book not found'], 404);
        }

        // Return the book details
        return response()->json($book);
    }
}

