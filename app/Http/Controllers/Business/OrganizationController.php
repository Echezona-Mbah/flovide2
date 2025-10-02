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





































         public function indexsetting(Request $request)
    {
    
        return view('business.organization_setting');
    }

        public function indexplan(Request $request)
    {
    
        return view('business.organization_plan');
    }
}
