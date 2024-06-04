<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Material;
use Storage, Str;

class BookController extends Controller
{
    const URL = 'http://26.68.32.39:8000';
    public function getLocations() {
        return Location::all();
    }

    public function getBooks() {        
        $books = Book::with('location')->orderByDesc('updated_at')->get();
        
        foreach($books as $book) {
            if($book->image_url != null)
                $book->image_url = self::URL . Storage::url($book->image_url);

            $book->authors = json_decode($book->authors);
        }
        return $books;
    }

    public function getBook($id) {
        $book = Book::with('location')->findOrFail($id);
        $book->authors = json_decode($book->authors);
        if($book->image_url != null)
            $book->image_url = self::URL . Storage::url($book->image_url);

        return $book;
    }

    // FOR STUDENT PORTAL
    public function viewBooks() {
        $books = Book::with('location')->orderByDesc('updated_at')->get();
        
        $books_array = [];
        foreach($books as $book) {
            $image_url = null;
            if($book->image_url != null)
                $image_url = self::URL .  Storage::url($book->image_url);

            array_push($books_array, [
                'id' => $book->id,
                'image_url' => $image_url,
                'location' => $book->location->location,
                'full_location' => $book->location->full_location,
                'title' => $book->title,
                'authors' => json_decode($book->authors),
                'volume' => $book->volume,
                'edition' => $book->edition,
                'available' => $book->available,
                'copyright' => $book->copyright,
                'fine' => $book->fine
            ]);
        }
        return $books_array;
    }

    public function viewBook(int $id) {
        $book = Book::find($id, ['available', 'title', 'id', 'call_number', 'copyright', 'price', 'authors',
        'volume', 'pages', 'edition', 'remarks', 'image_url']);

        $book->authors = json_decode($book->authors);
        $book->image_url = self::URL . Storage::url($book->image_url);
        return $book;
    }

    // search BOOKS
    public function searchBooks(Request $request)
    {
        // Retrieve the query parameter from the request
        $query = $request->input('query');

        // Check if the query parameter is empty or not provided
        if(empty($query)) {
            // Return a response indicating that the query is required
            return response()->json(['message' => 'Please provide a search query.'], 400);
        }
        
        // Search for books where the title contains the query string
        $books = Book::where('title', 'LIKE', "%{$query}%")->get();

        // Return the results as a JSON response
        return response()->json($books);
    }

    /* PROCESSING OF DATA */

    public function add(Request $request) {

        $request->validate([
            'id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            'copyright' => 'required|integer|min:1900|max:'.date('Y'),
            'volume' => 'nullable|integer',
            'edition' => 'nullable|string',
            'pages' => 'required|integer',
            'acquired_date' => 'required|date',
            'source_of_fund' => 'required|string',
            'price' => 'nullable|numeric',
            'location_id' => 'required|integer',
            'call_number' => 'required|string|max:50',
            'copies' => 'required|integer|min:1|max:20',
            'remarks' => 'nullable|string|max:512',
            'image_url' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ]);

        if($request->copies < 1) {
            return response('Error: Invalid number of copies', 400);
        } else {
            for($i = 0; $i < $request->copies; $i++) {

                $model = new Book();
                try {
                    
                    $model->fill($request->except(['id', 'image_url']));

                    // get id if request has an id
                    if($i > 0 && $request->id != null) {

                        $model->id = $request->id + $i;
                    } else if($i == 0 && $request->id != null) {

                        $model->id = $request->id;
                    }

                } catch (Exception) {
                    return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.', 400]);
                }

                if($request->image_url != null) {
                    $ext = $request->file('image_url')->extension();

                    // Check file extension and raise error
                    if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                        return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
                    }

                    // Store image and save path
                    $path = $request->file('image_url')->store('public/images/books');

                    $model->image_url = $path;
                } 
                
                $authors = json_decode($request->authors, true);

                foreach($authors as &$author) {
                    $author = Str::title($author);
                }

                $model->authors = json_encode($authors);
                
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
            'authors' => 'nullable|string|max:255',
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
            'image_url' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ]);

        $model = Book::findOrFail($id);

        try {
            $model->fill($request->except('image_url', 'title', 'authors'));
        } catch (Exception) {
            return response()->json(['Error' => 'Invalid form request. Check values if on correct data format.'], 400);
        }

        if(!empty($request->image_url)) {
            $ext = $request->file('image_url')->extension();

            // Check file extension and raise error
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                return response()->json(['Error' => 'Invalid image format. Only PNG, JPG, and JPEG formats are allowed.'], 415);
            }

            // Store image and save path
            try {
                $books = Book::withTrashed()->where('image_url', '=', $model->image_url)->count();

                if(!empty($model->image_url) && $books == 1) {
                    
                    $image = new ImageController();
                    $image->delete($model->image_url);
                }
                
                $path = $request->file('image_url')->store('public/images/books');
                $model->image_url = $path;

            } catch (Exception $e) {
                // add function
            }
        }

        $model->title = Str::title($request->title);
        $authors = json_decode($request->authors, true);

        foreach($authors as &$author) {
            $author = Str::title($author);
        }

        $model->authors = json_encode($authors);

        $model->save();
        
        $location = Location::where('id', $model->location_id)->value('location');

        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Updated', $model->title, 'book', $location);

        return response()->json($model, 200);
    }

    public function delete(Request $request, $id) {
        $model = Book::findOrFail($id);
        $books = Book::withTrashed()->where('image_url', '=', $model->image_url)->count();

        if(!empty($model->image_url) && $books == 1) {
            
            $image = new ImageController();
            $image->delete($model->image_url);
        }
        $model->delete();

        $location = Location::where('id', $model->location_id)->value('location');
        
        $log = new CatalogingLogController();
        $log->add($request->user()->id, 'Archived', $model->title, 'book', $location);

        return response()->json(['Response' => $books], 200);
    }

    //opac
    public function opacGetBooks() {     
        $books = Material::select('accession', 'call_number', 'title', 'acquired_date', 'authors', 'image_url')
                    ->where('material_type', 0)
                    ->orderByDesc('date_published', 'desc')
                    ->paginate(24);
    
        foreach ($books as $book) {
            $book->authors = json_decode($book->authors);
            if ($book->image_url != null) {
                $book->image_url = self::URL . Storage::url($book->image_url);
            }
        }
        
        return $books;
    }
    
    public function opacSearchBooks(Request $request){  
        $search = $request->input('search');

        $books = Material::select('accession', 'call_number', 'title', 'acquired_date', 'authors', 'image_url')
                    ->where('material_type', 0)
                    ->where(function($query) use($search) {
                        $query->where('title', 'like', '%' . $search . "%")
                            ->orWhere('authors', 'like', '%' . $search . "%");
                    })
                    ->orderByDesc('date_published')
                    ->paginate(24);

        foreach($books as $book) {
            if($book->image_url != null)
                $book->image_url = self::URL . Storage::url($book->image_url);

            $book->authors = json_decode($book->authors);
        }

        return $books;
    } 
}

