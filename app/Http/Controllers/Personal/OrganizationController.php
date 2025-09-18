<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $personalId = auth('personal-api')->id(); 
        $personal = Personal::where('id', $personalId)->first();

        if (!$personal) {
            return response()->json([
                 'data' =>[
                'errors' => 'Personal record not found'
            ]], 404);
        }

        if ($request->filled('firstname')) {
            $personal->firstname = $request->firstname;
        }
        if ($request->filled('lastname')) {
            $personal->lastname = $request->lastname;
        }

        if ($request->hasFile('profile_picture')) {
            $folder = "profile_pictures/personal";
            $path = $request->file('profile_picture')->store($folder, 'public');
            if ($personal->profile_picture && \Storage::disk('public')->exists($personal->profile_picture)) {
                \Storage::disk('public')->delete($personal->profile_picture);
            }

            $personal->profile_picture = $path;
        }

        $personal->save();

        return response()->json([
            'data' =>[
            'message' => 'Profile updated successfully',
            'profile_picture_url' => $personal->profile_picture 
                ? asset('storage/'.$personal->profile_picture) 
                : null,
            'firstname' => $personal->firstname,
            'lastname' => $personal->lastname,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]]);
    }

    public function updateEmail(Request $request)
    {
        $personalId = auth('personal-api')->id();

        $request->validate([
            'email' => 'required|email',
        ]);

        $newEmail = $request->email;

        $existsInPersonals = Personal::where('email', $newEmail)
            ->where('id', '!=', $personalId)
            ->exists();

        // Check if email already exists in users table
        $existsInUsers = User::where('email', $newEmail)->exists();

        if ($existsInPersonals || $existsInUsers) {
            return response()->json([
                'data' => [
                    'errors' => 'Email already taken',
                ]
            ], 422);
        }

        $personal = Personal::find($personalId);

        if (!$personal) {
            return response()->json([
                'data' => [
                    'message' => 'Personal record not found'
                ]
            ], 404);
        }

        $personal->email = $newEmail;
        $personal->save();

        return response()->json([
            'data' => [
                'message' => 'Email updated successfully',
                'email' => $personal->email,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ]);
    }

    public function deactivateAccount(Request $request)
    {
        $personalId = auth('personal-api')->id();
        $personal = Personal::find($personalId);

        if (!$personal) {
            return response()->json([
                'data' => [
                    'message' => 'Personal record not found'
                ]
            ], 404);
        }

        // Update status
        $personal->deletestatus = 'deactivated';
        $personal->save();

        /// Revoke tokens
     $personal->tokens()->delete();

        return response()->json([
            'data' => [
                'message' => 'Account deactivated successfully',
                'status' => $personal->deletestatus,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ]);
    }





}
