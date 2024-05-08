<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Exception;
use Auth;
use DB;

class AuthController extends Controller
{

    public function user(Request $request) {
        return $request->user();
    }

    public function login(Request $request, string $subsystem) {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

            /* 
            NOTE:
                Roles should be in ['superadmin', 'admin', 'staff', 'user']
                Input your subsystem to control login
            */

            $user = Auth::user();

            // Generate token for superadmin and admin only
            if($user->role == 'superadmin' && $subsystem == 'maintenance') {

                $token = $user->createToken('token-name', ['materials:edit', 'materials:read'])->plainTextToken;
                
                $responseData = [
                    'token' => $token,
                    'id' => $user->id,
                    'displayName' => $user->first_name + $user->last_name,
                    'role' => $user->role
                ];

                return response()->json($responseData, 200);

            } else if(in_array($user->role, ['superadmin', 'admin']) && in_array($subsystem, ['cataloging', 'circulation'])) {

                $token = $user->createToken('token-name', ['materials:edit', 'materials:read'])->plainTextToken;

                // sets expiry time
                $tokenModel = $user->tokens->last();
                $expiryTime = now()->addHour();
                $tokenModel->update(['expires_at' => $expiryTime]);
                
                $responseData = [
                    'token' => $token,
                    'id' => $user->id,
                    'displayName' => $user->first_name . ' ' . $user->last_name,
                    'role' => $user->role
                ];

                return response()->json($responseData, 200);

            } else if(in_array($user->role, ['user']) && in_array($subsystem, ['student'])) {
                
                $token = $user->createToken('token-name', ['materials:view'])->plainTextToken;

                $responseData = [
                    'token' => $token,
                    'id' => $user->id,
                    'department' => $user->department,
                    'role' => $user->role
                ];

                return response()->json($responseData, 200);

            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function refreshToken(Request $request) {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        if(in_array($user->role, ['superadmin', 'admin']))
            $token = $user->createToken('token-name', ['materials:edit', 'materials:read'])->plainTextToken;

        // sets expiry time
        $tokenModel = $user->tokens->last();
        $expiryTime = now()->addHour();
        $tokenModel->update(['expires_at' => $expiryTime]);

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request) {
        try {
            auth()->user()->tokens()->delete();
            return response()->json(['Status' => 'Logged out successfully'], 200);
        } catch(Exception $e) {
            return response()->json(['Error' => $e->getMessage()], 400);
        }
    }
}
