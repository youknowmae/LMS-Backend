<?php

namespace App\Http\Controllers;
use App\Models\BorrowMaterial;
use App\Models\BorrowBook;
use Illuminate\Http\Request;

use App\Models\User; // Import the User model

class BorrowMaterialController extends Controller
{
    //borrow function
    public function request(Request $request)
    {
        $type = $request->input('type'); 
        switch ($type) 
        {
            case 'book':
               
                $borrowBookController = new BorrowBookController();
                return $borrowBookController->borrowBook($request);
                break;

            case 'project':
                
                break;

            case 'article':
                
                break;

            default:
                
                break;
        }
    }

    

    //to get user list
    public function userlist()
    {
        // Retrieve from the users table
        $users = User::select('username', 'course_id', 'department')->get();

        
        return $users;
    }
}
