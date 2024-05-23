<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Exception;
use Auth;
use DB, Http, Str;

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
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            /* 
            NOTE:
                Roles should be in ['superadmin', 'admin', 'staff', 'user']
                Input your subsystem to control login
            */

            $user = Auth::user();

            // Generate token for superadmin and admin only
            if($user->role == 'superadmin' && $subsystem == 'maintenance') {

                $token = $user->createToken('token-name', ['materials-edit', 'materials-read'])->plainTextToken;
                
                $responseData = [
                    'token' => $token,
                    'id' => $user->id,
                    'displayName' => $user->first_name . ' ' . $user->last_name,
                    'role' => $user->role
                ];

                return response()->json($responseData, 200);

            } else if(in_array($user->role, ['superadmin', 'cataloging', 'frontdesk', 'opac']) && in_array($subsystem, ['cataloging', 'circulation', 'opac','locker'])) {
                
                if($user->role == 'cataloging') {
                    $token = $user->createToken('token-name', ['cataloging', 'materials-read'])->plainTextToken;
                }
                    
                else if($user->role == 'frontdesk')
                    $token = $user->createToken('token-name', ['materials:read', 'system:circulation'])->plainTextToken;
                else if($user->role == 'opac')
                    $token = $user->createToken('token-name', ['system:opac'])->plainTextToken;
                else {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }

                // sets expiry time
                $tokenModel = $user->tokens->last();
                $expiryTime = now()->addHour();
                $tokenModel->update(['expires_at' => $expiryTime]);
                
                $responseData = [
                    'token' => $token,
                    'id' => $user->id,
                    'displayName' => $user->first_name . ' ' . $user->last_name,
                    'role' => $user->position
                ];

                return response()->json($responseData, 200);

            } else if(in_array($user->role, ['user']) && in_array($subsystem, ['student'])) {
                
                $token = $user->createToken('token-name', ['materials:view'])->plainTextToken;

                $student = User::with('program')->find($user->id);
                $responseData = [
                    'token' => $token,
                    'id' => $user->id,
                    'department' => $student->program->department,
                    'role' => $user->role,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'middle_name' => $user->middle_name,
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



    
    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user], 200);
    }
}