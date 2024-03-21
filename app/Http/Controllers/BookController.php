<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Get functions 
    public function getBooks() {
        return Book::all();
    }

    public function getBook($id) {
        return Book::find($id);
    }

    public function add(Request $request) {
        $model = new Book();
        $model->fill($request->all());
        $model->save();

        return response()->json($model, 200);
    }
}
