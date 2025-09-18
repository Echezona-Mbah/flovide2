<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\payments as payment;
use Illuminate\Support\Str;
use App\Exports\PaymentRecordsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class paymentsController extends Controller
{
    //
    public function index(){
        $user = Auth::guard('personal-api')->user();
        $payments = payment::where('personal_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        
        if ($payments->total() < 1) {
            return response()->json([
                'data' => [
                    'status' => 'empty',
                    'message' => 'No payments found for this user',
                    'payments' => []
                ]
            ], 200);
        }

        //return json
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payments retrieved successfully',
                'payments' => $payments
            ]
        ], 200);
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
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'visibility' => 'required|in:public,private'
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
        ], 200);

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
        ], 200);
    }

    public function destroy(Request $request, $id){
        $user = Auth::guard('personal-api')->user();
        $payment = payment::where('id', $id)
                      ->where('personal_id', $user->id)->first();

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
                'message' => 'Payment deleted successfully'
            ]
        ], 200);
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
        ], 200);
    }

    public function exportUserPayments()
    {
        $user = Auth::guard('personal-api')->user();

        $fileName = 'my_payments_records_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return Excel::download(
            new PaymentRecordsExport($user->id), 
            $fileName, 
            ExcelFormat::CSV
        );
    }
}