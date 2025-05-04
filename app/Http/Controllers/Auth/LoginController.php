<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
                        $token = $user->createToken('API Token')->plainTextToken;
                $filteredUser = [
                'id' => $user->id,
                'business_name' => $user->business_name,
                'registration_number' => $user->registration_number,
                'incorporation_date' => $user->incorporation_date,
                'business_type' => $user->business_type,
                'company_url' => $user->company_url,
                'industry' => $user->industry,
                'annual_turnover' => $user->annual_turnover,
                'street_address' => $user->street_address,
                'city' => $user->city,
                'trading_address' => $user->trading_address,
                'nature_of_business' => $user->nature_of_business,
                'trading_street_address' => $user->trading_street_address,
                'trading_city' => $user->trading_city,
                'state' => $user->state,
                'typeofuser' => $user->typeofuser,
                'bvn' => $user->bvn,
                'email' => $user->email,
                'email_verified_status'=> $user->email_verified_status,
                'forgot_password_token'=> $user->forgot_password_token,
                'created_at'=> $user->created_at,
                'updated_at'=> $user->updated_at,
            ];
    
            return response()->json([
                'data' => [
                    'user' => $filteredUser,
                    'token' => $token,
                ],
                'method' => $request->method(),
                'url' => $request->fullUrl(),
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid credentials',
                'method' => $request->method(),
                'url' => $request->fullUrl(),
            ], 401);
        }
    }
    
}
