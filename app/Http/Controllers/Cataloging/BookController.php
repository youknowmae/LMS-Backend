<?php

namespace App\Http\Controllers\Cataloging;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Http\Controllers\ImageController;
use Exception;
use Illuminate\Http\Request;
use App\Models\Location;
use Storage, Str, DB;

class BookController extends Controller
{
    // const URL = 'http://26.68.32.39:8000';
    const URL = 'http://127.0.0.1:8000';

    public function getLocations() {
        return Location::all();
    }

    public function getBooks() {        
        $books = Material::where('material_type', 0)->orderByDesc('updated_at')
        ->get(['accession', 'title', 'authors', 'location', 'copyright']);

        foreach($books as $book) {
            $book->authors = json_decode($book->authors);
        }
    
        return $books;
    }

    public function getBook($id) {
        $book = Material::where('accession', $id)->firstOrFail();
        $book->authors = json_decode($book->authors);
        if($book->image_url != null)
            $book->image_url = self::URL . Storage::url($book->image_url);

        return $book;
    }

    // FOR STUDENT PORTAL
    public function viewBooks() {
        $books = Material::with('location')->orderByDesc('updated_at')->get();
        
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
        $book = Material::find($id, ['available', 'title', 'id', 'call_number', 'copyright', 'price', 'authors',
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
        $books = Material::where('title', 'LIKE', "%{$query}%")->get();

        // Return the results as a JSON response
        return response()->json($books);
    }

    /* PROCESSING OF DATA */

    public function add(Request $request) {

        $request->validate([
            'accession' => 'nullable|string|max:20',
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            'copyright' => 'required|integer|min:1901|max:'.date('Y'),
            'volume' => 'nullable|string',
            'edition' => 'nullable|string',
            'pages' => 'required|integer',
            'acquired_date' => 'required|date',
            'source_of_fund' => 'required|integer',
            'price' => 'nullable|numeric',
            'location' => 'required|string',
            'call_number' => 'required|string|max:50',
            'remarks' => 'nullable|string|max:512',
            'image_url' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ]);

        if($request->copies < 1) {
            return response('Error: Invalid number of copies', 400);
        } else {
            for($i = 0; $i < $request->copies; $i++) {

                $model = new Material();
                try {
                    
                    $model->fill($request->except(['id', 'image_url']));
                    $model->material_type = 0;
                    
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


        // $location = Location::where('id', $model->location_id)->value('location');

        // $log = new CatalogingLogController();

        // if($request->copies == 1)
        //     $title = $model->title;
        // else
        //     $title = $model->title . ' (' . $request->copies . ')';

        // $log->add($request->user()->id, 'Added', $title, 'book', $location);
        
        return response()->json($model, 201);
    }

    public function update(Request $request, $id) {
        
        $request->validate([
            'accession' => 'nullable|string|max:20',
            'title' => 'nullable|string|max:255',
            'authors' => 'nullable|string|max:255',
            'copyright' => 'nullable|integer|min:1901|max:'.date('Y'),
            'volume' => 'nullable|string',
            'edition' => 'nullable|string',
            'pages' => 'nullable|integer',
            'acquired_date' => 'nullable|date',
            'source_of_fund' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string',
            'call_number' => 'nullable|string|max:50',
            'remarks' => 'nullable|string|max:512',
            'image_url' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ]);

        $model = Material::where('accession', $id)->firstOrFail();

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
                $books = Material::where('image_url', '=', $model->image_url)->count();

                if(!empty($model->image_url) && $books == 1) {
                    
                    $image = new ImageController();
                    $image->delete($model->image_url);
                }
                
                $path = $request->file('image_url')->store('public/images/books');
                $model->update(['image_url' => $path]);

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

        return response()->json($model, 200);
    }

    //opac
    public function opacGetBooks(Request $request) {        
        $sort = $request->input('sort', 'acquired_date desc'); 
    
        $sort = $this->validateSort($sort);
        
        if ($sort[0] === 'date_published') {
            $sort[0] = 'acquired_date';
        }
    
        $books = Material::select('id', 'call_number', 'title', 'acquired_date', 'authors', 'image_url')
                     ->orderBy($sort[0], $sort[1])
                     ->paginate(24);
    
        foreach ($books as $book) {
            $book->authors = json_decode($book->authors);
            if ($book->image_url != null) {
                $book->image_url = self::URL . Storage::url($book->image_url);
            }
        }
        
        return $books;
    }

    public function opacGetBook($id) {
        $book = Material::select('title', 'location', 'call_number', 'copyright', 'authors', 'acquired_date', 'volume', 'pages', 'edition', 'remarks', 'status')
                        ->findOrFail($id);

        $book->authors = json_decode($book->authors);
        if($book->image_url != null)
            $book->image_url = self::URL . Storage::url($book->image_url);

        return $book;
    }
    
    public function opacSearchBooks(Request $request){  
        $search = $request->input('search');
        $sort = $request->input('sort', 'acquired_date desc');

        $sort = $this->validateSort($sort);
        
        if($sort[0] === 'date_published') {
            $sort[0] = 'acquired_date';
        }

        $books = Material::select('id', 'call_number', 'title', 'acquired_date', 'authors', 'image_url')
                    ->where('title', 'like', '%' . $search . "%")
                    ->orWhere('authors', 'like', '%' . $search . "%")
                    ->orWhere('call_number', 'like', '%' . $search . "%")
                    ->orderBy($sort[0], $sort[1])
                    ->paginate(24);

        foreach($books as $book) {
            if($book->image_url != null)
                $book->image_url = self::URL . Storage::url($book->image_url);

            $book->authors = json_decode($book->authors);
        }

        return $books;
    } 
}

