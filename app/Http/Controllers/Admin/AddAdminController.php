<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminActivityLog;
use App\Models\AdminLoginLog;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AddAdminController extends Controller
{
    public function index(Request $request)
    {
        $roles = AdminRole::pluck('name', 'id');
        return view('admin.add_admin', compact('roles'));
    }


     public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'role_id' => 'required',
            'password' => 'required|min:6',
            'passwordrep' => 'required|same:password',
        ]);
        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role_id,
        ]);

        return redirect()->route('admin.add_admin')->with('success', 'Admin created successfully!');
    }

    public function indexprofile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Please login first');
        }

        $roles = AdminRole::pluck('name', 'id');

        return view('admin.admin_updateprofile', compact('admin', 'roles'));
    }


    public function updateprofile(Request $request)
    {

        // dd($request->all());
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'role_id' => 'required',
            'skills' => 'nullable',
            'experience' => 'nullable',
            'portfolio_links' => 'nullable',
            'social_media_accounts' => 'nullable',
            'languages_spoken' => 'nullable',
            'emergency_contact' => 'nullable',
            'linkedin_profile' => 'nullable',
            'profile_picture' => 'image|mimes:jpg,png,jpeg|max:5120',
        ]);

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture')->store('admin_profiles', 'public');
            $admin->profile_picture = $image;
        }

        $admin->update($request->except('profile_picture'));

        return back()->with('success', 'Profile updated successfully');
    }

    public function indexadmin(Request $request)
    {
        $admins = Admin::all(); // fetch all admins
        return view('admin.alladmin', compact('admins'));
    }

    public function view($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.view_admin', compact('admin'));
    }

    public function unlock($id)
{
    $admin = Admin::findOrFail($id);
    $admin->locked_until = null;
    $admin->failed_attempts = 0;
    $admin->save();

    return back()->with('success', 'Admin account unlocked successfully!');
}


    public function activity($id)
{
    $admin = Admin::findOrFail($id);

    $activities = AdminActivityLog::where('admin_id', $id)->latest()->paginate(10);
    $logins = AdminLoginLog::where('admin_id', $id)->latest()->paginate(10);

    return view('admin.adminactivity', compact('admin','activities','logins'));
}


public function showResetForm()
{
    return view('admin.adminreset_password');
}

public function resetPassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $admin = Auth::guard('admin')->user();

    if (!Hash::check($request->old_password, $admin->password)) {
        return back()->with('error', 'Old password is incorrect');
    }

    $admin->password = Hash::make($request->new_password);
    $admin->save();

    return back()->with('success', 'Password updated successfully');
}


public function settings($id)
{
    $admin = Admin::findOrFail($id);
    $roles = AdminRole::get();
    // dd($roles);
    return view('admin.adminsetting', compact('admin','roles'));
}



public function changeRole(Request $request, $id)
{
    $admin = Admin::findOrFail($id);
    $request->validate([
        'role' => 'required'
    ]);

    $admin->role = $request->role;
    $admin->save();

    return back()->with('success', 'Role updated successfully.');
}

public function lock($id)
{
    $admin = Admin::findOrFail($id);
    $admin->locked_until = now()->addDays(30);
    $admin->save();

    return back()->with('success', 'Admin locked successfully.');
}

// public function unlock($id)
// {
//     $admin = Admin::findOrFail($id);
//     $admin->locked_until = null;
//     $admin->save();

//     return back()->with('success', 'Admin unlocked successfully.');
// }

public function deactivate($id)
{
    $admin = Admin::findOrFail($id);
    $admin->status = 'inactive';
    $admin->save();

    return back()->with('success', 'Admin deactivated successfully.');
}

public function resetPasswords($id)
{
    $admin = Admin::findOrFail($id);
    $newPassword = 'Admin1234';
    $admin->password = bcrypt($newPassword);
    $admin->save();

    return back()->with('success', 'Password reset successfully. New password: ' . $newPassword);
}

public function destroy($id)
{
    $admin = Admin::findOrFail($id);
    $admin->delete();

    return redirect()->route('admin.list')->with('success', 'Admin deleted successfully.');
}




}
