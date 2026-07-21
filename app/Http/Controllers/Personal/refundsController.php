<?php

namespace App\Http\Controllers\Personal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Refund;

class refundsController extends Controller
{
    //

    public function index()
    {
        $user = Auth::guard('personal-api')->user();

        $refunds = Refund::where('personal_id', $user->id)->orderBy('created_at', 'desc')->get();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Refund requests retrieved successfully.',
                'code' => 'REFUNDS_FETCHED',
                'data' => $refunds
            ], 200);
        }

        return view('business.refunds', ['refunds' => $refunds]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'amount'   => 'required|numeric|min:0',
            'method'   => 'required|string|max:100',
            'currency' => 'required|string|in:NGN,USD,EUR,GBP,KES,ZAR,GHS',
            'reason'   => 'required|string|max:500',
        ]);

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
        $user = Auth::guard('personal-api')->user();

        try {
            $refund = Refund::create([
                'personal_id' => $user->id,
                'name' => $validated['fullname'],
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

        $user = Auth::guard('personal-api')->user();

        try {
            $refund = Refund::findOrFail($id);

            if ($refund->personal_id !== $user->id) {
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
        $user = Auth::guard("personal-api")->user();
        $refund = Refund::where('personal_id', $user->id)->find($id);

        if (!$refund) {
            return response()->json([
                'success' => false,
                'message' => 'Refund request not found.',
                'code' => 'REFUND_NOT_FOUND',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Refund request retrieved successfully.',
            'code' => 'REFUND_FETCHED',
            'data' => $refund
        ], 200);
    }


}
