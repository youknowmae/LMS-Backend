<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Book;
use App\Models\Inventory;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function getBookInventory($filter)
    {
        if(!in_array($filter, ['available', 'unreturned', 'missing', 'unlabeled'])){
            return response()->json(['error' => 'Page not found'], 404);
        }

        $books = Book::with('location')
                    ->where('status', $filter)
                    ->orderByDesc('created_at')
                    ->get();
        
        foreach($books as $book) {
            $book->authors = json_decode($book->authors);
        }

      

        return $books;
    }

    public function searchBookInventory(Request $request, $filter)
    {
        $search = $request->input('search', '');
        
        if(!in_array($filter, ['available', 'unreturned', 'missing', 'unlabeled'])){
            return response()->json(['error' => 'Page not found'], 404);
        }

        $books = Book::with('location')
                    ->where('status', $filter)
                    ->where('call_number', 'LIKE', "%" . $search . "%")
                    ->orderByDesc('created_at')
                    ->get();
        
        foreach($books as $book) {
            $book->authors = json_decode($book->authors);
        }

      

        return $books;
    }

    public function updateBookStatus(Request $request, $id) {
        $data = Validator::make($request->all(), [
            'status' => 'required|in:available,unreturned,missing,unlabeled'
        ]);

        if($data->fails()) {
            return response()->json(['errors', $data->errors()], 400);
        }

        $book = Book::findorfail($id);
        $book->update($data->validated());
        $book->save();

        return response()->json(['success' => 'Item has been updated.'], 200);
    }

    public function clearBooksHistory() {
        Book::where('status', 'available')->update(['status' => 'unlabeled']);
        
        return response()->json(['success' => 'History has been cleared.'], 200);
    }
}