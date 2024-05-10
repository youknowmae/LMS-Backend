<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereNot('role', 'superadmin')->get();
        return response()->json(['users'=>$users]);
        // return response()->json(['Response' => 'User Controller']); // This line will not be executed if the exception is thrown

    }
}
