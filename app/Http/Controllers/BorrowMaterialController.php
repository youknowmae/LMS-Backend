<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\BorrowMaterial;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use Exeption;
use Storage;


class BorrowMaterialController extends Controller
{
    public function borrowbook(Request $request)
    {
        $payload=json_decode($request->payload);

        // Check if the book_id exists in the books table
        $book = Book::find($payload->book_id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        // Check if the book is available
        if ($book->available  == 0) {
            return response()->json(['error' => 'Book is not available for borrowing'], 400);
        }

        // Create a new BorrowMaterial instance
        $borrowMaterial = new BorrowMaterial();
        $borrowMaterial->book_id = $payload->book_id;
        $borrowMaterial->user_id = $payload->user_id;
        $borrowMaterial->fine = $payload->fine;
        $borrowMaterial->borrow_expiration = $payload->borrow_expiration;
        $borrowMaterial->borrow_date = $payload->borrow_date;
        
    
     // Rollback the decrement operation if saving BorrowMaterial failed
        $book->available = 0;
        $book->save();
        $borrowMaterial->save();
        $data = ['borrow_material' => $borrowMaterial];
        return response()->json($data);
    }

    public function borrowlist(Request $request){
        $borrowMaterial = BorrowMaterial::with(['user.program', 'user.department', 'user.patrons'])
                            ->whereHas('user', function($query) {
                                $query->where('status', 1);
                            })
                            ->get();
        return response()->json($borrowMaterial); 
    }

    public function returnedlist(Request $request){
        $borrowMaterial = BorrowMaterial::with(['user.program', 'user.department', 'user.patrons'])
                            ->whereHas('user', function($query){
                                $query->where('status', 0);
                            })
                            ->get();
        return response()->json($borrowMaterial);
    }

    public function borrowEdit(Request $request)
{
    $payload = json_decode($request->payload);
  
    $borrowMaterial = BorrowMaterial::find($payload->id);
    
    if (!$borrowMaterial) {
        return response()->json(['error' => 'Borrow material not found'], 404);
    }

    $borrowMaterial->book_id = $payload->book_id;
    $borrowMaterial->user_id = $payload->user_id;
    $borrowMaterial->fine = $payload->fine;
    $borrowMaterial->borrow_expiration = $payload->borrow_expiration;
    $borrowMaterial->borrow_date = $payload->borrow_date;

    // Save the updated record
    $borrowMaterial->save();

    // Return the updated record as a response
    $data = ['borrow_material' => $borrowMaterial];
    return response()->json($data);
}

    public function userlist(Request $request){
        $users = User::with('program', 'department', 'patrons')->get();
        return response()->json($users, 200);
    }


    //return book

    public function returnbook(Request $request, $id)
    {
        // Start a database transaction
        DB::beginTransaction();
    
        try {
            // Find the BorrowMaterial by its ID
            $borrowMaterial = BorrowMaterial::find($id);
            
            // Check if the borrowed material exists
            if (!$borrowMaterial) {
                // Rollback the transaction if the borrowed material is not found
                DB::rollback();
                return response()->json(['message' => 'Borrowed material not found'], 404);
            }
    
            // Find the Book by the book_id associated with the BorrowMaterial
            $book = Book::find($borrowMaterial->book_id);
            
            // Set book availability to 1 (available)
            $book->available = 1;
    
            // Save the changes to the Book
            $book->save();
    
            // Update BorrowMaterial status and return date
            $borrowMaterial->status = 0;
            $borrowMaterial->date_returned = now();
            
            // Save the changes to the BorrowMaterial
            $borrowMaterial->save();
    
            // Commit the transaction if all operations succeed
            DB::commit();
    
            // Return a success response
            return response()->json(['message' => 'Book returned successfully'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if any operation fails
            DB::rollback();
    
            // Handle the exception, e.g., log the error or return an error response
            return response()->json(['message' => 'An error occurred while returning the book'], 500);
        }
    }
    

    public function bookBorrowersReport(Request $request){
        // Fetch distinct borrowers with their user and program relations
        $borrowers = BorrowMaterial::with(['user.program'])
            ->select('user_id')
            ->distinct()
            ->get();
    
        // Initialize arrays to store counts
        $borrowersByDepartment = [];
        $borrowersByGender = [
            'Male' => 0,
            'Female' => 0
        ];
    
        // Process each borrower to populate the counts
        foreach ($borrowers as $borrow) {
            $user = $borrow->user;
            $program = $user->program;
    
            // Increment count by department
            if ($program) {
                $department = $program->department;
                if (isset($borrowersByDepartment[$department])) {
                    $borrowersByDepartment[$department]++;
                } else {
                    $borrowersByDepartment[$department] = 1;
                }
            }
    
            // Increment count by gender
            $gender = $user->gender;
            if (isset($borrowersByGender[$gender])) {
                $borrowersByGender[$gender]++;
            } else {
                $borrowersByGender[$gender] = 1;
            }
        }
    
        // Return the response as JSON
        return response()->json([
            'borrowersByDepartment' => $borrowersByDepartment,
            'borrowersByGender' => $borrowersByGender
        ]);
    }

    public function mostBorrowed(Request $request){
        $mostBorrowedBooks = BorrowMaterial::select('book_id', DB::raw('COUNT(*) as borrow_count'))
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->get();
    
        return response()->json($mostBorrowedBooks);
    }
        
    public function topborrowers(Request $request){
        $topborrowers = BorrowMaterial::select(
            'user_id',
            DB::raw('COUNT(*) as borrow_count'),
            'users.last_name'
        )
        ->join('users', 'borrow_materials.user_id', '=', 'users.id')
        ->groupBy('borrow_materials.user_id', 'users.last_name')
        ->orderByDesc('borrow_count')
        ->get();

        return response()->json($topborrowers,200);
    }

}


//edited out
        // // Create a new BorrowBook instance
        // $borrowBook = new BorrowBook();
        // $borrowBook->request_id = $borrowMaterial->id; // Set the request_id
        // $borrowBook->book_id = $request->book_id; // Save the book_id
    
        // // Save the BorrowBook instance
        // $borrowBook->save();
    
        // Check if the BorrowBook was saved successfully
        // if (!$borrowBook->id) {
        //     return response()->json(['error' => 'Failed to create borrow book'], 500);
        // }
    

        // Update the availability of the book
        // $book->available -= 1;
        // $book->save(); // Save the updated book

        
        // public function bookBorrowersReport(Request $request){
        // $borrowers = BorrowMaterial::with('user.program')
        //     ->select('user_id')
        //     ->distinct()
        //     ->get();

        // $borrowersByDepartment = $borrowers->groupBy('user.program.department');
        // $borrowersByGender = $borrowers->groupBy('user.gender');

        // return response()->json([
        //     'borrowersByDepartment' => $borrowersByDepartment,
        //     'borrowersByGender' => $borrowersByGender
        // ]);
        // }