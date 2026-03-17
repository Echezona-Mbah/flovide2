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








}
