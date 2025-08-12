<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\ChargeBacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChargebackController extends Controller
{

 public function index(Request $request)
{
    $user = auth()->user();
    $chargebacks = ChargeBacks::where('user_id', $user->id)->get();

    // API response
    if ($request->expectsJson()) {
        if ($chargebacks->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No chargebacks found',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Chargebacks fetched successfully',
            'data' => $chargebacks
        ]);
    }

    // Web response
    return view('business.chargeback', [
        'chargebacks' => $chargebacks,
        'message' => $chargebacks->isEmpty() ? 'No chargebacks found' : null
    ]);
}


    public function submitEvidence(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:charge_backs,id',
            'evidence' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // max 10MB
            'note' => 'nullable|string',
        ]);

        $chargeback = ChargeBacks::findOrFail($request->id);
        if ($request->hasFile('evidence')) {
            if ($chargeback->evidence_path && Storage::disk('public')->exists($chargeback->evidence_path)) {
                Storage::disk('public')->delete($chargeback->evidence_path);
            }
            $filePath = $request->file('evidence')->store('evidences', 'public');
            $chargeback->evidence_path = $filePath;
        }


        $chargeback->note = $request->note;
        $chargeback->status = 'Evidence submitted';
        $chargeback->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Evidence submitted successfully.',
                'data' => $chargeback
            ], 200);
        }

        return redirect()->back()->with('success', 'Evidence submitted successfully.');
    }



}
