<?php

namespace App\Http\Controllers;
use App\Models\BorrowMaterial;
use App\Models\BorrowBook;
use Illuminate\Http\Request;

use App\Models\User; // Import the User model

class BorrowMaterialController extends Controller
{

    
    // user list array data
    private $users = [
        ['username' => 'John Doe', 'student_id' => '202110111', 'course_id' => 'CS101', 'department' => 'Computer Science'],
        ['username' => 'Jane Smith', 'student_id' => '202110123', 'course_id' => 'ENG201', 'department' => 'English Literature'],
        ['username' => 'Ehdrian Lim', 'student_id' => '202110134', 'course_id' => 'ENG201', 'department' => 'English Literature'],
    ];

    // check if user exists in the array
    public function checkUserExists($userId)
    {
        foreach ($this->users as $user) {
            if ($user['student_id'] == $userId) {
                return true;
            }
        }
        return false;
    }


    //get user list
    public function userlist()
    {
        return response()->json($this->users, 200);
    }
}
