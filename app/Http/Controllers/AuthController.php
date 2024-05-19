<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Auth;
use DB, Http, Str, Hash;

class AuthController extends Controller
{

    // public function studentLogin(Request $request) {
    //     $auth_url = 'http://127.0.0.1:8001/api/login';
    //     $details = Http::get($auth_url)->json();
    //     return response()->json($details, 200);
    // }

    public function studentLogin(Request $request)
    {

        // Get credentials from the request
        $credentials = $request->only('username', 'password');

        // Send credentials to the external authentication API
        $response = Http::post('http://127.0.0.1:8001/api/login', $credentials);

        // Check if the authentication was successful
        if ($response->successful()) {
            // Extract user data from the response
            $userData = $response->json();

            // Generate a random API token
            $apiToken = Str::random(80); // Adjust the length as needed

            // Hash the token before storing it
            $hashedToken = Hash::make($apiToken);

            $expiryTime = now()->addHour();
            $now = now();

            DB::table('personal_access_tokens')->insert([
                'tokenable_type' => 'StudentUser',
                'name' => 'student-token',
                'tokenable_id' => null,
                'token' => $hashedToken,
                'expires_at' => $expiryTime,
                'updated_at' => $now,
                'created_at' => $now
            ]);

            // Return the token to the client
            return response()->json([
                'token' => $apiToken,
                'first_name' => $userData['details']['first_name'],
                'last_name' => $userData['details']['last_name'],
                'student_number' => $userData['details']['student_number']
            ], 200);
        } else {
            // Return error response if authentication failed
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

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
                    'displayName' => $user->first_name . ' ' . $user->last_name,
                    'role' => $user->role
                ];

                return response()->json($responseData, 200);

            } else if(in_array($user->role, ['superadmin', 'admin']) && in_array($subsystem, ['cataloging', 'circulation', 'opac'])) {

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
                    'role' => $user->role,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'domain_account' => $user->domain_email,
                    'main_address' => $user->main_address,
                    'profile_picture' => $user->profile_image,


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