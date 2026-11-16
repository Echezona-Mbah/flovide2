<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
    $credentials = $request->validate([
        'email' => ['required', 'email', 'max:255'],
        'password' => ['required', 'string', 'max:1024'],
    ]);

    $agent = new Agent();
    $device = $agent->platform().' - '.$agent->browser();
    $ip = $request->ip();
    $email = mb_strtolower(trim($credentials['email']));
    $admin = Admin::where('email', $email)->first();
    $validPassword = Hash::check(
        $credentials['password'],
        $admin?->password ?? $this->dummyPasswordHash()
    );

    if (! $admin) {
        $this->recordLoginAttempt(null, $email, $ip, $device, false);
        return back()->with('error', 'Invalid email or password.');
    }

    if ($admin->locked_until && now()->lessThan($admin->locked_until)) {
        $this->recordLoginAttempt($admin, $email, $ip, $device, false);
        return back()->with('error', 'Invalid email or password.');
    }

    if (! $validPassword) {
        $admin->failed_attempts += 1;
        $admin->save();
        $this->recordLoginAttempt($admin, $email, $ip, $device, false);
        return back()->with('error', 'Invalid email or password.');
    }

    $admin->failed_attempts = 0;
    $admin->locked_until = null;
    $admin->save();

    Auth::guard('admin')->login($admin);
    $request->session()->regenerate();
    $this->recordLoginAttempt($admin, $email, $ip, $device, true);

    return redirect()->route('admin.dashboard');
}

private function recordLoginAttempt(?Admin $admin, string $email, string $ip, string $device, bool $success): void
{
    AdminLoginLog::create([
        'admin_id' => $admin?->id,
        'email' => $email,
        'ip_address' => $ip,
        'device' => $device,
        'success' => $success,
        'attempted_at' => now(),
    ]);
}

private function dummyPasswordHash(): string
{
    static $hash;

    return $hash ??= Hash::make(bin2hex(random_bytes(32)));
}

}
