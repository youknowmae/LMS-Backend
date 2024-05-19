<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Exception;
use Auth;

class AuthController extends Controller
{

    public function login(Request $request, string $subsystem) {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            
            $user = Auth::user();
    
            if ($user) {
                $token = $user->createToken('token-name')->plainTextToken;
                $responseData = [
                    'token' => $token,
                    'id' => $user->id,
                    'department' => $user->department,
                    'role' => $user->role,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'middle_name' => $user->middle_name,
                    'domain_account' => $user->domain_email,
                    'main_address' => $user->main_address,
                    'profile_picture' => $user->profile_image,


                ];
    
                if ($user->role == 'superadmin' && $subsystem == 'maintenance') {
                    return response()->json($responseData, 200);
                } elseif (in_array($user->role, ['superadmin', 'admin']) && in_array($subsystem, ['cataloging', 'circulation'])) {
                    return response()->json($responseData, 200);
                } elseif (in_array($user->role, ['user']) && in_array($subsystem, ['student'])) {
                    return response()->json($responseData, 200);
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } else {
                return response()->json(['message' => 'User not found'], 404);
            }
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request) {
        try {
            auth()->user()->tokens()->delete();
            return response()->json(['Status' => 'Logged out successfully'], 200);
        } catch(Exception $e) {
            return response()->json(['Error' => $e->getMessage()], 400);
        }
    }



    
    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user], 200);
    }
}