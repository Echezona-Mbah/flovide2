<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterOtpMail;
use App\Mail\WelcomeMail;
use App\Models\Countries;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;



class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', function ($attribute, $value, $fail) {
                if (strpos($value, '@gmail.com') === false) {
                    $fail('The email must be a Gmail address.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
            'typeofuser' => 'required|in:business,personal',
        ], [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'email.unique' => 'The :attribute has already been taken.',
            'min' => 'The :attribute must be at least :min characters.',
            'regex' => 'The :attribute must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $method = $request->method();
            $url = $request->fullUrl();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $errors,
                'method' => $method,
                'url' => $url
            ], 422);
        }

        $otp = $this->generateOTP();

        $user = User::create([
            'countries_id' => $request->country,
            'business_name' => $request->business_name,
            'registration_number' => $request->registration_number,
            'incorporation_date' => $request->incorporation_date,
            'business_type' => $request->business_type,
            'company_url' => $request->company_url,
            'industry' => $request->industry,
            'annual_turnover' => $request->annual_turnover,
            'street_address' => $request->street_address,
            'city' => $request->city,
            'trading_address' => $request->trading_address,
            'nature_of_business' => $request->nature_of_business,
            'trading_street_address' => $request->trading_street_address,
            'trading_city' => $request->trading_city,
            'state' => $request->state,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_otp' => $otp,
            'email_verification_otp_expires_at' => now()->addMinutes(10),
            'bvn' => $request->bvn,
            'typeofuser' => $request->typeofuser
        ]);

        Mail::to($user->email)->send(new WelcomeMail($user));
    
        $email_verification_otp = $this->generateOTP();
        $user->email_verification_otp = $email_verification_otp;
        $expirationTime = now()->addMinutes(5);
        $user->update([
            'email_verification_otp' => $email_verification_otp,
            'email_verification_otp_expires_at' => $expirationTime,
        ]);
        Mail::to($user->email)->send(new RegisterOtpMail($email_verification_otp,$user));


        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'message' => "Registration was successful. Please check your email for verification",
            'data' => [
                'token' => $token
            ],
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ], 201);
    }

    public function verifyEmail(Request $request, $email)
    {
        $request->validate([
            'otp' => 'required|string|min:6',
        ]);
    
        $user = User::where('email', $email)
            ->where('email_verification_otp_expires_at', '>', now())
            ->first();
    
        if (!$user) {
            $method = $request->method();
            $url = $request->fullUrl();
            return response()->json([
                'message' => 'Invalid or expired OTP',
                'method' => $method,
                'url' => $url
            ], 404);
        }
    
        if ($user->email_verification_otp !== $request->otp) {
            $user->increment('email_verification_attempts');
    
            if ($user->email_verification_attempts >= 3) {
                return response()->json(['errors' => 'Too many attempts. Please request a new OTP.'], 422);
            }
    
            $method = $request->method();
            $url = $request->fullUrl();
            return response()->json([
                'message' => 'Invalid OTP',
                'method' => $method,
                'url' => $url
            ], 422);
        }
    
        if ($user->email_verified_status !== 'no') {
            return response()->json(['message' => 'Email already verified']);
        }
    
        $user->update([
            'email_verified_at' => now(),
            'email_verified_status' => 'yes',
            'email_verification_attempts' => 0,
        ]);
        
    
        return response()->json([
            'message' => 'Email verified successfully',
            'email' => $user->email,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]);
    }



    public function verifyEmailOtp($email, Request $request)
    {
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            $method = $request->method();
            $url = $request->fullUrl();
            return response()->json(['errors' => 'User not found', 'method' => $method, 'url' => $url], 404);
        }
        $otp = rand(100000, 999999);
    
        $email_verification_otp = $otp;
        $expirationTime = now()->addMinutes(5);
    
        $user->update([
            'email_verification_otp' => $email_verification_otp,
            'email_verification_otp_expires_at' => $expirationTime,
            'email_verification_attempts' => 0,
        ]);

        Mail::to($user->email)->send(new RegisterOtpMail($email_verification_otp, $user));
    
    
        return response()->json(['message' => "New OTP was sent. Please check your email for verification", 'method' => $request->method(), 'url' => $request->fullUrl()], 201);
    }

    public function getAllUsers()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return Response::json(['message' => 'No users found'], 404);
        }
        return Response::json($users, 200);
    }

    public function getAllCountry()
    {
        $Countries = Countries::all();
        if ($Countries->isEmpty()) {
            return Response::json(['message' => 'No Countries found'], 404);
        }
        return Response::json($Countries, 200);
    }


    public function deleteUser(Request $request, $email)
    {
        $user = $request->user();
    
        if (!$user) {
            $method = $request->method();
            $url = $request->fullUrl();
            return Response::json(['error' => 'Unauthorized', 'method' => $method, 'url' => $url], 401);
        }
    
        // if ($user->email !== $email) {
        //     $method = $request->method();
        //     $url = $request->fullUrl();
        //     return Response::json(['error' => 'Forbidden', 'method' => $method, 'url' => $url], 403);
        // }
    
        $userToDelete = User::where('email', $email)->first();
    
        if (!$userToDelete) {
            $method = $request->method();
            $url = $request->fullUrl();
            return Response::json(['error' => 'User not found', 'method' => $method, 'url' => $url], 404);
        }
    
        // Delete the user
        $userToDelete->delete();
    
        // Return success response
        return Response::json(['message' => 'User deleted successfully', 'method' => $request->method(), 'url' => $request->fullUrl()], 200);
    }


    private function generateOTP()
    {
        return rand(100000, 999999);
    }


}
