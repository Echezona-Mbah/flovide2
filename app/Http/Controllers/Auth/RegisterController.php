<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Mail\RegisterOtpMail;
use App\Mail\WelcomeMail;
use App\Models\Balance;
use App\Models\Countries;
use App\Models\Currency;
use App\Models\Personal;
use App\Models\TeamMembers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Http;


class RegisterController extends Controller
{

    private function generateReferralCode($length = 10)
    {
        do {
            $code = strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));

            $existsInPersonals = Personal::where('referral_code', $code)->exists();

            $existsInUsers = User::where('referral_code', $code)->exists();

        } while ($existsInPersonals || $existsInUsers);

        return $code;
    }




    public function fetchcountrylist(Request $request)
    {
        $country_name = $request->get('country_name', 'NG'); 
        $alpha2 = $request->get('alpha2', 'NGN'); 

        $response = Http::withToken(env('OHENTPAY_API_KEY'))
            ->get(rtrim(env('OHENTPAY_BASE_URL'), '/') . '/countries', [
                'country_name' => $country_name,
                'alpha2' => $alpha2
            ]);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'fields' => $response->json()
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch bank fields',
            'details' => $response->json()
        ], $response->status());
    }


public function registerUser(Request $request)
{

    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255',
        'password' => [
            'required','string','min:8','confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]+$/'
        ],
        'password_confirmation' => 'required|string|min:8|same:password',
        'country' => 'required|string|max:255',
        'street_address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'date_of_birth' => 'required|date|before_or_equal:today',
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

    // return response()->json([
    //     'success' => false,
    //     'message' => 'Validation error',
    //     'code' => 'VALIDATION_ERROR',
    //     'data' => $validator->errors()
    // ], 422);

      if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'code' => 'VALIDATION_ERROR',
            'data' => $validator->errors()
        ], 422);
    }

    if (
        User::where('email', $request->email)->exists() ||
        Personal::where('email', $request->email)->exists()
    ) {
       return response()->json([
            'success' => false,
            'message' => 'Email already taken',
            'code' => 'EMAIL_TAKEN',
            'data' => null
        ], 422);
    }

    if ($request->person_phone && (
        User::where('person_phone', $request->person_phone)->exists() ||
        Personal::where('person_phone', $request->person_phone)->exists()
    )) {
        return response()->json([
            'success' => false,
            'message' => 'Phone already taken',
            'code' => 'PHONE_TAKEN',
            'data' => null
        ], 422);
    }

    $otp = $this->generateOTP();
    $existingcountry = Countries::where('name', $request->country)->first();
    $currency_code = $existingcountry->currency_code;

    $referral_code = $this->generateReferralCode();
    $referral_link = url('/register/' . $referral_code);

    $user = User::create([
        'countries_id' => $request->country,
        'street_address' => $request->street_address,
        'city' => $request->city,
        'state' => $request->state,
        'date_of_birth' => \Carbon\Carbon::parse($request->date_of_birth)->format('d/m/Y'),
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'email_verification_otp' => $otp,
        'email_verification_otp_expires_at' => now()->addMinutes(10),
        'typeofuser' => 'business',
        'referral_code' => $referral_code,
        'referral_link' => $referral_link,
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
    event(new UserRegistered($user));


    return response()->json([
        'success' => true,
        'message' => 'Business registration successful. Please check your email for verification',
        'code' => 'REGISTERED',
        'data' => [
            'token' => $token,
            'email_verification_otp' => $otp
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
                'success' => false,
                'message' => 'Invalid or expired OTP',
                'code' => 'OTP_INVALID_OR_EXPIRED',
                'data' => null
            ], 404);
        }

        if ($account->email_verification_otp !== $request->otp) {
            $account->increment('email_verification_attempts');

            if ($account->email_verification_attempts >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many attempts. Please request a new OTP.',
                    'code' => 'OTP_TOO_MANY_ATTEMPTS',
                    'data' => null
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
                'code' => 'OTP_INVALID',
                'data' => null
            ], 422);
        }

        if ($account->email_verified_status !== 'no') {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified',
                'code' => 'EMAIL_ALREADY_VERIFIED',
                'data' => null
            ], 400);
        }

        $account->update([
            'email_verified_at' => now(),
            'email_verified_status' => 'yes',
            'email_verification_attempts' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'code' => 'EMAIL_VERIFIED',
            'data' => [
                'email' => $account->email
            ]
        ], 200);

    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $account = \App\Models\User::where('email', $request->email)->first();

        if (!$account) {
           return response()->json([
            'success' => false,
            'message' => 'User not found',
            'code' => 'USER_NOT_FOUND',
            'data' => null
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
            'success' => true,
            'message' => 'New OTP was sent. Please check your email for verification',
            'code' => 'OTP_SENT',
            'data' => [
                'otp' => $otp
            ]
        ], 201);
    }



    public function getLoggedInUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
           return response()->json([
                'success' => false,
                'message' => 'No logged-in user',
                'code' => 'NO_AUTH_USER',
                'data' => null
            ], 404);

        }

        $method = $request->method();
        $url = $request->fullUrl();

       return response()->json([
            'success' => true,
            'message' => 'User fetched',
            'code' => 'USER_FETCHED',
            'data' => [
                'business' => $user
            ]
        ], 200);

    }

    public function getAllUsers()
    {
        $users = User::all();
        if ($users->isEmpty()) {
           return response()->json([
            'success' => false,
            'message' => 'No users found',
            'code' => 'USERS_EMPTY',
            'data' => null
        ], 404);

        }
        return response()->json([
            'success' => true,
            'message' => 'Users fetched',
            'code' => 'USERS_FETCHED',
            'data' => $users
        ], 200);

    }



    // public function getAllCountry(Request $request) {
    //     // $countries = Countries::all();

    //     $countryResponse = $this->fetchcountrylist($request);
    //     $countries = $countryResponse->getData();

    //     // $countries = Currency::orderBy('name', 'asc')->get(); 

    //     // if ($countries->isEmpty()) {
    //     if (!$countries) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No countries found',
    //             'code' => 'COUNTRIES_EMPTY',
    //             'data' => null
    //         ], 404);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Countries fetched',
    //         'code' => 'COUNTRIES_FETCHED',
    //         'data' => [
    //             'countries' => $countries
    //         ]
    //     ], 200);
    // }

    public function getAllCountry(Request $request)
{
    // $countryResponse = $this->fetchcountrylist($request);
    // $countries = $countryResponse->getData();

    $countries = Currency::where('is_active', true)
        ->orderBy('name', 'asc')
        ->get()
        ->map(function ($currency) {
            return [
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'country_code' => strtolower($currency->country_code ?? ''),
                'country_name' => $currency->country_name,

            ];
        })
        ->values();

    if ($countries->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No currencies found',
            'code' => 'CURRENCIES_EMPTY',
            'data' => null
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Currencies fetched',
        'code' => 'CURRENCIES_FETCHED',
        'data' => [
            'countries' => $countries
        ]
    ], 200);
}


    public function deleteUser(Request $request, $email)
    {
        $user = $request->user();
    
        if (!$user) {
           return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'code' => 'UNAUTHORIZED',
                'data' => null
            ], 401);
        }
    
        $userToDelete = User::where('email', $email)->first();
    
        if (!$userToDelete) {
           return response()->json([
                'success' => false,
                'message' => 'User not found',
                'code' => 'USER_NOT_FOUND',
                'data' => null
            ], 404);

        }
    
        // Delete the user
        $userToDelete->delete();
    
        // Return success response
       return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
            'code' => 'USER_DELETED',
            'data' => null
        ], 200);

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
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]+$/'
            ],
            'password_confirmation' => 'required|string|min:8|same:password',
            'country' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'person_phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
           return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'code' => 'VALIDATION_ERROR',
                'data' => $validator->errors()
            ], 422);
        }

        if (
            User::where('email', $request->email)->exists() ||
            Personal::where('email', $request->email)->exists()
        ) {
           return response()->json([
                'success' => false,
                'message' => 'Email already taken',
                'code' => 'EMAIL_TAKEN',
                'data' => null
            ], 422);

        }

        if (
            User::where('person_phone', $request->person_phone)->exists() ||
            Personal::where('person_phone', $request->person_phone)->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Phone already taken',
                'code' => 'PHONE_TAKEN',
                'data' => null
            ], 422);

        }

        $otp = $this->generateOTP();
        $existingcountry = Countries::where('name', $request->country)->first();
        $currency_code = $existingcountry->currency_code;


        // REFERRAL CODE & LINK
        $referral_code = $this->generateReferralCode();
        $referral_link = url('/register/' . $referral_code);

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
            'date_of_birth' => \Carbon\Carbon::parse($request->date_of_birth)->format('d/m/Y'),
            'currency' => $currency_code,
            'referral_code' => $referral_code,
            'referral_link' => $referral_link,
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
        event(new UserRegistered($user));

        return response()->json([
            'success' => true,
            'message' => 'Personal registration successful. Please check your email for verification',
            'code' => 'REGISTERED',
            'data' => [
                'token' => $token,
                // 'email_verification_otp' => $otp
            ]
        ], 201);

    }






     public function verifyPersonalEmail(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|min:6',
            'email' => 'required','string','email','max:255',

        ]);

    
       $user = \App\Models\Personal::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'code' => 'USER_NOT_FOUND',
                'data' => null
            ], 404);

        }    
        if ($user->email_verification_otp !== $request->otp) {
            $user->increment('email_verification_attempts');
    
            if ($user->email_verification_attempts >= 3) {

                return response()->json([
                    'success' => false,
                    'message' => 'Too many attempts. Please request a new OTP.',
                    'code' => 'OTP_TOO_MANY_ATTEMPTS',
                    'data' => null
                ], 422);

            }
    
            $method = $request->method();
            $url = $request->fullUrl();
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
                'code' => 'OTP_INVALID',
                'data' => null
            ], 422);

        }
    
        if ($user->email_verified_status !== 'no') {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified',
                'code' => 'EMAIL_ALREADY_VERIFIED',
                'data' => null
            ], 400);

        }
            // dd($user);

        $user->update([
            'email_verified_at' => now(),
            'email_verified_status' => 'yes',
            'email_verification_attempts' => 0,
        ]);
        
    
      return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'code' => 'EMAIL_VERIFIED',
            'data' => [
                'email' => $user->email
            ]
        ], 200);

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
                'success' => false,
                'message' => 'User not found',
                'code' => 'USER_NOT_FOUND',
                'data' => null
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
            'success' => true,
            'message' => 'New OTP was sent. Please check your email for verification',
            'code' => 'OTP_SENT',
            'data' => [
                'otp' => $otp
            ]
        ], 201);

    }

    public function getLoggedInPersonal(Request $request)
    {
        // Use the personal guard
        $personal = Auth::guard('personal')->user();

        if (!$personal) {
           return response()->json([
                'success' => false,
                'message' => 'No logged-in personal',
                'code' => 'NO_AUTH_USER',
                'data' => null
            ], 404);

        }

        return response()->json([
            'success' => true,
            'message' => 'Personal fetched',
            'code' => 'PERSONAL_FETCHED',
            'data' => [
                'personal' => $personal
            ]
        ], 200);

    }

    public function getAllPersonal()
    {
        $personals = Personal::all();
        if ($personals->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No personals found',
                'code' => 'PERSONALS_EMPTY',
                'data' => null
            ], 404);

        }
        return response()->json([
            'success' => true,
            'message' => 'Personals fetched',
            'code' => 'PERSONALS_FETCHED',
            'data' => $personals
        ], 200);

    }

    public function deletePersonal(Request $request, $email)
    {
        $personal = Auth::guard('personal')->user();

        if (!$personal) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'code' => 'UNAUTHORIZED',
                'data' => null
            ], 401);
        }

        $personalToDelete = Personal::where('email', $email)->first();

        if (!$personalToDelete) {
            return response()->json([
                'success' => false,
                'message' => 'Personal not found',
                'code' => 'PERSONAL_NOT_FOUND',
                'data' => null
            ], 404);

        }

        $personalToDelete->delete();

        return response()->json([
            'success' => true,
            'message' => 'Personal deleted successfully',
            'code' => 'PERSONAL_DELETED',
            'data' => null
        ], 200);

    }


}
