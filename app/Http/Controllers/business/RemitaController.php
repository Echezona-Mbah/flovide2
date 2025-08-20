<?php

namespace App\Http\Controllers\business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Remita;
use App\Models\RemitaPayment;
use App\Models\Subaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class RemitaController extends Controller
{
    //
    public function index()
    {
        // Fetch all remita pages for the authenticated user
        $user = Auth::user();
        $remitas = Remita::where('user_id', $user->id)->get();

        // Get all remita_ids from the collection
        $remitaIds = $remitas->pluck('id'); 

        //fetch remita_payment pages
        $paymentCount = RemitaPayment::whereIn('remita_id', $remitaIds)->count();
        
        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Remita pages retrieved successfully.',
                'data' => $remitas,
                'remita_payments_count' => $paymentCount,
            ], 200);
        }

        //return the view
        return view('business.remita', ['remitas' => $remitas, 'remita_payments_count' => $paymentCount]);
    }

    public function create(Request $request)
    {

        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();
        
        if ($request->expectsJson()) {
            if ($subaccounts->isEmpty()) {
                return response()->json(['message' => 'No subaccount found.'], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Subaccounts retrieved successfully.',
                'data' => $subaccounts,
            ], 200);
        }
        return view('business.remitaCreate', ['subaccounts' => $subaccounts]);
    }
    public function update()
    {
        return view('business.remitaPayment');
    }

    //generate a unique 13-digit RRR code.
    protected function generateRRR()
    {
        do {
            $rrr = sprintf(
                "RRR-%04d-%04d-%04d-flovide",
                mt_rand(0, 9999),
                mt_rand(0, 9999),
                mt_rand(0, 99999)
            );

            // Ensure uniqueness in the Remita table
            $exists = Remita::where('rrr', $rrr)->exists();
        } while ($exists);

        return $rrr;
    }

    public function store(Request $request)
    {
        $request->validate([
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120|dimensions:width=1600,height=300', // 5MB
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            // 'rrr' => 'nullable|string|max:50',
            'service_type' => 'required|string',
            'subaccount_id' => 'required|string',
            'percentage' => 'required|numeric|min:0|max:100', // Percentage must be between 0 and 100
            'currency' => 'required|string',
            'visibility' => 'required|string',
        ]);

        //get subaccount details
        $subaccount = Subaccount::find($request->subaccount_id);
        if (!$subaccount) {
            return redirect()->back()->withErrors(['subaccount_id' => 'Invalid subaccount selected.']);
        }

        // Validate subaccount ownership
        if ($subaccount->user_id !== Auth::id()) {
            return redirect()->back()->withErrors(['subaccount_id' => 'You do not have permission to use this subaccount.']);
        }

        $accountNumber = Crypt::decryptString($subaccount->account_number);
        $accountName = $subaccount->account_name;
        $bankName = $subaccount->bank_name;


        $path = null;
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('remita_cover_image', 'public');
        }

        $user = Auth::user();
        $rrr = $request->rrr ?? $this->generateRRR();
        // Save to DB
        Remita::create([
            'user_id' => $user->id,
            'cover_image' => $path,
            'title' => $request->title,
            'amount' => $request->amount,
            'rrr' => $rrr,
            'service_type' => $request->service_type,
            'subaccount' => $bankName,
            'subaccount_name' => $accountName,
            'subaccount_number' => $accountNumber,
            'percentage' => $request->percentage ?? 10, // Default to 10% if not provided
            'currency' => $request->currency,
            'visibility' => $request->visibility,
        ]);

        return redirect()->back()->with('success', 'Remita page created successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $remita = Remita::where('id', $id)->where('user_id', $user->id)->first();

        if (!$remita) {
            return redirect()->route('remita.index')->with('error', 'Remita page not found or you do not have permission to delete it.');
        }

        $remita->delete();

        return redirect()->route('remita.index')->with('success', 'Remita page deleted successfully.');
    }
}
