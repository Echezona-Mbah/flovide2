<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterOtpMail;
use App\Mail\WelcomeMail;
use App\Models\Balance;
use App\Models\Countries;
use App\Models\Personal;
use App\Models\TeamMembers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;




class RegisterController extends Controller
{


public function registerUser(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255',
        'password' => [
            'required','string','min:8','confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
        ],
        'password_confirmation' => 'required|string|min:8|same:password',
        'country' => 'required|string|max:255',
        'street_address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'firstname' => 'nullable|string|max:255',
        'lastname' => 'nullable|string|max:255',
        'person_phone' => 'nullable|string|max:20',

        // Business-specific
        'business_name' => 'required|string|max:255',
        'business_phone' => 'nullable|string|max:20',
        'registration_number' => 'required|string|max:255',
        'incorporation_date' => 'required|date|before_or_equal:today',
        'business_type' => 'required|string|max:255',
        'company_url' => 'required|string|max:255',
        'industry' => 'required|string|max:255',
        'annual_turnover' => 'required|string|max:255',
        'trading_state' => 'required|string|max:255',
        'nature_of_business' => 'required|string|max:255',
        'trading_street_address' => 'required|string|max:255',
        'trading_city' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if (
        User::where('email', $request->email)->exists() ||
        Personal::where('email', $request->email)->exists()
    ) {
        return response()->json(['errors' => ['email' => ['Email already taken']]], 422);
    }

    if ($request->person_phone && (
        User::where('person_phone', $request->person_phone)->exists() ||
        Personal::where('person_phone', $request->person_phone)->exists()
    )) {
        return response()->json(['errors' => ['person_phone' => ['Phone already taken']]], 422);
    }

    $otp = $this->generateOTP();
    $existingcountry = Countries::where('name', $request->country)->first();
    $currency_code = $existingcountry->currency_code;

    $user = User::create([
        'countries_id' => $request->country,
        'street_address' => $request->street_address,
        'city' => $request->city,
        'state' => $request->state,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'email_verification_otp' => $otp,
        'email_verification_otp_expires_at' => now()->addMinutes(10),
        'typeofuser' => 'business',
        'currency' => $currency_code,
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'person_phone' => $request->person_phone,
        'business_phone' => $request->business_phone,
        'business_name' => $request->business_name,
        'registration_number' => $request->registration_number,
        'incorporation_date' => $request->incorporation_date,
        'business_type' => $request->business_type,
        'company_url' => $request->company_url,
        'industry' => $request->industry,
        'annual_turnover' => $request->annual_turnover,
        'trading_address' => $request->trading_state,
        'nature_of_business' => $request->nature_of_business,
        'trading_street_address' => $request->trading_street_address,
        'trading_city' => $request->trading_city,
    ]);

    Balance::create([
        'user_id' => $user->id,
        'personal_id' => null,
        'currency' => $user->currency ?? 'USD',
        'name' => 'Main Balance',
        'balance' => 0.00,
    ]);

    TeamMembers::create([
        'user_id'  => $user->id,
        'owner_id' => $user->id,
        'email'    => $user->email,
        'role'     => "Owner",
    ]);

    Mail::to($user->email)->send(new RegisterOtpMail($otp, $user));

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'data' => [
            'message' => "Business registration successful. Please check your email for verification",
            'token' => $token,
            'email_verification_otp' => $otp,
        ]
    ], 201);
}






    public function verifyEmail(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|min:6',
            'email' => 'required|string|email|max:255',
        ]);

        $account = \App\Models\User::where('email', $request->email)
            ->where('email_verification_otp_expires_at', '>', now())
            ->first();

        if (!$account) {
            return response()->json([
                'message' => 'Invalid or expired OTP',
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ], 404);
        }

        if ($account->email_verification_otp !== $request->otp) {
            $account->increment('email_verification_attempts');

            if ($account->email_verification_attempts >= 3) {
                return response()->json([
                    'errors' => 'Too many attempts. Please request a new OTP.'
                ], 422);
            }

            return response()->json([
                'data' => [
                    'message' => 'Invalid OTP',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 422);
        }

        if ($account->email_verified_status !== 'no') {
            return response()->json(['message' => 'Email already verified']);
        }

        $account->update([
            'email_verified_at' => now(),
            'email_verified_status' => 'yes',
            'email_verification_attempts' => 0,
        ]);

        return response()->json([
            'data' => [
                'message' => 'Email verified successfully',
                'email' => $account->email,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ]);
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $account = \App\Models\User::where('email', $request->email)->first();

        if (!$account) {
            return response()->json([
                'data' => [
                    'errors' => 'User not found',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 404);
        }

        $otp = rand(100000, 999999);
        $expirationTime = now()->addMinutes(5);

        $account->update([
            'email_verification_otp' => $otp,
            'email_verification_otp_expires_at' => $expirationTime,
            'email_verification_attempts' => 0,
        ]);

        Mail::to($account->email)->send(new RegisterOtpMail($otp, $account));

        return response()->json([
            'data' => [
                'message' => "New OTP was sent. Please check your email for verification",
                'data' => $otp, 
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], 201);
    }



    public function getLoggedInUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return Response::json(['message' => 'No logged-in user'], 404);
        }

        $method = $request->method();
        $url = $request->fullUrl();

        return response()->json([
         'data' => [
            'business' => $user,
            'method' => $method,
            'url' => $url
        ]], 200);
    }

    public function getAllUsers()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return Response::json([
             'data' => [
                'message' => 'No users found'
            ]], 404);
        }
        return Response::json($users, 200);
    }

    public function getAllCountry()
    {
        $Countries = Countries::all();
        if ($Countries->isEmpty()) {
            return Response::json([
             'data' => [
                'message' => 'No Countries found'
            ]], 404);
        }
        return Response::json($Countries, 200);
    }


    public function deleteUser(Request $request, $email)
    {
        $user = $request->user();
    
        if (!$user) {
            $method = $request->method();
            $url = $request->fullUrl();
            return Response::json([
             'data' => [
            'error' => 'Unauthorized', 
            'method' => $method,
             'url' => $url
            ]], 401);
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
            return Response::json([
            'data' => [
            'error' => 'User not found',
             'method' => $method,
              'url' => $url
            ]], 404);
        }
    
        // Delete the user
        $userToDelete->delete();
    
        // Return success response
        return Response::json([
             'data' => [
            'message' => 'User deleted successfully',
             'method' => $request->method(), 
             'url' => $request->fullUrl()
            ]], 200);
    }


    private function generateOTP()
    {
        return rand(100000, 999999);
    }


    //  for personal

public function registerPersonal(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255',
        'password' => [
            'required','string','min:8','confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
        ],
        'password_confirmation' => 'required|string|min:8|same:password',
        'country' => 'required|string|max:255',
        'street_address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'person_phone' => 'required|string|max:20',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if (
        User::where('email', $request->email)->exists() ||
        Personal::where('email', $request->email)->exists()
    ) {
        return response()->json(['errors' => ['email' => ['Email already taken']]], 422);
    }

    if (
        User::where('person_phone', $request->person_phone)->exists() ||
        Personal::where('person_phone', $request->person_phone)->exists()
    ) {
        return response()->json(['errors' => ['person_phone' => ['Phone already taken']]], 422);
    }

    $otp = $this->generateOTP();
    $existingcountry = Countries::where('name', $request->country)->first();
    $currency_code = $existingcountry->currency_code;

    $user = Personal::create([
        'country' => $request->country,
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'person_phone' => $request->person_phone,
        'street_address' => $request->street_address,
        'city' => $request->city,
        'state' => $request->state,
        'currency' => $currency_code,
        'email_verification_otp' => $otp,
        'email_verification_otp_expires_at' => now()->addMinutes(10),
    ]);

    Balance::create([
        'user_id' => null,
        'personal_id' => $user->id,
        'currency' => $user->currency ?? 'USD',
        'name' => 'Main Balance',
        'balance' => 0.00,
    ]);

    Mail::to($user->email)->send(new RegisterOtpMail($otp, $user));

    $token = $user->createToken('personal-api-token')->plainTextToken;

    return response()->json([
        'data' => [
            'message' => "Personal registration successful. Please check your email for verification",
            'token' => $token,
            'email_verification_otp' => $otp,
        ]
    ], 201);
}






     public function verifyPersonalEmail(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|min:6',
            'email' => 'required','string','email','max:255',

        ]);
    
        $user = Personal::where('email', $request->email)
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
                'data' => [
                'message' => 'Invalid OTP',
                'method' => $method,
                'url' => $url
                ]
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
         'data' => [
            'message' => 'Email verified successfully',
            'email' => $user->email,
            'method' => $request->method(),
            'url' => $request->fullUrl()
         ]
        ]);
    }

    public function verifyPersonalEmailOtp(Request $request)
    {
          $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $user = Personal::where('email',  $request->email)->first();
    
        if (!$user) {
            $method = $request->method();
            $url = $request->fullUrl();
            return response()->json([
             'data' => [
                'errors' => 'User not found', 
                'method' => $method,
                 'url' => $url
             ]
                ], 404);
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
    
    
        return response()->json([
         'data' => [
            'message' => "New OTP was sent. Please check your email for verification",
            'data' => $otp,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]], 201);
    }

    public function getLoggedInPersonal(Request $request)
    {
        // Use the personal guard
        $personal = Auth::guard('personal')->user();

        if (!$personal) {
            return Response::json(['message' => 'No logged-in personal'], 404);
        }

        return response()->json([
            'data' => [
                'personal' => $personal,
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], 200);
    }

    public function getAllPersonal()
    {
        $personals = Personal::all();
        if ($personals->isEmpty()) {
            return Response::json([
                'data' => [
                    'message' => 'No personals found'
                ]
            ], 404);
        }
        return Response::json($personals, 200);
    }

    public function deletePersonal(Request $request, $email)
    {
        $personal = Auth::guard('personal')->user();

        if (!$personal) {
            return Response::json([
                'data' => [
                    'error' => 'Unauthorized',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 401);
        }

        $personalToDelete = Personal::where('email', $email)->first();

        if (!$personalToDelete) {
            return Response::json([
                'data' => [
                    'error' => 'Personal not found',
                    'method' => $request->method(),
                    'url' => $request->fullUrl()
                ]
            ], 404);
        }

        $personalToDelete->delete();

        return Response::json([
            'data' => [
                'message' => 'Personal deleted successfully',
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ], 200);
    }


}
