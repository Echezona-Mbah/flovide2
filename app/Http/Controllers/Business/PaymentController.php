<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;
use App\Models\Subaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\payments as payment;
use Illuminate\Support\Str;
use App\Exports\PaymentRecordsBusinessExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class PaymentController extends Controller
{
    public function index()
    {
        // Fetch all payment pages for the authenticated user
        $user = Auth::user();
        $payment = payment::withCount('records')->where('user_id', $user->id)
                    ->latest()
                    ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Payment pages retrieved successfully.',
                    'payments' => $payment
                ]
            ], 200);
        }

        //return the view
        return view('business.payment', ['payments' => $payment]);
    }

    public function paymentcheckout(Request $request, $id)
    {
        $payment = payment::where("payment_reference", $id)->first();
        if (!$payment) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'An error occured or payment not found.'
                    ]
                ], 404);
            }
        }

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Successfully retrieved payment.',
                    'payment' => $payment
                ]
            ], 200);
        }
        //return view
        return view("business.paymentCheckout", compact("payment"));
    }

    public function paymentpay(Request $request) {}

    public function create(Request $request)
    {

        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        if ($request->expectsJson()) {
            if ($subaccounts->isEmpty()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'No subaccount found.'
                    ]
                ], 404);
            }

            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Subaccounts retrieved successfully.',
                    'subaccounts' => $subaccounts,
                ]
            ], 200);
        }
        return view('business.paymentCreate', ['subaccounts' => $subaccounts]);
    }
    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $Payment = payment::with("records")->where('id', $id)->where('user_id', $user->id)->first();

        if (!$Payment) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Payment page not found.'
                    ]
                ], 404);
            }
            return redirect()->route('Payment.index')->with('error', 'Payment page not found.');
        }

        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Payment page retrieved successfully.',
                    'Payment' => $Payment,
                    'subaccounts' => $subaccounts
                ]
            ], 200);
        }

        return view('business.editPayment', ['Payment' => $Payment, 'subaccounts' => $subaccounts]);
    }

    //generate a payment reference code.
    public function generateUniqueReference()
    {
        do {
            $reference = (string) Str::uuid();
        } while (payment::where('payment_reference', $reference)->exists());

        return $reference;
    }

    public function store(Request $request)
    {
        $request->validate([
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120|dimensions:width=1600,height=300', // 5MB
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'subaccount_id' => 'required|string',
            'percentage' => 'required|numeric|min:0|max:100', // Percentage must be between 0 and 100
            'currency' => 'required|string',
            'visibility' => 'required|string',
        ]);

        //get subaccount details
        $subaccount = Subaccount::find($request->subaccount_id);
        if (!$subaccount) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Invalid subaccount selected.'
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['subaccount_id' => 'Invalid subaccount selected.']);
        }

        // Validate subaccount ownership
        if ($subaccount->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'You do not have permission to use this subaccount.'
                    ]
                ], 403);
            }
            return redirect()->back()->withErrors(['subaccount_id' => 'You do not have permission to use this subaccount.']);
        }

        $accountNumber = $subaccount->account_number;
        $accountName = $subaccount->account_name;
        $bankName = $subaccount->bank_name;


        $path = null;
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('payment_cover_image', 'public');
        }

        $user = Auth::user();
        $reference = $request->reference ?? $this->generateUniqueReference();
        $pageLink = url('payment/paymentcheckout/' . $reference);
        // Save to DB
        payment::create([
            'user_id' => $user->id,
            'cover_image' => $path,
            'title' => $request->title,
            'amount' => $request->amount,
            'payment_reference' => $reference,
            'subaccount_id' => $request->subaccount_id,
            'subaccount' => $bankName,
            'subaccount_name' => $accountName,
            'subaccount_number' => $accountNumber,
            'percentage' => $request->percentage ?? 10, // Default to 10% if not provided
            'currency' => $request->currency,
            'visibility' => $request->visibility,
            'page_link' => $pageLink
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Payment page created successfully.'
                ]
            ], 200);
        }
        return redirect()->back()->with('success', 'Payment page created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120|dimensions:width=1600,height=300', // 5MB
            'remove_cover' => 'nullable|in:0,1', // must be 0 or 1
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'subaccount_id' => 'required|string',
            'percentage' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string',
            'visibility' => 'required|string'
        ]);

        //get subaccount details
        $subaccount = Subaccount::find($request->subaccount_id);
        if (!$subaccount) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Invalid subaccount selected.'
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['subaccount_id' => 'Invalid subaccount selected.']);
        }

        // Validate subaccount ownership
        if ($subaccount->user_id !== $user->id) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'You do not have permission to use this subaccount.'
                    ]
                ], 403);
            }
            return redirect()->back()->withErrors(['subaccount_id' => 'You do not have permission to use this subaccount.']);
        }

        $accountNumber = $subaccount->account_number;
        $accountName = $subaccount->account_name;
        $bankName = $subaccount->bank_name;


        $payment = payment::where('id', $id)->where('user_id', $user->id)->first();

        if (!$payment) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'payment not found'
                    ]
                ], 404);
            }
            return redirect()->route('payment.index')->with('error', 'payment page not found or you do not have permission to edit it.');
        }

        $path = null;
        if ($request->remove_cover == 1) {
            if ($payment->cover_image) {
                Storage::disk('public')->delete($payment->cover_image);
            }
            $payment->cover_image = null;
        } elseif ($request->hasFile('cover_image')) {
            if ($payment->cover_image) {
                Storage::disk('public')->delete($payment->cover_image);
            }
            $path = $request->file('cover_image')->store('payment_cover_image', 'public');
            $payment->cover_image = $path;
        }

        $payment->title = $request->title;
        $payment->amount = $request->amount;
        $payment->subaccount_id = $subaccount->id;
        $payment->subaccount = $bankName;
        $payment->subaccount_name = $accountName;
        $payment->subaccount_number = $accountNumber;
        $payment->percentage = $request->percentage ?? $payment->percentage; // Default to existing percentage if not provided
        $payment->currency = $request->currency;
        $payment->visibility = $request->visibility;
        $payment->save();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Remite updated successfully'
                ]
            ], 200);
        }

        return redirect()->route('payment.edit', ['id' => $payment->id])->with('success', 'payment page updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $payment = payment::where('id', $id)->where('user_id', $user->id)->first();

        if (!$payment) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'payment page not found or you do not have permission to delete it.'
                    ]
                ], 404);
            }
            // return redirect()->route('payment.index')->with('error', 'payment page not found or you do not have permission to delete it.');
        }

        $payment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'payment page deleted successfully.'
                ]
            ], 200);
        }
    }

    public function refresh()
    {
        $user = Auth::user();
        $payments = payment::where('user_id', $user->id)->latest()->get();

        if (request()->expectsJson()) {
            if($payments->isEmpty()){
                return response()->json([
                    'data' => [
                        'status' => 'success',
                        'message' => 'No payment pages found.',
                        'payments' => [],
                    ]
                ], 200);
            }
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Payment pages refreshed successfully.',
                    'payments' => $payments,
                ]
            ], 200);
        }

        return redirect()->back()->with('payments', $payments);
    }

    public function refreshDetails(Request $request, $id)
    {
        $user = Auth::user();
        $payment = payment::where('id', $id)->where('user_id', $user->id)->first();

        if (!$payment) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Payment page not found.'
                    ]
                ], 404);
            }
            return redirect()->back()->with('error', 'Payment page not found.');
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

    public function exportUserPayments($id)
    {
        $user = Auth::user();
        $payment = payment::with("records")->where('id', $id)->where('user_id', $user->id)->first();

        if (!$payment) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Payment page not found.'
                    ]
                ], 404);
            }
            return redirect()->back()->with('error', 'Payment page not found.');
        }

        $fileName = 'payment_records_' . $payment->payment_reference . '.xlsx';

        return Excel::download(new PaymentRecordsBusinessExport($payment->id), $fileName, ExcelFormat::XLSX);
    }
}
