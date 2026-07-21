<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChargeBacks;
use Illuminate\Http\Request;

class AllChargebackController extends Controller
{
        public function index(Request $request)
    {

        $Chargebacks = ChargeBacks::orderBy('created_at', 'desc')->paginate(4);

        return view('admin.Chargeback', compact('Chargebacks'));

    }

    // ChargebackController.php
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string|in:new,resolved,evidence submitted,evidence rejected'
    ]);

    $chargeback = ChargeBacks::findOrFail($id);
    $chargeback->status = $request->status;
    $chargeback->save();

    return response()->json([
        'success' => true,
        'message' => "Chargeback status updated to '{$chargeback->status}'"
    ]);
}


public function submitEvidence(Request $request)
{
    // Validate request
    $request->validate([
        'transaction_id' => 'required',
        'user_id' => 'required|exists:users,id',
        'currency' => 'required|string',
        'cbReference' => 'required|string',
        'name' => 'required|string',
        'deadline' => 'required|string',
        'reason' => 'nullable|string',
        'amount' => 'nullable|string',
    ]);

    // Check if a chargeback already exists
    $existing = ChargeBacks::where('transaction_reference', $request->cbReference)->first();
    if ($existing) {
        return redirect()->back()->with('error', 'Chargeback already submitted for this transaction.');
    }

    // Create new chargeback
    $chargeback = new ChargeBacks();
    $chargeback->user_id = $request->user_id ?? null;
    $chargeback->currency = $request->currency;
    $chargeback->status = 'New';
    $chargeback->transaction_reference = $request->cbReference;
    $chargeback->name = $request->name;
    $chargeback->deadline = $request->deadline;
    $chargeback->reason = $request->reason;

    // Clean the amount so it becomes numeric
    $amountClean = floatval(str_replace(['NGN', '$', '₦', ',', ' '], '', $request->amount));
    $chargeback->amount = $amountClean;

    $chargeback->save();

return redirect()->route('admin.chargeback')->with('success', 'Chargeback submitted successfully.');
}




}
