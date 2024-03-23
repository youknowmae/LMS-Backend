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

        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {
        $model = Book::find($id);
        $model->update($request->all());
        $model->save();

        return response()->json($model, 200);
    }

    public function patch(Request $request, $id) {
        $model = Book::find($id);
        $model->update($request->all());
        $model->save();

        return response()->json($model, 200);
    }

    public function delete($id) {
        $model = Book::find($id);
        $model->delete();

        return response()->json('Record Deleted', 200);
    }
}
