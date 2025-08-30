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
        // Fetch all refund requests for the authenticated user
        $refunds = Refund::where('personal_id', $user->id)->orderBy('created_at', 'desc')->get();

        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Refund requests retrieved successfully.',
                'data' => $refunds,
            ], 200);
        }

        return view('business.refunds', ['refunds' => $refunds]);
    }

    public function store(Request $request)
    {
        // Validate
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
                // Generate a 20-digit number
                $ref = '';
                for ($i = 0; $i < 20; $i++) {
                    $ref .= mt_rand(0, 9);
                }
            } while (Refund::where('transaction_ref_number', $ref)->exists());

            return $ref;
        }
        // Generate a unique reference number
        $transactionRef = generateUniqueRefNumber();

        $user = Auth::guard('personal-api')->user();
        try {
            // Create refund record
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
            // If API request → return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Refund request created successfully.',
                    'data'    => $refund
                ]);
            }

            // Else redirect
            return redirect()->back()->with('success', 'Refund request created successfully.');


        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to create refund: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create refund: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Validate
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $user = Auth::guard('personal-api')->user();
        try{
            $refund = Refund::findOrFail($id);

            //authorize only if this refund belongs to the logged-in user
            if ($refund->personal_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not allowed to modify this request.',
                ], 403);
            }

            $refund->action = $validated["status"];
            ($validated["status"] == "rejected") ? $refund->status = "success" : $refund->status = "processing";
            $refund->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Request updated successfully.',
                ], 200);
            }

            return redirect()->back()->with('success', 'Request updated successfully.');    
        }
        catch (\Exception $e){
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to process request: ' . $e->getMessage()
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
