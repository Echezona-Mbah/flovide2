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
                // dd($members);


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
        'role'  => 'required|in:Owner,Admin,Accountant,Author'
    ]);

    $owner = auth()->user();

    $existingMember = TeamMembers::where('owner_id', $owner->id)
        ->where('email', $request->email)
        ->first();

    if ($existingMember) {
        return response()->json([
            'data' => [
                'status'  => false,
                'error' => 'This email is already a member of your team.',
            ]
        ], 422);
    }

    $existingUser = User::where('email', $request->email)->first();

    $member = new TeamMembers();
    $member->owner_id = $owner->id;
    $member->email = $request->email;
    $member->role = $request->role;
    $member->invite_token = Str::random(40);
    $member->invite_token_expires_at = now()->addHours(24); //  expires in 24 hours

    if ($existingUser) {
        $member->user_id = $existingUser->id;
        $member->status = 'active';
    } else {
        $member->status = 'pending';
    }

    $member->save();

    // ✅ Generate invite link
    $inviteLink = url('/team/invite/' . $member->invite_token);

    // ✅ Send mail
    Mail::to($request->email)->send(new TeamInviteMail($owner, $inviteLink));

    // ✅ Response
    return response()->json([
        'status'  => true,
        'message' => 'Member added and invite email sent successfully.',
        'data'    => array_merge($member->toArray(), [
            'invite_link' => $inviteLink,
        ]),
    ]);
}




    public function showInviteForm($token)
    {
        $member = TeamMembers::where('invite_token', $token)->firstOrFail();
        return view('mainpage.accept-invite', compact('member'));
    }

        // Complete invite (register new user)
    public function completeInvite(Request $request, $token)
    {
    $member = TeamMembers::with('userOwner')
        ->where('invite_token', $token)
        ->first();

    if (!$member) {
        return response()->json([
        'data'=>[
            'status'  => false,
            'error' => 'This invitation link is invalid or has already been used.',
        ]
        ], 404);
    }


        // ✅ Check if token expired
        if ($member->invite_token_expires_at && $member->invite_token_expires_at->isPast()) {
            return response()->json([
                'data'=>[
                    'status'  => false,
                    'error' => 'This invitation link has expired. Please request a new one.',
                ]
            ], 410); // 410 Gone
        }

        $request->validate([
            'name'     => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        // ✅ Create or fetch user
        $user = User::where('email', $member->email)->first();

        if (!$user) {
            $user = User::create([
                'typeofuser'            => 'business',
                'email_verified_status' => 'yes',
                'email'                 => $member->email,
                'password'              => Hash::make($request->password),
                'business_name'         => $request->name,
            ]);
        }

        // ✅ Activate member
        $member->user_id = $user->id;
        $member->status = 'active';
        $member->invite_token_used_at = now(); // record usage
        $member->invite_token = null;          // clear token
        $member->save();

        Auth::login($user);

        return response()->json([
            'status'  => true,
            'message' => 'Invitation accepted successfully.',
            'data'    => [
                'user'   => $user,
                'member' => $member,
                'team_owner' => $member->userOwner ? [
                    'id' => $member->userOwner->id,
                    'business_name' => $member->userOwner->business_name,
                    'email' => $member->userOwner->email,
                ] : null,
            ],
        ]);
    }




public function updateRole(Request $request, $id)
{
    $ownerId = session('owner_id');
    $currentMemberRole = TeamMembers::where('owner_id', $ownerId)
        ->where('user_id', auth()->id())
        ->value('role');

    if ($currentMemberRole !== 'Owner') {
        $message = 'Only the Owner can update roles.';

        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => $message
            ], 403);
        }

        return back()->with('error', $message);
    }
    $request->validate([
        'role' => 'required|in:Owner,Admin,Accountant,Author',
    ]);
    $member = TeamMembers::where('owner_id', $ownerId)->findOrFail($id);

    $oldRole = $member->role;
    $member->role = $request->role;
    $member->save();

    $successMessage = "Role updated successfully from $oldRole to {$member->role}.";

    // If API request
    if ($request->expectsJson()) {
        return response()->json([
            'status' => true,
            'message' => $successMessage,
            'data' => [
                'id' => $member->id,
                'email' => $member->email,
                'old_role' => $oldRole,
                'new_role' => $member->role,
                'owner_id' => $member->owner_id,
                'updated_at' => $member->updated_at,
            ]
        ]);
    }

    // If web request
    return back()->with('success', $successMessage);
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
