<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use App\Models\LoginActivity;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     session()->flash('status', 'Login successful!');

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }


    public function recordLoginActivity($request, $user)
    {
        $agent = new Agent();

        // Get user details
        $ip = $request->ip();
        $device = $agent->device();
        $browser = $agent->browser();
        $os = $agent->platform();

        //Get Location using IP-API
        $location = null;
        try {
            $json = @file_get_contents("http://ip-api.com/json/{$ip}");
            $details = json_decode($json, true);
            if ($details && $details['status'] === 'success') {
                $location = $details['city'] . ', ' . $details['country'];
            }
        } catch (\Exception $e) {
            $location = null;
        }

        // Save record
        LoginActivity::create([
            'user_id' => $user->id,
            'personal_id' => null,
            'ip_address' => $ip,
            'device' => $device ?: 'Unknown Device',
            'browser' => $browser ?: 'Unknown Browser',
            'os' => $os ?: 'Unknown OS',
            'location' => $location,
            'login_time' => now(),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user(); // Use the authenticated user

        if ($user->email_verified_status !== 'yes') {
            $email = $user->email;
            Auth::logout();

            return redirect()->route('verifyemail')->with([
                'error' => 'You must verify your email before logging in.',
                'user_email' => $email
            ]);
        }

        //RECORD LOGIN ACTIVITY
        $this->recordLoginActivity($request, $user);

        session()->flash('status', 'Login successful!');
        return redirect()->intended(route('dashboard', absolute: false));
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
