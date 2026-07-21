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

        $currentMemberRole = TeamMembers::where('owner_id', $ownerId)
            ->where('user_id', auth()->id())
            ->value('role');

        $members = TeamMembers::where('owner_id', $ownerId)->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Team members fetched successfully',
                'code' => 'TEAM_MEMBERS_FETCHED',
                'data' => $members
            ], 200);
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
            'success' => false,
            'message' => 'This email is already a member of your team.',
            'code' => 'TEAM_MEMBER_EXISTS',
            'data' => null
        ], 422);
    }

    $existingUser = User::where('email', $request->email)->first();

    $member = new TeamMembers();
    $member->owner_id = $owner->id;
    $member->email = $request->email;
    $member->role = $request->role;
    $member->invite_token = Str::random(40);
    $member->invite_token_expires_at = now()->addHours(24);

    if ($existingUser) {
        $member->user_id = $existingUser->id;
        $member->status = 'active';
    } else {
        $member->status = 'pending';
    }

    $member->save();

    $inviteLink = url('/team/invite/' . $member->invite_token);

    Mail::to($request->email)->send(new TeamInviteMail($owner, $inviteLink));

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Member added and invite email sent successfully.',
            'code' => 'TEAM_MEMBER_INVITED',
            'data' => array_merge($member->toArray(), [
                'invite_link' => $inviteLink,
            ]),
        ], 201);
    }

    return redirect()
        ->route('organization')
        ->with('success', 'Member added and invite email sent successfully.');
}





    public function showInviteForm($token)
    {
        $member = TeamMembers::where('invite_token', $token)->firstOrFail();
        return view('mainpage.accept-invite', compact('member'));
    }
    public function completeInvite(Request $request, $token)
    {
        $member = TeamMembers::with('userOwner')
            ->where('invite_token', $token)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'This invitation link is invalid or has already been used.',
                'code' => 'INVITE_INVALID',
                'data' => null
            ], 404);
        }

        if ($member->invite_token_expires_at && $member->invite_token_expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'This invitation link has expired. Please request a new one.',
                'code' => 'INVITE_EXPIRED',
                'data' => null
            ], 410);
        }

        $request->validate([
            'name'     => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

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

        $member->user_id = $user->id;
        $member->status = 'active';
        $member->invite_token_used_at = now();
        $member->invite_token = null;
        $member->save();

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Invitation accepted successfully.',
            'code' => 'INVITE_ACCEPTED',
            'data' => [
                'user' => $user,
                'member' => $member,
                'team_owner' => $member->userOwner ? [
                    'id' => $member->userOwner->id,
                    'business_name' => $member->userOwner->business_name,
                    'email' => $member->userOwner->email,
                ] : null,
            ],
        ], 200);
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
                'success' => false,
                'message' => $message,
                'code' => 'FORBIDDEN',
                'data' => null
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

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'code' => 'ROLE_UPDATED',
            'data' => [
                'id' => $member->id,
                'email' => $member->email,
                'old_role' => $oldRole,
                'new_role' => $member->role,
                'owner_id' => $member->owner_id,
                'updated_at' => $member->updated_at,
            ]
        ], 200);
    }

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
                'success' => false,
                'message' => 'User record not found',
                'code' => 'USER_NOT_FOUND',
                'data' => null
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

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'code' => 'PROFILE_UPDATED',
            'data' => [
                'profile_picture_url' => $user->profile_picture 
                    ? asset($user->profile_picture) 
                    : null
            ]
        ], 200);
    }

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
                'success' => false,
                'message' => 'User record not found',
                'code' => 'USER_NOT_FOUND',
                'data' => null
            ], 404);
        }
        return redirect()->back()->withErrors('User record not found');
    }

    $user->deletestatus = 'deactivated';
    $user->save();

    try {
        $user->tokens()->delete();
    } catch (\Exception $e) {
        // swallow
    }

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Account deactivated successfully',
            'code' => 'ACCOUNT_DEACTIVATED',
            'data' => [
                'user_status' => $user->deletestatus
            ]
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
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
        ],
    ]);

    $user = auth()->user();

    if (!Hash::check($request->old_password, $user->password)) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
                'code' => 'PASSWORD_INCORRECT',
                'data' => null
            ], 422);
        }
        return back()->withErrors(['old_password' => 'Current password is incorrect']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            'code' => 'PASSWORD_UPDATED',
            'data' => null
        ], 200);
    }

    return back()->with('success', 'Password updated successfully');
}





















        public function indexplan(Request $request)
    {
    
        return view('business.organization_plan');
    }
}
