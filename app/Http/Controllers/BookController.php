<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use App\Models\Location;
use Storage;

class BookController extends Controller
{
    // Get functions 
    public function getLocations() {
        return Location::all();
    }

    public function getBooks() {        
        $books = Book::with('location')->orderByDesc('updated_at')->get();
        
        foreach($books as $book) {
            if($book->image_location != null)
                $book->image_location = 'http://localhost:8000' . Storage::url($book->image_location);
        }
        return $books;
    }

    public function getBook($id) {
        $book = Book::with('location')->findOrFail($id);
        return $book;
    }

    public function add(Request $request) {

        $request->validate([
            'id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'copyright' => 'required|integer|min:1900|max:'.date('Y'),
            'volume' => 'nullable|integer',
            'edition' => 'nullable|integer',
            'pages' => 'required|integer',
            'acquired_date' => 'required|date',
            'source_of_fund' => 'required|string',
            'price' => 'nullable|numeric',
            'location_id' => 'required|integer',
            'call_number' => 'required|string|max:50',
            'copies' => 'required|integer|min:1|max:20',
            'remarks' => 'nullable|string|max:512',
            'image_location' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ]);

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
                    $path = $request->file('image_location')->store('public/images/books');

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

        $log->add($request->user()->id, 'Added', $title, 'book', $location);
        
        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {
        
        $request->validate([
            'id' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'copyright' => 'nullable|integer|min:1900|max:'.date('Y'),
            'volume' => 'nullable|integer',
            'edition' => 'nullable|integer',
            'pages' => 'nullable|integer',
            'acquired_date' => 'nullable|date',
            'source_of_fund' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location_id' => 'nullable|integer',
            'call_number' => 'nullable|string|max:50',
            'copies' => 'nullable|integer|min:1|max:20',
            'remarks' => 'nullable|string|max:512',
            'image_location' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ]);

        $model = Book::findOrFail($id);

        try {
            $model->fill($request->except('image_location'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.'], 400);
        }

        if(!empty($request->image_location)) {
            $ext = $request->file('image_location')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $books = Book::withTrashed()->where('image_location', '=', $model->image_location)->count();

                if(!empty($model->image_location) && $books == 1) {
                    
                    $image = new ImageController();
                    $image->delete($model->image_location);
                }
                
                $path = $request->file('image_location')->store('public/images/books');
                $model->image_location = $path;

            } catch (Exception $e) {
                // add function
            }
        }

        $model->save();
        
        $location = Location::where('id', $model->location_id)->value('location');

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Updated', $model->title, 'book', $location);

        return response()->json($model, 200);
    }

    public function delete(Request $request, $id) {
        $model = Book::findOrFail($id);
        $books = Book::withTrashed()->where('image_location', '=', $model->image_location)->count();

        if(!empty($model->image_location) && $books == 1) {
            
            $image = new ImageController();
            $image->delete($model->image_location);
        }
        $model->delete();

        $location = Location::where('id', $model->location_id)->value('location');
        
        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, 'book', $location);

        return response()->json(['Response' => $books], 200);
    }
}

