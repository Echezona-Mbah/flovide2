<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\AdminLoginLog;
use Jenssegers\Agent\Agent;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.register');
    }


 public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:admins,email',
        'password' => 'required|min:6|confirmed',
    ]);

    Admin::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('admin.login')->with('success', 'Admin registered successfully!');
}


        public function indexlogin(Request $request)
    {
        return view('admin.login');
    }


    //  public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::guard('admin')->attempt([
    //         'email' => $request->email,
    //         'password' => $request->password
    //     ])) {
    //         return redirect()->route('admin.dashboard')
    //             ->with('success', 'Login successful!');
    //     }

    //     return back()->with('error', 'Invalid email or password.');
    // }

//     public function login(Request $request)
// {
//     $admin = Admin::where('email', $request->email)->first();

//     if (!$admin) {
//         return back()->with('error', 'Invalid login details');
//     }

//     // Check if locked
//     if ($admin->locked_until && Carbon::now()->lessThan($admin->locked_until)) {
//         return back()->with('error', 'Account locked. Try again later.');
//     }

//     // Check password
//     if (!Hash::check($request->password, $admin->password)) {

//         $admin->failed_attempts += 1;

//         if ($admin->failed_attempts >= 3) {
//             $admin->locked_until = Carbon::now()->addMinutes(10); // lock for 10 mins
//             $admin->failed_attempts = 0;
//         }

//         $admin->save();

//         return back()->with('error', 'Invalid login credentials');
//     }

//     // Reset attempts
//     $admin->failed_attempts = 0;
//     $admin->locked_until = null;
//     $admin->save();

//     Auth::guard('admin')->login($admin);

//     return redirect()->route('admin.dashboard');
// }



public function login(Request $request)
{
    $agent = new Agent();

    $device = $agent->platform().' - '.$agent->browser();

    $ip = $request->ip();

    $location = json_decode(file_get_contents("http://ip-api.com/json/".$ip));
    //dd($location);

    $country = $location->country ?? null;
    $city = $location->city ?? null;
    $lat = $location->lat ?? null;
    $lon = $location->lon ?? null;

    $admin = Admin::where('email', $request->email)->first();

    if (!$admin) {

        AdminLoginLog::create([
            'email' => $request->email,
            'ip_address' => $ip,
            'device' => $device,
            'country' => $country,
            'city' => $city,
            'latitude' => $lat,
            'longitude' => $lon,
            'success' => false,
            'attempted_at' => now()
        ]);

        return back()->with('error','Invalid login details');
    }

    if ($admin->locked_until && now()->lessThan($admin->locked_until)) {
        return back()->with('error','Account locked. Try later.');
    }

    if (!Hash::check($request->password,$admin->password)) {

        $admin->failed_attempts += 1;

        if ($admin->failed_attempts >= 3) {
            $admin->locked_until = now()->addDays(2555);
            $admin->failed_attempts = 0;
        }

        $admin->save();

        AdminLoginLog::create([
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'ip_address' => $ip,
            'device' => $device,
            'country' => $country,
            'city' => $city,
            'latitude' => $lat,
            'longitude' => $lon,
            'success' => false,
            'attempted_at' => now()
        ]);

        return back()->with('error','Invalid login credentials');
    }

    $admin->update([
        'failed_attempts' => 0,
        'locked_until' => null
    ]);

    Auth::guard('admin')->login($admin);

    AdminLoginLog::create([
        'admin_id' => $admin->id,
        'email' => $admin->email,
        'ip_address' => $ip,
        'device' => $device,
        'country' => $country,
        'city' => $city,
        'latitude' => $lat,
        'longitude' => $lon,
        'success' => true,
        'attempted_at' => now()
    ]);

    return redirect()->route('admin.dashboard');
}

}
