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
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    ]);

    $personalId = auth('personal-api')->id();
    $personal = Personal::where('id', $personalId)->first();

    if (!$personal) {
        return response()->json([
            'success' => false,
            'message' => 'Personal record not found',
            'code' => 'PERSONAL_NOT_FOUND',
            'data' => null
        ], 404);
    }

    if ($request->hasFile('profile_picture')) {
        $folder = 'profile_pictures/personal';
        $filename = time() . '_' . uniqid() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
        $request->file('profile_picture')->move(public_path($folder), $filename);
        if ($personal->profile_picture && file_exists(public_path($personal->profile_picture))) {
            unlink(public_path($personal->profile_picture));
        }
        $personal->profile_picture = $folder . '/' . $filename;
    }

    $personal->save();

    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully',
        'code' => 'PROFILE_UPDATED',
        'data' => [
            'profile_picture_url' => $personal->profile_picture 
                ? asset($personal->profile_picture) 
                : null
        ]
    ], 200);
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

    $existsInUsers = User::where('email', $newEmail)->exists();

    if ($existsInPersonals || $existsInUsers) {
        return response()->json([
            'success' => false,
            'message' => 'Email already taken',
            'code' => 'EMAIL_TAKEN',
            'data' => null
        ], 422);
    }

    $personal = Personal::find($personalId);

    if (!$personal) {
        return response()->json([
            'success' => false,
            'message' => 'Personal record not found',
            'code' => 'PERSONAL_NOT_FOUND',
            'data' => null
        ], 404);
    }

    $personal->email = $newEmail;
    $personal->save();

    return response()->json([
        'success' => true,
        'message' => 'Email updated successfully',
        'code' => 'EMAIL_UPDATED',
        'data' => [
            'email' => $personal->email
        ]
    ], 200);
}


 public function deactivateAccount(Request $request)
{
    $personalId = auth('personal-api')->id();
    $personal = Personal::find($personalId);

    if (!$personal) {
        return response()->json([
            'success' => false,
            'message' => 'Personal record not found',
            'code' => 'PERSONAL_NOT_FOUND',
            'data' => null
        ], 404);
    }

    $personal->deletestatus = 'deactivated';
    $personal->save();

    $personal->tokens()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Account deactivated successfully',
        'code' => 'ACCOUNT_DEACTIVATED',
        'data' => [
            'status' => $personal->deletestatus
        ]
    ], 200);
}





}
