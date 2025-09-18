<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Remita;
use App\Models\RemitaPayment;
use App\Models\Subaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Exports\RemitaRecordsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class RemitaController extends Controller
{
    //
    public function index()
    {
        // Fetch all remita pages for the authenticated user
        $user = Auth::user();
        $remitas = Remita::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        // Get all remita_ids from the collection
        $remitaIds = $remitas->pluck('id');

        //fetch remita_payment pages
        $paymentCount = RemitaPayment::whereIn('remita_id', $remitaIds)->count();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Remita pages retrieved successfully.',
                    'remitas' => $remitas,
                    'remita_payments_count' => $paymentCount,
                ]
            ], 200);
        }

        //return the view
        return view('business.remita', ['remitas' => $remitas, 'remita_payments_count' => $paymentCount]);
    }

    public function create(Request $request)
    {

        $user = Auth::user();
        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        //Decrypt account_number or iban before sending to view/JSON
        $subaccounts->transform(function ($subaccount) {
            try {
                if (!empty($subaccount->account_number)) {
                    $subaccount->decrypted_account = Crypt::decryptString($subaccount->account_number);
                } elseif (!empty($subaccount->iban)) {
                    $subaccount->decrypted_account = Crypt::decryptString($subaccount->iban);
                } else {
                    $subaccount->decrypted_account = null;
                }
            } catch (\Exception $e) {
                // fallback if decryption fails
                $subaccount->decrypted_account = null;
            }
            return $subaccount;
        });

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
        return view('business.remitaCreate', ['subaccounts' => $subaccounts]);
    }
    public function edit(Request $request, $id)
    {
        $user = Auth::user();
        $remita = Remita::where('id', $id)->where('user_id', $user->id)->first();

        if (!$remita) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'message' => 'Remita page not found.'
                    ]
                ], 404);
            }
            return redirect()->route('remita.index')->with('error', 'Remita page not found.');
        }

        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        //Decrypt account_number or iban before sending to view/JSON
        $subaccounts->transform(function ($subaccount) {
            try {
                if (!empty($subaccount->account_number)) {
                    $subaccount->decrypted_account = Crypt::decryptString($subaccount->account_number);
                } elseif (!empty($subaccount->iban)) {
                    $subaccount->decrypted_account = Crypt::decryptString($subaccount->iban);
                } else {
                    $subaccount->decrypted_account = null;
                }
            } catch (\Exception $e) {
                // fallback if decryption fails
                $subaccount->decrypted_account = null;
            }
            return $subaccount;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Remita page retrieved successfully.',
                    'remita' => $remita,
                    'subaccounts' => $subaccounts
                ]
            ], 200);
        }

        return view('business.remitaPayment', ['remita' => $remita, 'subaccounts' => $subaccounts]);
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
            'service_type' => 'required|string',
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
            'subaccount_id' => $request->subaccount_id,
            'subaccount' => $bankName,
            'subaccount_name' => $accountName,
            'subaccount_number' => $accountNumber,
            'percentage' => $request->percentage ?? 10, // Default to 10% if not provided
            'currency' => $request->currency,
            'visibility' => $request->visibility,
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Remita page created successfully.'
                ]
            ], 200);
        }
        return redirect()->back()->with('success', 'Remita page created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120|dimensions:width=1600,height=300', // 5MB
            'remove_cover' => 'nullable|in:0,1', // must be 0 or 1
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'service_type' => 'required|string',
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

        $accountNumber = Crypt::decryptString($subaccount->account_number);
        $accountName = $subaccount->account_name;
        $bankName = $subaccount->bank_name;


        $remita = Remita::where('id', $id)->where('user_id', $user->id)->first();

        if (!$remita) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Remita not found'
                    ]
                ], 404);
            }
            return redirect()->route('remita.index')->with('error', 'Remita page not found or you do not have permission to edit it.');
        }

        $path = null;
        if ($request->remove_cover == 1) {
            if ($remita->cover_image) {
                Storage::disk('public')->delete($remita->cover_image);
            }
            $remita->cover_image = null;
        } elseif ($request->hasFile('cover_image')) {
            if ($remita->cover_image) {
                Storage::disk('public')->delete($remita->cover_image);
            }
            $path = $request->file('cover_image')->store('remita_cover_image', 'public');
            $remita->cover_image = $path;
        }

        $remita->title = $request->title;
        $remita->amount = $request->amount;
        $remita->service_type = $request->service_type;
        $remita->subaccount_id = $subaccount->id;
        $remita->subaccount = $bankName;
        $remita->subaccount_name = $accountName;
        $remita->subaccount_number = $accountNumber;
        $remita->percentage = $request->percentage ?? $remita->percentage; // Default to existing percentage if not provided
        $remita->currency = $request->currency;
        $remita->visibility = $request->visibility;
        $remita->save();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Remite updated successfully'
                ]
            ], 200);
        }

        return redirect()->route('remita.edit', ['id' => $remita->id])->with('success', 'Remita page updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $remita = Remita::where('id', $id)->where('user_id', $user->id)->first();

        if (!$remita) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Remita page not found or you do not have permission to delete it.'
                    ]
                ], 404);
            }
            // return redirect()->route('remita.index')->with('error', 'Remita page not found or you do not have permission to delete it.');
        }

        $remita->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Remita page deleted successfully.'
                ]
            ], 200);
        }
    }

    public function exportUserRemita(Request $request, $id)
    {
        $fileName = 'my_remita_records_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $user = Auth::user();
        $remita = Remita::where('id', $id)->where('user_id', $user->id)->first();
        if(!$remita){
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Remita records not found'
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Remita records not found']);
        }
        return Excel::download(
            new RemitaRecordsExport($remita->id), 
            $fileName, 
            ExcelFormat::CSV
        );
    }
}
