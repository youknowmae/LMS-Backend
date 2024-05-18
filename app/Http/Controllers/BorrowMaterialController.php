<?php

namespace App\Http\Controllers;
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
        $borrowMaterial->save();
        
    
     // Rollback the decrement operation if saving BorrowMaterial failed
        $book->available = 0;
        $book->save();
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
        $payload=json_decode($request->payload);

        // Create a new BorrowMaterial instance
        $borrowMaterial = new BorrowMaterial();
        $borrowMaterial->book_id = $payload->book_id;
        $borrowMaterial->user_id = $payload->user_id;
        $borrowMaterial->fine = $payload->fine;
        $borrowMaterial->borrow_expiration = $payload->borrow_expiration;
        $borrowMaterial->borrow_date = $payload->borrow_date;
        $borrowMaterial->save();
        $data = ['borrow_material' => $borrowMaterial];
        return response()->json($data);
    }



    public function userlist(Request $request){
        $users = User::with('program', 'department', 'patrons')->get();
        return response()->json($users, 200);
    }


    //return book
    public function returnbook(Request $request, $id){
        $borrowMaterial = BorrowMaterial::find($id);
        $book = Book::find($id);
            // Check if the borrowed material exists
        if(!$borrowMaterial){
            return response()->json(['message' => 'Borrowed material not found'], 404);
        }
            $borrowMaterial->status = 0;
            $book->available = 1;

            $borrowMaterial->date_returned = now();
        // Save the changes
        $book->save();
        $borrowMaterial->save();

        // Return a success response
        return response()->json(['message' => $id], 200);
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