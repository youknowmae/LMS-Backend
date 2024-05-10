<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereNot('role', 'superadmin')->get();
        return response()->json(['users'=>$users]);
        // return response()->json(['Response' => 'User Controller']); // This line will not be executed if the exception is thrown

    }

    public function store(Request $request)
    {   
        
        $user = User::create([
            'username' => $request->username,
            'patron_id' => $request->patron_id,
            'role' => 'admin',
            'department' => $request->department,
            'position' => $request->position,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,

        ]);

        return response()->json([
            'message'=> 'User Created',
            'data'=> $user
        ]);
    }

    public function update(User $user, Request $request)
    {
        $user->update([
            'patron_id' => $request->patron_id,
            'role' => 'admin',
            'department' => $request->department,
            'position' => $request->position,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,

        ]);
        return response()->json([
            'message'=> 'User Updated',
            'data'=> $user->fresh()
        ]);
    }

    public function delete(User $user, Request $request)
    {
        $user->delete();

        return response()->json([
            'message'=> 'User Delete',
            'data'=> []
        ]);
    }
}
