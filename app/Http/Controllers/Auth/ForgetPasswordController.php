<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    public function create()
    {
    
        return view('auth.forgot-password');
    }


    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'User not found',
                    'errors' => 'User not found with the provided email address',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 404);
            } else {
                return redirect()->back()->withErrors(['email' => 'User not found with the provided email address'])->withInput();
            }
        }
        session(['reset_email' => $user->email]);

        $otp = $this->generateOTP();

        $user->update([
            'forget_verification_otp' => $otp,
            'forgot_password_otp_expires_at' => now()->addMinutes(5),
        ]);
        Mail::to($user->email)->send(new ForgetPasswordEmail($otp, $user));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Password reset OTP sent. Please check your email for OTP',
                'data' => ['otp' => $otp],
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 201);
        } else {
            return redirect()->route('forget-verify-otp')->with('status', 'Password reset OTP sent. Please check your email.');
        }
    }

    
    public function createverifyOTP()
    {
    
        return view('auth.varifyOtp');
    }

    public function verifyOTP(Request $request)
    {
        $email = session('reset_email');
        // dd($email);
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $user = User::where('forget_verification_otp', $request->otp)
                    ->where('forgot_password_otp_expires_at', '>', now())
                    ->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invalid or expired OTP',
                    'errors' => 'Invalid or expired OTP',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 401);
            } else {
                return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP'])->withInput();
            }
        }

        $user->update([
            'forget_verification_otp' => null,
            'forgot_password_otp_expires_at' => null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'OTP verified',
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 200);
        } else {
            return redirect()->route('resetPassword')->with('status', 'OTP verified. You can now reset your password.');
        }
    }

    public function createresetPassword()
    {
    
        return view('auth.reset-password');
    }
    public function resetPassword(Request $request)
    {
        $email = $request->expectsJson()
            ? $request->email
            : session('reset_email');
    
        if (!$email) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Email is missing'], 400)
                : redirect()->back()->withErrors(['email' => 'Email session expired or missing. Please restart password reset.']);
        }
    
        $rules = [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ];
    
        $messages = [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $request->expectsJson()
                ? response()->json([
                    'message' => 'User not found',
                    'errors' => 'No account with this email',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ], 404)
                : redirect()->back()->withErrors(['email' => 'User not found'])->withInput();
        }
        $user->update([
            'password' => bcrypt($request->password),
        ]);
    
        // Clear session for web
        if (!$request->expectsJson()) {
            session()->forget('reset_email');
            return redirect()->route('login')->with('status', 'Password reset successfully.');
        }
    
        // API success response
        return response()->json([
            'message' => 'Password has been reset',
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ], 200);
    }
    



    private function generateOTP()
    {
        return rand(100000, 999999);
    }



}
