<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterOtpMail;
use App\Mail\WelcomeMail;
use App\Models\Countries;
use App\Models\Industry;
use App\Models\State;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $countries = Countries::all();
        $industries = Industry::all();
        $states = State::all();
        return view('auth.register', compact('countries','industries','states'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     return redirect(route('dashboard', absolute: false));
    // }

    
    public function saveStepData(Request $request)
    {
        Log::info('Request data:', ['all_data' => $request->all()]);

        // Validate the input data
        $validated = $request->validate([
            'country' => 'nullable|string|max:255',
            'business-name' => 'nullable|string|max:255',
            'registration-number' => 'nullable|string|max:255',
            'day' => 'nullable|integer|min:1|max:31',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'business-type' => 'nullable|string|max:255',
            'company-url' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'trading_address' => 'nullable|string|max:255',
            'annual-turnover' => 'nullable|string|max:255',
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        $email = $request->input('email');
        $existingUser = User::where('email', $email)->first();
        $existingcountry = Countries::where('code', $request->country)->first();
        $existingcountryname = $existingcountry->name;
        $existingcountrycurrency_code = $existingcountry->currency_code;

         Log::info('Request data:', ['all_data' => $existingcountrycurrency_code ]);
        //   dd($existingcountry);

        // dd($existingcountry);


        
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already taken. Please choose a different email.',
            ], 422);
        }
        
        // dd($request->all());

        // Create a new user instance
        $user = new User();
        $user->countries_id = $existingcountryname?? null;
        $user->business_name = $validated['business-name'] ?? null;
        $user->registration_number = $validated['registration-number'] ?? null;

        // Set the incorporation date if valid day, month, and year are provided
        if (!empty($validated['day']) && !empty($validated['month']) && !empty($validated['year'])) {
            $day = str_pad($validated['day'], 2, '0', STR_PAD_LEFT);
            $month = str_pad($validated['month'], 2, '0', STR_PAD_LEFT);
            $year = $validated['year'];
            $user->incorporation_date = "{$year}-{$month}-{$day}";
        }

        // Assign the rest of the fields
        $user->business_type = $validated['business-type'] ?? null;
        $user->company_url = $validated['company-url'] ?? null;
        $user->industry = $validated['industry'] ?? null;
        $user->annual_turnover = $validated['annual-turnover'] ?? null;
        $user->street_address = $validated['street_address'] ?? null;
        $user->city = $validated['city'] ?? null;
        $user->trading_address = $validated['trading_address'] ?? null;
        $user->nature_of_business = $validated['message'] ?? null;
        $user->email = $validated['email'] ?? null;
        $user->state = $validated['state'] ?? null;
        $user->currency = $existingcountrycurrency_code ?? null;


        // Validate and set the password if provided
        if (isset($validated['password']) && !empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->typeofuser = 'business';

        $email_verification_otp = $this->generateOTP();
        $user->email_verification_otp = $email_verification_otp;
        $user->email_verification_otp_expires_at = now()->addMinutes(5);

        $user->save();

        \App\Models\Balance::create([
            'user_id'  => $user->id,
            'currency' => $user->currency ?? 'USD',
            'name'     => 'Main Balance',
            'balance'  => 0.00,
        ]);

        \App\Models\TeamMembers::create([
            'user_id'  => $user->id,
            'owner_id' => $user->id,
            'email'     => $user->email,
<<<<<<< HEAD
            'role'  => $user->role,
=======
            'role'  => 'Owner',
>>>>>>> 4214aa702807e3d23954c2ccb8c80301d29082d1
        ]);
        Auth::login($user);

        Mail::to($user->email)->send(new WelcomeMail($user));
        Mail::to($user->email)->send(new RegisterOtpMail($email_verification_otp, $user));

        return redirect()->route('verifyemail')->with([
            'success' => true,
            'message' => 'Step 3 completed. OTP sent to email!',
            'email' => $user->email,
        ]);
    }



    public function showverifyEmail()
    {
        $userEmail = session('user_email');

        if (!$userEmail) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        return view('auth.varifyEmail', compact('userEmail'));
    }




    public function verifyEmail(Request $request, $email)
    {

        $otp = implode('', $request->code);

        $request->merge(['otp' => $otp]);

        $request->validate([
            'otp' => 'required|string|min:6',
        ]);

        $user = User::where('email', $email)
            ->where('email_verification_otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid or expired OTP']);
        }

        if ($user->email_verification_otp !== $otp) {
            $user->increment('email_verification_attempts');

            if ($user->email_verification_attempts >= 3) {
                return back()->withErrors(['otp' => 'Too many attempts. Please request a new OTP.']);
            }

            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        if ($user->email_verified_status !== 'no') {
            return redirect()->route('login')->with('status', 'Email already verified.');
        }

        // Mark as verified
        $user->update([
            'email_verified_at' => now(),
            'email_verified_status' => 'yes',
            'email_verification_attempts' => 0,
        ]);

        // Automatically log the user in
        Auth::login($user);

        // dd(($user->countries_id));

    if ($user->countries_id === 'Nigeria') {
        return redirect()->route('verify_bvn')->with('status', 'Email verified. Please verify your BVN.');
    }

        return redirect()->route('dashboard')->with('status', 'Email verified successfully!');
    }


    public function resendOtp($email, Request $request)
    {
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        $otp = rand(100000, 999999);
        $expirationTime = now()->addMinutes(5);
    
        $user->update([
            'email_verification_otp' => $otp,
            'email_verification_otp_expires_at' => $expirationTime,
            'email_verification_attempts' => 0,
        ]);
    
        Mail::to($user->email)->send(new RegisterOtpMail($otp, $user));
    
        return response()->json(['message' => 'OTP has been resent.']);
    }

    public function bvn(){
        return view('auth.varify-bvn');
    }

    public function verifyBVN(Request $request)
    {
        $request->validate([
            'bvn' => 'required|digits:11'
        ]);

        $user = Auth::user();
        $bvn = $request->bvn;

        try {
            $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
                ->get("https://api.paystack.co/bank/resolve_bvn/{$bvn}");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === true) {
                    // Save BVN and mark status as verified
                    $user->update([
                        'bvn' => $bvn,
                        'bvn_status' => 'verified',
                    ]);

                    return redirect()->route('dashboard')->with('status', 'BVN verified successfully!');
                } else {
                    return redirect()->back()->withErrors(['bvn' => $data['message'] ?? 'Verification failed. Try again.']);
                }
            } else {
                return redirect()->back()->withErrors(['bvn' => 'BVN verification failed. Invalid BVN or server error.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['bvn' => 'Something went wrong. Please try again later.']);
        }
    }
    
    








    private function generateOTP()
    {
        return rand(100000, 999999);
    }





    public function fetchBalances()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
            'Accept' => 'application/json',
        ])->get(env('OHENTPAY_BASE_URL') . '/balances');
    
        if ($response->successful()) {
            return $response->json();
        }
    
        return [
            'error' => true,
            'message' => $response->body(),
            'status' => $response->status(),
        ];
    }
    

    public function getBalances()
    {
        $data = $this->fetchBalances();
        return response()->json($data);
    }


    public function createBalance(Request $request)
    {
        // Validate input (optional, but recommended)
        $request->validate([
            'name' => 'required|string',
            'currency' => 'required|string',
            // 'amount' => 'required|numeric',
        ]);
    
        $payload = $request->only(['name', 'currency', 'amount']);
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OHENTPAY_API_KEY'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(env('OHENTPAY_BASE_URL') . '/balances', $payload);
    
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => true,
                'message' => $response->body(),
                'status' => $response->status(),
            ], $response->status());
        }
    }






}
