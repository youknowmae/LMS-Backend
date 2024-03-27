<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use App\Models\Location;

class BookController extends Controller
{
    // Get functions 
    public function getBooks() {
        $books = [];
        
        $books = Book::join('locations', 'books.location_id', '=', 'locations.id')
        ->select('books.id', 'books.title', 'books.created_at', 'books.author', 'books.copyright', 'locations.location')
        ->get();

        return $books;
    }

    public function getBook($id) {
        $book = Location::join('books', 'locations.id', '=', 'books.location_id')
        ->select('books.id', 'books.call_number', 'books.title', 'books.author', 'books.date_published',
                'books.volume', 'books.edition', 'books.pages','books.remarks', 'books.copyright', 'locations.location')
        ->find($id);
        return $book;
    }

    public function image($id) {
        $book = Book::find($id);

        // check if it has image
        if($book->image_location == null)
            return response('', 200);

        $image = 'app/public/' . $book->image_location;
        $path = storage_path($image);
        return response()->file($path);
    }

    public function add(Request $request) {
        // Validate image
        $request->validate([
            'image_location' => 'required|image|mimes:jpeg,png,jpg|max:4096', // Adjust the max size as needed
        ]);

        $model = new Book();
        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response('Error: Invalid form request. Check values if on correct data format.', 400);
        }

        $ext = $request->file('image_location')->extension();

        // Check file extension and raise error
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
            return response('Error: Invalid image format. Only PNG, JPG, and JPEG formats are allowed.', 415);
        }

        // Store image and save path
        $path = $request->file('image_location')->store('images', 'public');

        $model->image_location = $path;
        $model->save();

        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {
        // return response($request);
        $model = Book::findOrFail($id);
        // return response($model);

        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response('Error: Invalid form request. Check values if on correct data format.', 400);
        }

        if(!empty($request->image_location)) {
            $ext = $request->file('image_location')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response('Error: Invalid image format. Only PNG, JPG, and JPEG formats are allowed.', 415);
            }

            // Store image and save path
            $path = $request->file('image_location')->store('images', 'public');

            $model->image_location = $path;
        }

        $model->save();

        return response()->json($model, 200);
    }

    public function delete($id) {
        $model = Book::find($id);
        $model->delete();

        return response()->json('Record Deleted', 200);
    }
}
