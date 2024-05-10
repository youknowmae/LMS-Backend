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
    public function show(User $user, Request $request)
    {
        return response()->json(['user'=>$user]);
        // return response()->json(['Response' => 'User Controller']); // This line will not be executed if the exception is thrown

    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'patron_id' => 'required|unique:users',
            'department' => 'required',
            'position' => 'required',
            'password' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'ext_name' => 'nullable',
        ]);

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
            'message'=> 'User created successfully',
            'data'=> $user
        ]);
    }

    public function update(User $user, Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,'.$user->id,
            'patron_id' => 'required|unique:users,patron_id,'.$user->id,
            'department' => 'required',
            'position' => 'required',
            'password' => 'nullable',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'ext_name' => 'nullable',
        ]);

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
            'message'=> 'User updated successfully',
            'data'=> $user->fresh()
        ]);
    }

    public function destroy(User $user, Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message'=> 'User deleted successfully',
        ]);
    }
}
