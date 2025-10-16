<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\TeamMembers;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TeamInviteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $ownerId = session('owner_id');

        // Get current member's role in the team
        $currentMemberRole = TeamMembers::where('owner_id', $ownerId)
            ->where('user_id', auth()->id())
            ->value('role'); // This returns only the role string

        $members = TeamMembers::where('owner_id', $ownerId)->get();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Team members fetched successfully',
                'data' => $members
            ]);
        }

        return view('business.organization', compact('members', 'currentMemberRole'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:Owner,Admin,Accountant,Author'
        ]);

        $owner = auth()->user();
        $existingUser = User::where('email', $request->email)->first();

        $member = new TeamMembers();
        $member->owner_id = $owner->id;
        $member->email = $request->email;
        $member->role = $request->role;

        if ($existingUser) {
            $member->user_id = $existingUser->id;
            $member->status = 'active';
        } else {
            $member->invite_token = Str::random(40);
            $member->status = 'pending';
            Mail::to($request->email)->send(new TeamInviteMail($owner, $member->invite_token));
        }

        $member->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Member added successfully',
                'data' => $member
            ]);
        }

        return back()->with('success', 'Member added successfully!');
    }

    public function showInviteForm($token)
    {
        $member = TeamMembers::where('invite_token', $token)->firstOrFail();
        return view('mainpage.accept-invite', compact('member'));
    }

    // Complete invite (register new user)
    public function completeInvite(Request $request, $token)
    {
        $member = TeamMembers::with('userOwner') // eager load the team owner
            ->where('invite_token', $token)
            ->firstOrFail();

        $request->validate([
            'name'     => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        // If invited user already exists, reuse them
        $user = User::where('email', $member->email)->first();

        if (!$user) {
            // Create user without making a new business dashboard
            $user = User::create([
                'typeofuser'            => 'personnal', // invited users are personal
                'email_verified_status' => 'yes',
                'email'                 => $member->email,
                'password'              => Hash::make($request->password),
                'business_name'         => $request->name,
            ]);
        }

        // Mark invite as accepted
        $member->user_id     = $user->id;
        $member->status      = 'active';
        $member->invite_token = null;
        $member->save();

        // Log in the invited user
        Auth::login($user);

        // Redirect to the team owner’s dashboard
        return redirect()->route('dashboard')
            ->with('success', 'Welcome to ' . $member->userOwner->business_name . ' dashboard!');
    }


    public function updateRole(Request $request, $id)
    {
        $ownerId = session('owner_id');

        $currentMemberRole = TeamMembers::where('owner_id', $ownerId)
            ->where('user_id', auth()->id())
            ->value('role');

        if ($currentMemberRole !== 'Owner') {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Only the Owner can update roles.'
                ], 403);
            }
            abort(403, 'Only the Owner can update roles.');
        }

        $request->validate([
            'role' => 'required|in:Owner,Admin,Accountant,Author',
        ]);

        $member = TeamMembers::where('owner_id', $ownerId)->findOrFail($id);
        $member->role = $request->role;
        $member->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Role updated successfully.',
                'data' => $member
            ]);
        }

        return back()->with('success', 'Role updated successfully.');
    }



public function updateProfile(Request $request)
{
    $request->validate([
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
    ]);

    $user = Auth::user();

    if (!$user) {
        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'errors' => 'User record not found'
                ]
            ], 404);
        }
        return redirect()->back()->withErrors('User record not found');
    }

    if ($request->hasFile('profile_picture')) {
        $folder = 'profile_pictures/business';
        $filename = time() . '_' . uniqid() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
        $request->file('profile_picture')->move(public_path($folder), $filename);

        // delete old picture if exists
        if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
            unlink(public_path($user->profile_picture));
        }

        $user->profile_picture = $folder . '/' . $filename;
    }

    $user->save();

    // ✅ If API (JSON request)
    if ($request->expectsJson()) {
        return response()->json([
            'data' => [
                'message' => 'Profile updated successfully',
                'profile_picture_url' => $user->profile_picture 
                    ? asset($user->profile_picture) 
                    : null,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ]);
    }

    // ✅ If Web (normal form submission)
    return redirect()->back()->with('success', 'Profile updated successfully');
}



    public function storesetting(Request $request)
    {
        $formType = $request->input('form_type');

        if ($formType === 'password') {
            return $this->updatePassword($request);
        }

        if ($formType === 'deactivate') {
            return $this->deactivateAccount($request);
        }

        if ($formType === 'updateProfile') {
            return $this->updateProfile($request);
        }

        return redirect()->route('organization_setting')->withErrors('Invalid request.');
    }


          public function indexsetting(Request $request)
    {
    
        return view('business.organization_setting');
    }


    public function deactivateAccount(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'User record not found'
                ], 404);
            }
            return redirect()->back()->withErrors('User record not found');
        }
        $user->deletestatus = 'deactivated';
        $user->save();
        try {
            $user->tokens()->delete();
        } catch (\Exception $e) {
        }
        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Account deactivated successfully',
                'user_status' => $user->deletestatus,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        }
        return redirect()
            ->route('login')
            ->with('success', 'Account deactivated successfully');
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // Strong password rule
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
            ],
        ]);

        $user = auth()->user();

        // 2. Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                'data'=>[
                    'status' => false,
                    'message' => 'Current password is incorrect',
                ]
                ], 422);
            }
            return back()->withErrors(['old_password' => 'Current password is incorrect']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        if ($request->expectsJson()) {
            return response()->json([
            'data'=>[
                'status' => true,
                'message' => 'Password updated successfully',
            ]
            ]);
        }
        return back()->with('success', 'Password updated successfully');
    }





















        public function indexplan(Request $request)
    {
    
        return view('business.organization_plan');
    }
}
