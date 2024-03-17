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
        return Book::find($id);
    }
}
