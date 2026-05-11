<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;
use App\Models\User;
use App\Mail\LoginOtpMail;

class OtpController extends Controller
{
    public function index()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.OTPAuthentication');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $userId = session('otp_user_id');
        $user = User::find($userId);

        // If session expired or user not found
        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // Check if OTP is correct
        if ($user->login_otp !== $request->otp) {
            return back()->with('error', 'Invalid OTP');
        }

        // Check if OTP expired
        if (now()->gt($user->login_otp_expires_at)) {
            return redirect()->route('login')->with('error', 'OTP expired. Please login again.');
        }

        // Clear OTP after successful verification
        $user->update([
            'login_otp' => null,
            'login_otp_expires_at' => null,
        ]);
 
        session()->forget('otp_user_id');
        session()->regenerate();
        Auth::login($user);
        session()->flash('status', 'Login successful!');

        return redirect()->route('dashboard');
    }

    public function resend(Request $request)
    {
        try{
            $userId = session('otp_user_id');
            
            if (!$userId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Session expired. Please login again.'
                ], 400);
            }

            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ], 400);
            }

            // Check if OTP is still valid to prevent abuse
            if ($user->login_otp_expires_at && now()->lt($user->login_otp_expires_at->subSeconds(300))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You can resend OTP after 5 minutes.'
                ], 400);
            }

            // Generate new OTP
            $otp = rand(100000, 999999);

            // Save to database
            $user->update([
                'login_otp' => $otp,
                'login_otp_expires_at' => now()->addMinutes(5),
            ]);

            // Send OTP notification
            $user->notify(new GeneralNotification(
                "Your Login OTP",
                "Hello {$user->business_name}, your OTP is: {$otp}. It expires in 5 minutes."
            ));

            // Send OTP via email
            Mail::to($user->email)->send(new LoginOtpMail($user->business_name, $otp));

            return response()->json([
                'status' => 'success',
                'message' => 'A new OTP has been sent!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
        
    }

}