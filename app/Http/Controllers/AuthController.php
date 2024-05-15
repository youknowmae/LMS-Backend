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

    public function updateProfile(Request $request, $id)
{
    try {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Log the request data for debugging
        \Log::info('Request data:', $request->all());

        // Validate the request data
        $validatedData = $request->validate([
            'main_address' => 'sometimes|string|max:255',
            'old_password' => 'nullable|string|min:2',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'domain_email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            // Add any other validation rules...
        ]);

        // Log the validated data for debugging
        \Log::info('Validated data:', $validatedData);

        // Update main address if provided
        if ($request->filled('main_address')) {
            $user->main_address = $validatedData['main_address'];
        }

        // Log the updated main address for debugging
        \Log::info('Main address updated:', ['main_address' => $user->main_address]);

        // Update password if provided
        if ($request->filled('old_password') && $request->filled('password')) {
            // Verify if the provided old password matches the user's current password
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return response()->json(['error' => 'The old password is incorrect.'], 422);
            }

            // Update the password with the new hashed password
            $user->password = Hash::make($validatedData['password']);

            // Log the password update for debugging
            \Log::info('Password updated.');
        }

        // Update profile image if provided
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $fileName = time() . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->move(public_path('profile_images'), $fileName);
            $user->profile_image = $fileName;

            // Log the profile image update for debugging
            \Log::info('Profile image updated:', ['profile_image' => $user->profile_image]);
        }

        // Update domain email if provided
        if ($request->filled('domain_email')) {
            $user->domain_email = $validatedData['domain_email'];

            // Log the domain email update for debugging
            \Log::info('Domain email updated:', ['domain_email' => $user->domain_email]);
        }

        // Save the updated user
        $user->save();

        // Log the successful update for debugging
        \Log::info('Profile updated successfully.');

        // Return a success response
        return response()->json(['message' => 'Profile updated successfully'], 200);
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('An error occurred while updating the profile:', ['error' => $e->getMessage()]);

        // Return an error response
        return response()->json(['error' => 'An error occurred while updating the profile'], 500);
    }
}


    
    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user], 200);
    }
}