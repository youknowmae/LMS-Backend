<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use App\Models\Location;

class BookController extends Controller
{
    // Get functions 
    public function getLocations() {
        return Location::all();
    }

    public function getBooks() {        
        $books = Book::with('location')->get();

        return $books;
    }

    public function getBook($id) {
        $book = Book::with('location')->findOrFail($id);
        return $book;
    }

    public function image($id) {
        $material = Book::find($id);

        // check if it has no image
        if($material->image_location == null)
            return response()->json(['Response' => 'No Image Found'], 200);

        $image = 'app/' . $material->image_location;
        $path = storage_path($image);
        try {
            return response()->file($path);
        } catch (Exception $e) {
            return response()->json(['Status' => 'File not found'], 404);
        }
    }

    public function add(Request $request) {

        if($request->copies < 1) {
            return response('Error: Invalid number of copies', 400);
        } else {
            for($i = 0; $i < $request->copies; $i++) {

                $model = new Book();
                try {
                    
                    $model->fill($request->except(['id', 'image_location']));

                    // get id if request has an id
                    if($i > 0 && $request->id != null) {

                        $model->id = $request->id + $i;
                    } else if($i == 0 && $request->id != null) {

                        $model->id = $request->id;
                    }

                } catch (Exception) {
                    return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.', 400]);
                }

                if($request->image_location != null) {
                    $ext = $request->file('image_location')->extension();

                    // Check file extension and raise error
                    if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                        return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
                    }

                    // Store image and save path
                    $path = $request->file('image_location')->store('images/books');

                    $model->image_location = $path;
                } 
                $model->save();
            }
        }

        $location = Location::where('id', $model->location_id)->value('location');

        $log = new CatalogingLogController();

        if($request->copies == 1)
            $title = $model->title;
        else
            $title = $model->title . ' (' . $request->copies . ')';

        $log->add('Added', $title, 'book', $location);
        
        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {
        
        $model = Book::findOrFail($id);

        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.', 400]);
        }

        if(!empty($request->image_location)) {
            $ext = $request->file('image_location')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $temp = $model->image_location;
                $path = $request->file('image_location')->store('images/books');
                $model->image_location = $path;

                if(!empty($temp)) {
                    $image = new ImageController();
                    $image->delete($temp);
                }
            } catch (Exception $e) {
                // add function
            }
        }

        $model->save();
        
        $location = Location::where('id', $model->location_id)->value('location');

        $log = new CatalogingLogController();
        $log->add('Updated', $model->title, 'book', $location);

        return response()->json($model, 200);
    }

    public function delete($id) {
        $model = Book::findOrFail($id);

        if(!empty($model->image_location)) {
            $image = new ImageController();
            $image->delete($model->image_location);
        }
        $model->delete();

        $location = Location::where('id', $model->location_id)->value('location');
        
        $log = new CatalogingLogController();
        $log->add('Deleted', $model->title, 'book', $location);

        return response()->json(['Response' => 'Record Deleted'], 200);
    }
}
