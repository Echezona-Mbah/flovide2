<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\payments as payment;
use App\Models\PaymentRecord;
use App\Models\Subaccount;
use Illuminate\Support\Str;
use App\Exports\PaymentRecordsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class paymentsController extends Controller
{
    //
    public function index()
    {
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

    //fetch users added sub account
    public function subaccount()
    {
        try {
            $user = Auth::guard('personal-api')->user();
            $subaccounts = Subaccount::where('personal_id', $user->id)->get();

            if ($subaccounts->isEmpty()) {
                return response()->json([
                    'data' => [
                        'status' => 'success',
                        'message' => 'No subaccounts available for this user.',
                        'subaccount' => []
                    ]
                ], 200);
            }

            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Subaccount fetched.',
                    'subaccount' => $subaccounts
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Failed to fetch subaccounts.',
                    // 'error' => $e->getMessage()
                ]
            ], 500);
        }
    }
    public function generateUniqueReference()
    {
        do {
            $reference = (string) Str::uuid();
        } while (payment::where('payment_reference', $reference)->exists());

        return $reference;
    }
    public function store(Request $request)
    {
        $user = Auth::guard('personal-api')->user();

        // Validate request
        $validator = Validator::make($request->all(), [
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'subaccount_id' => 'nullable|string|max:50',
            'subaccount' => 'nullable|string|max:100',
            'subaccount_name' => 'nullable|string|max:150',
            'subaccount_number' => 'nullable|digits_between:9,15',
            'percentage' => 'nullable|numeric|min:0|max:100', // Percentage must be between 0 and 100
            'currency' => 'required|string|size:3', // ISO currency codes are usually 3 chars (e.g., USD, NGN)
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

        // auto-generate unique reference
        $reference = $request->reference ?? $this->generateUniqueReference();
        $pageLink = url('payment/paymentcheckout/' . $reference);
        // Create payment
        $payment = payment::create([
            'personal_id' => $user->id,
            'cover_image' => $path,
            'title' => $request->title,
            'payment_reference' => $reference,
            'amount' => $request->amount,
            'subaccount_id' => $request->subaccount_id,
            'subaccount' => $request->subaccount,
            'subaccount_name' => $request->subaccount_name,
            'subaccount_number' => $request->subaccount_number,
            'percentage' => $request->percentage,
            'currency' => $request->currency ?? 'NGN',
            'visibility' => $request->visibility ?? 'private',
            'page_link' => $pageLink
        ]);

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payment created successfully',
                'payment' => $payment
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
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
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'visibility' => 'required|in:public,private',
            'subaccount_id' => 'required|string|max:50',
            'percentage' => 'required|numeric|min:0|max:100',
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

        //get subaccount details with subaccount_id
        $subaccountId = $request->input('subaccount_id');
        $subaccount = Subaccount::where('personal_id', $user->id)
            ->where('id', $subaccountId)->first();

        if (!$subaccount) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Subaccount not found for the provided subaccount_id ' . $subaccountId,
                ]
            ], 404);
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if it exists
            if ($payment->cover_image && Storage::disk('public')->exists($payment->cover_image)) {
                Storage::disk('public')->delete($payment->cover_image);
            }

            // Store new image
            $path = $request->file('cover_image')->store('payment_cover_image', 'public');
            $payment->cover_image = $path;
        }

        // Update payment fields
        $payment->fill([
            'title' => $request->input('title'),
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'visibility' => $request->input('visibility'),
            'subaccount_id' => $request->input('subaccount_id'),
            'percentage' => $request->input('percentage'),
            'subaccount' => $subaccount->bank_name,
            'subaccount_name' => $subaccount->account_name,
            'subaccount_number' => $subaccount->account_number,
        ])->save();

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Payment updated successfully',
                'payment' => $payment
            ]
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $user = Auth::guard('personal-api')->user();
        $payment = payment::where('id', $id)
            ->where('personal_id', $user->id)->first();

        if (!$payment) {
            return response()->json([
                'data' => [
                    'status' => 'error',
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

    public function destroy(Request $request, $id)
    {
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

    public function paymentrecords()
    {
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

    public function records(Request $request, $id)
    {
        $user = Auth::guard('personal-api')->user();
        // Fetch payments records
        $records = PaymentRecord::where("payment_id", $id)->where('personal_id', $user->id)->paginate(10);

        // If no records found, return error response
        if ($records->isEmpty()) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'No records found or unauthorized access'
                ]
            ], 404);
        }
        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Records retrieved successfully',
                'records' => $records
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

    public function refresh()
    {
        $user = Auth::guard('personal-api')->user();

        $payments = Payment::where('personal_id', $user->id)
            ->latest()
            ->get();
        
        if($payments->isEmpty()){
            return response()->json([
                'data' => [
                    'status' => 'empty',
                    'message' => 'No payments found for this user',
                    'payments' => []
                ]
            ], 200);
        }

        return response()->json([
            'data' => [
                'status' => 'success',
                'message' => 'Latest payment records fetched successfully',
                'payments' => $payments
            ]
        ], 200);
    }

    public function refreshDetails(Request $request, $id)
    {
        $user = Auth::guard('personal-api')->user();

        $payment = Payment::where('id', $id)
            ->where('personal_id', $user->id)
            ->first();

        if (!$payment) {
            return response()->json([
                'data' => [
                    'status' => 'error',
                    'message' => 'Payment not found or not authorized',
                ]
            ], 404);
        }

        // Fetch only the records related to this payment
        $records = $payment->records()->latest()->get();

        if (request()->expectsJson()) {
            if($records->isEmpty()){
                return response()->json([
                    'data' => [
                        'status' => 'success',
                        'message' => 'No payment records found for this payment page.',
                        'records' => [],
                    ]
                ], 200);
            }
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Payment records refreshed successfully.',
                    'records' => $records,
                ]
            ], 200);
        }
    }
}
