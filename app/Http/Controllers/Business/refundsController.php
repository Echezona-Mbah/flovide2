<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Refund;

class refundsController extends Controller
{
    //

 public function index()
    {
        $user = Auth::user();

        $refunds = Refund::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Refund requests retrieved successfully.',
                'code' => 'REFUNDS_FETCHED',
                'data' => $refunds,
            ], 200);
        }

        return view('business.refunds', ['refunds' => $refunds]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'referenceNumber' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:50',
            'currency' => 'required|string|in:NGN,USD,EUR,GBP,KES,ZAR,GHS',
            'reason' => 'required|string|max:500',
        ]);

        $exists_for_user = Refund::where('referenceNumber', $validated['referenceNumber'])
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists_for_user) {
            return response()->json([
                'success' => false,
                'message' => 'You already used this reference number.',
                'code' => 'REFERENCE_USED',
                'data' => null
            ], 422);
        }

        $check_reference_number = Refund::where('referenceNumber', $validated['referenceNumber'])->exists();

        if ($check_reference_number) {
            return response()->json([
                'success' => false,
                'message' => 'This reference number already exists.',
                'code' => 'REFERENCE_EXISTS',
                'data' => null
            ], 422);
        }

        function generateUniqueRefNumber()
        {
            do {
                $ref = '';
                for ($i = 0; $i < 20; $i++) {
                    $ref .= mt_rand(0, 9);
                }
            } while (Refund::where('transaction_ref_number', $ref)->exists());

            return $ref;
        }

        $transactionRef = generateUniqueRefNumber();

        try {
            $refund = Refund::create([
                'user_id' => Auth::id(),
                'name' => $validated['fullname'],
                'referenceNumber'   => $validated['referenceNumber'],
                'amount'   => $validated['amount'],
                'type'   => $validated['method'],
                'reason'   => $validated['reason'],
                'transaction_ref_number' => $transactionRef,
                'currency' => $validated['currency'],
                'recipient' => 'self',
                'status'   => 'pending',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Refund request created successfully.',
                    'code' => 'REFUND_CREATED',
                    'data' => $refund
                ], 201);
            }

            return redirect()->back()->with('success', 'Refund request created successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create refund: ' . $e->getMessage(),
                    'code' => 'REFUND_CREATE_FAILED',
                    'data' => null
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create refund: ' . $e->getMessage());
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        try {
            $refund = Refund::findOrFail($id);

            if ($refund->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to modify this request.',
                    'code' => 'FORBIDDEN',
                    'data' => null
                ], 403);
            }

            $refund->action = $validated["status"];
            ($validated["status"] == "rejected") ? $refund->status = "success" : $refund->status = "processing";
            $refund->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request updated successfully.',
                    'code' => 'REFUND_UPDATED',
                    'data' => null
                ], 200);
            }

            return redirect()->back()->with('success', 'Request updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process request: ' . $e->getMessage(),
                    'code' => 'REFUND_UPDATE_FAILED',
                    'data' => null
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to process request: ' . $e->getMessage());
        }
    }



    public function search(Request $request)
    {

        $query = $request->input('query');

        return response()->json(['message' => 'Search results for: ' . $query], 200);
    }

    // get a user refund by id
    public function fetchRefund($id)
    {
        $user = Auth::user();
        $refund = Refund::where('user_id', $user->id)->find($id);
        if (!$refund) {
            return response()->json([
                'status' => 'error',
                'message' => 'Refund request not found.',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Refund request retrieved successfully.',
            'data' => $refund,
        ], 200);
    }
}
