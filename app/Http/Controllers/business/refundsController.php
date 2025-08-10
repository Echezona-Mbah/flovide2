<?php

namespace App\Http\Controllers\business;

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
        // Fetch all refund requests for the authenticated user
        $refunds = Refund::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

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
            } while (\App\Models\Refund::where('transaction_ref_number', $ref)->exists());

            return $ref;
        }
        // Generate a unique reference number
        $transactionRef = generateUniqueRefNumber();

        try {
            // Create refund record
            $refund = Refund::create([
                'user_id' => Auth::id(),
                'name' => $validated['fullname'],
                'amount'   => $validated['amount'],
                'type'   => $validated['method'],
                'reason'   => $validated['reason'],
                'transaction_ref_number' => $transactionRef,
                'currency' => 'NGN',
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

    public function show($id)
    {

        return response()->json(['message' => 'Refund request details for ID: ' . $id], 200);
    }

    public function update(Request $request, $id)
    {

        return response()->json(['message' => 'Refund request updated successfully.'], 200);
    }

    public function destroy($id)
    {

        return response()->json(['message' => 'Refund request deleted successfully.'], 200);
    }

    public function create()
    {

        return view('business.create_refund');
    }

    public function edit($id)
    {

        return view('business.edit_refund', ['id' => $id]);
    }

    public function search(Request $request)
    {

        $query = $request->input('query');
        // Perform search logic here, e.g., querying the database

        return response()->json(['message' => 'Search results for: ' . $query], 200);
    }

    public function exportCsv()
    {

        return response()->json(['message' => 'CSV export functionality not implemented yet.'], 501);
    }



}
