<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request, string $subsystem)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            $access = [];
            if ($user->role == 'superadmin' && $subsystem == 'maintenance') {
                $access = ['superadmin', 'admin', 'staff', 'user'];
            } elseif (in_array($user->role, ['superadmin', 'admin']) && in_array($subsystem, ['cataloging', 'circulation'])) {
                $access = ['superadmin', 'admin'];
            } elseif ($user->role == 'user' && $subsystem == 'student') {
                $access = ['user'];
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Update user's access
            $user->access = $access;
            $user->save();

            // Generate token with permissions
            $permissions = [];
            if (in_array('superadmin', $access) || in_array('admin', $access)) {
                $permissions = ['materials:edit', 'materials:view'];
            } elseif (in_array('user', $access)) {
                $permissions = ['materials:view'];
            }

            $token = $user->createToken('token-name', $permissions)->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['Status' => 'Logged out successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['Error' => $e->getMessage()], 400);
        }
    }
}

