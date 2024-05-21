<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', '<>', 'superadmin')->get();
        return response()->json(['users' => $users]);
    }

    public function show(int $personnel)
    {
        $user = User::findorfail($personnel);
        return $user;
    }

    public function store(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'username' => 'required|unique:users',
            // 'patron_id' => 'required',
            // 'department' => 'required',
            // 'position' => 'required',
            'password' => 'required',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'ext_name' => 'nullable',
            'access' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::create([
            'username' => $request->username,
            'patron_id' => 1,  //kung ano nalang applicaton
            'role' => 'admin',
            'department' => $request->department,
            'position' => $request->position,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,
            'access' => $request->access,
        ]);

        return response()->json([
            'message'=> 'User created successfully',
            //'data'=> $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            // 'patron_id' => 'required|unique:users,patron_id,'.$user->id,
            // 'department' => 'required',
            // 'position' => 'required',
            'password' => 'nullable',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'ext_name' => 'nullable',
            'access' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user->update([
            // 'patron_id' => $request->patron_id,
            // 'role' => 'admin',
            // 'department' => $request->department,
            // 'position' => $request->position,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,
            'access' => $request->access
        ]);

        return response()->json([
            'message'=> 'User updated successfully',
            'data'=> $user->fresh()
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message'=> 'User deleted successfully',
        ]);
    }
}
