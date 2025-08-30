<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordEmail;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            return response()->json([
                'data' => [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 422);
        }

        // Only User lookup
        $account = \App\Models\User::where('email', $request->email)->first();

        if (!$account) {
            return response()->json([
                'data' => [
                    'message' => 'Account not found',
                    'errors' => 'No User found with this email',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 404);
        }

        session(['reset_email' => $account->email]);

        $otp = $this->generateOTP();

        $account->update([
            'forget_verification_otp' => $otp,
            'forgot_password_otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($account->email)->send(new ForgetPasswordEmail($otp, $account));

        return response()->json([
            'data' => [
                'message' => 'Password reset OTP sent. Please check your email.',
                'otp' => $otp,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], 201);
    }

    
    public function createverifyOTP()
    {
    
        return view('auth.varifyOtp');
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 422);
        }

        // Only User lookup
        $account = \App\Models\User::where('forget_verification_otp', $request->otp)
                    ->where('forgot_password_otp_expires_at', '>', now())
                    ->first();

        if (!$account) {
            return response()->json([
                'data' => [
                    'message' => 'Invalid or expired OTP',
                    'errors' => 'Invalid or expired OTP',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 401);
        }

        $account->update([
            'forget_verification_otp' => null,
            'forgot_password_otp_expires_at' => null,
            'reset_token' => Str::random(60),
            'reset_token_expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'data' => [
                'message' => 'OTP verified',
                'reset_token' => $account->reset_token,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], 200);
    }



    public function createresetPassword()
    {
    
        return view('auth.reset-password');
    }


    //  api
    public function resetPasswordapi(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ]);

        // Only User lookup
        $account = \App\Models\User::where('reset_token', $request->reset_token)
                    ->where('reset_token_expires_at', '>', now())
                    ->first();

        if (!$account) {
            return response()->json([
                'data' => [
                    'message' => 'Invalid or expired reset token',
                ]
            ], 401);
        }

        $account->update([
            'password' => bcrypt($request->password),
            'reset_token' => null,
            'reset_token_expires_at' => null,
        ]);

        return response()->json([
            'data' => [
                'message' => 'Password has been reset successfully',
                
            ]
        ], 200);
    }




    


    // web
    public function resetPassword(Request $request)
    {

        // dd('eesddd');
        $email = $request->expectsJson()
            ? $request->email
            : session('reset_email');
    
        if (!$email) {
            return $request->expectsJson()
                ? response()->json([
                     'data' => [
                    'message' => 'Email is missing']
                ], 400)
                : redirect()->back()->withErrors([
                     'data' => [
                    'email' => 'Email session expired or missing. Please restart password reset.']]);
        }
    
        $rules = [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ];
    
        $messages = [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                     'data' => [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]], 422);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $request->expectsJson()
                ? response()->json([
                 'data' => [
                    'message' => 'User not found',
                    'errors' => 'No account with this email',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]], 404)
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
         'data' => [
            'message' => 'Password has been reset',
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]], 200);
    }

    private function generateOTP()
    {
        return rand(100000, 999999);
    }


    // for personal

    public function forgotPasswordPersonal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 422);
        }

        $personal = Personal::where('email', $request->email)->first();

        if (!$personal) {
            return response()->json([
                'data' => [
                    'message' => 'Personal not found',
                    'errors' => 'Personal not found with the provided email address',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 404);
        }

        session(['reset_email' => $personal->email]);

        $otp = $this->generateOTP();

        $personal->update([
            'forget_verification_otp' => $otp,
            'forgot_password_otp_expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($personal->email)->send(new ForgetPasswordEmail($otp, $personal));

        return response()->json([
            'data' => [
                'message' => 'Password reset OTP sent. Please check your email for OTP',
                'data' => ['otp' => $otp],
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], 201);
    }

    public function verifyOTPPersonal(Request $request)
    {
        $email = session('reset_email');

        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 422);
        }

        $personal = Personal::where('forget_verification_otp', $request->otp)
            ->where('forgot_password_otp_expires_at', '>', now())
            ->first();

        if (!$personal) {
            return response()->json([
                'data' => [
                    'message' => 'Invalid or expired OTP',
                    'errors' => 'Invalid or expired OTP',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 401);
        }

        $personal->update([
            'forget_verification_otp' => null,
            'forgot_password_otp_expires_at' => null,
            'reset_token' => Str::random(60),
            'reset_token_expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'data' => [
                'message' => 'OTP verified',
                'reset_token' => $personal->reset_token,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], 200);
    }

    public function resetPasswordapiPersonal(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
        ]);

        $personal = Personal::where('reset_token', $request->reset_token)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$personal) {
            return response()->json([
                'data' => [
                    'message' => 'Invalid or expired reset token',
                ]
            ], 401);
        }

        $personal->update([
            'password' => bcrypt($request->password),
            'reset_token' => null,
            'reset_token_expires_at' => null,
        ]);

        return response()->json([
            'data' => [
                'message' => 'Password has been reset successfully',
            ]
        ], 200);
    }





}
