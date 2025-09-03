<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\payments as payment;
use Illuminate\Support\Str;

class paymentsController extends Controller
{
    //
    public function index(){
        $user = Auth::guard('personal-api')->user();
        $payments = payment::where('personal_id', $user->id)->paginate(10);
        
        if ($payments->total() < 1) {
            return response()->json([
                'data' => [
                    'status' => 'empty',
                    'message' => 'No payments found for this user',
                    'payments' => []
                ]
            ]);
        }

        //return json
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payments retrieved successfully',
                'payments' => $payments
            ]
        ]);
    }

    public function generateUniqueReference()
    {
        do {
            $reference = (string) Str::uuid();
        } while (payment::where('payment_reference', $reference)->exists());

        return $reference;
    }
    public function store(Request $request){
        $user = Auth::guard('personal-api')->user();

        // Validate request
        $validator = Validator::make($request->all(), [
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'title' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'visibility' => 'nullable|in:public,private',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }
        $path = null;
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('payment_cover_image', 'public');
        }

        // Create payment
        $payment = payment::create([
            'personal_id' => $user->id,
            'cover_image' => $path,
            'title' => $request->title,
            'payment_reference' => $this->generateUniqueReference(), // auto-generate unique reference
            'amount' => $request->amount,
            'currency' => $request->currency ?? 'NGN',
            'visibility' => $request->visibility ?? 'private',
        ]);

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payment created successfully',
                'payment' => $payment
            ]
        ], 201);
    }

    public function update(Request $request, $id){
        $user = Auth::guard('personal-api')->user();

        $payment = payment::where('id', $id)
                        ->where('personal_id', $user->id)
                        ->first();

        if (!$payment) {
            return response()->json([
                'data' => [
                    'status'  => 'error',
                    'message' => 'Payment not found or not authorized',
                ]
            ], 404);
        }

         // Validate fields
        $validator = Validator::make($request->all(), [
            'cover_image' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'visibility' => 'nullable|in:public,private',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }

        // Update only the provided fields
        $payment->update($request->only([
            'cover_image',
            'title',
            'amount',
            'currency',
            'visibility'
        ]));

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payment updated successfully',
                'payment' => $payment
            ]
        ]);

    }

    public function show(Request $request, $id){
        $user = Auth::guard('personal-api')->user();
        $payment = payment::where('id', $id)
                      ->where('personal_id', $user->id)
                      ->first();

        if (!$payment) {
            return response()->json([
                'data' => [
                    'status'  => 'error',
                    'message' => 'Payment not found or not authorized',
                ]
            ], 404);
        }

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payment retrieved successfully',
                'payment' => $payment
            ]
        ]);
    }

    public function destory(Request $request, $id){
        $user = Auth::guard('personal-api')->user();
        $payment = payment::where('id', $id)
                      ->where('personal_id', $user->id)
                      ->first();

        if (!$payment) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Payment not found or not authorized'
                ]
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payment deleted successfully',
            ]
        ]);
    }

    public function paymentrecords(){
        $user = Auth::guard('personal-api')->user();
        // Fetch payments with related records
        $payments = payment::where("personal_id", $user->id)->with('records')->paginate(10);

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payments with records retrieved successfully',
                'payments' => $payments
            ]
        ]);
    }
}
