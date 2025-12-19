<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\DonationRecord;
use Illuminate\Http\Request;
use App\Models\Subaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\DonationRecordsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Illuminate\Support\Facades\Validator;
use App\Models\donations as donation;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    //
    public function donationIndex()
    {
        // Fetch all donations pages for the authenticated user
        $user = Auth::user();
        $donations = donation::withCount("records")->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        if (request()->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donations pages retrieved successfully.',
                    'donations' => $donations
                ]
            ], 200);
        }

        //return the view
        return view('business.donation', ['donations' => $donations]);
    }


    public function donationCreate(Request $request)
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
        return view('business.createdonation', ['subaccounts' => $subaccounts]);
    }

    //generate a unique 13-digit RRR code.
    public function generateUniqueReference()
    {
        do {
            $reference = (string) Str::uuid();
        } while (donation::where('donation_reference', $reference)->exists());

        return $reference;
    }
    public function donationStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'visibility' => 'required|in:public,private',
            'subaccount_id' => 'nullable|exists:subaccounts,id',
            'percentage' => 'nullable|numeric|min:0|max:100',
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

        //get subaccount details
        $subaccount = Subaccount::where('id', $request->input('subaccount_id'))
            ->where('user_id', $user->id)
            ->first();

        if (!$subaccount) {
            if (request()->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Invalid subaccount selected or unauthorised.'
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['subaccount_id' => 'Invalid subaccount selected.']);
        }

        // Handle file upload if exists
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('donation_cover_image', 'public');
        }

        $donation_reference = $this->generateUniqueReference();
        //get page_link
        $pageLink = url('donation/donationcheckout/' . $donation_reference);

        // Create the donation
        $donation = donation::create([
            'user_id' => $user->id,
            'personal_id' => null,
            'cover_image' => $coverImagePath,
            'title' => $request->input('title'),
            'donation_reference' => $donation_reference,
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'visibility' => $request->input('visibility'),
            'page_link' => $pageLink,
            'subaccount_id' => $request->input('subaccount_id'),
            'percentage' => $request->input('percentage') ?? 10,
            'subaccount' => $subaccount->bank_name,
            'subaccount_name' => $subaccount->account_name,
            'subaccount_number' => $subaccount->account_number,
        ]);

        if($request->expectsJson()){
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donation created successfully',
                    'donation' => $donation
                ]
            ], 200);
        }

        return redirect()->back()->with('success', 'Donation created successfully.');
    }

    public function donationEdit(Request $request, $id)
    {
        $user = Auth::user();
        $donation = donation::with("records")->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$donation) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Donation not found or not authorized',
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Donation not found or not authorized.']);
        }

        $subaccounts = Subaccount::where('user_id', $user->id)->get();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donation and subaccounts retrieved successfully.',
                    'donation' => $donation,
                    'subaccounts' => $subaccounts,
                ]
            ], 200);
        }

        return view('business.editdonation', ['donation' => $donation, 'subaccounts' => $subaccounts]);
    }

    public function donationUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $donation = donation::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$donation) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Donation not found or not authorized',
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Donation not found or not authorized.']);
        }

        $validator = Validator::make($request->all(), [
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'visibility' => 'required|in:Public,Private',
            'subaccount_id' => 'nullable|exists:subaccounts,id',
            'percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ]
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //get subaccount details
        $subaccount = Subaccount::where('id', $request->input('subaccount_id'))
            ->where('user_id', $user->id)
            ->first();

        if (!$subaccount) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Invalid subaccount selected or unauthorised.'
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['subaccount_id' => 'Invalid subaccount selected.'])->withInput();
        }

        // Handle file upload if exists
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($donation->cover_image) {
                Storage::disk('public')->delete($donation->cover_image);
            }
            $coverImagePath = $request->file('cover_image')->store('donation_cover_image', 'public');
            $donation->cover_image = $coverImagePath;
        }
        // Update donation details
        $donation->title = $request->input('title');
        $donation->amount = $request->input('amount');
        $donation->currency = $request->input('currency');
        $donation->visibility = $request->input('visibility');
        $donation->subaccount_id = $request->input('subaccount_id');
        $donation->percentage = $request->input('percentage') ?? 10;
        $donation->subaccount = $subaccount->bank_name;
        $donation->subaccount_name = $subaccount->account_name;
        $donation->subaccount_number = $subaccount->account_number;
        $donation->save();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donation updated successfully',
                    'donation' => $donation
                ]
            ], 200);
        }
        return redirect()->back()->with('success', 'Donation updated successfully.');
    }

    public function donationDestroy(Request $request, $id)
    {
        $user = Auth::user();
        $donation = donation::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$donation) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Donation not found or not authorized',
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Donation not found or not authorized.']);
        }

        // Delete cover image if exists
        if ($donation->cover_image) {
            Storage::disk('public')->delete($donation->cover_image);
        }

        // Delete the donation
        $donation->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donation deleted successfully'
                ]
            ], 200);
        }
        return redirect()->back()->with('success', 'Donation deleted successfully.');
    }
    public function donationcheckout(Request $request, $id)
    {
        $donation = donation::where('donation_reference', $id)->first();

        if (!$donation) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Donation not found or not authorized',
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Donation not found or not authorized.']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donation retrieved successfully.',
                    'donation' => $donation,
                ]
            ], 200);
        }
        return view('business.donationCheckout', ['donation' => $donation]);
    }

    public function donationRecords(Request $request, $id)
    {
        $user = Auth::user();
        $donation = donation::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$donation) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Donation not found or not authorized',
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Donation not found or not authorized.']);
        }

        $records = DonationRecord::where('donation_id', $donation->id)->latest()->get();

        if ($request->expectsJson()) {
            if($records->isEmpty()){
                return response()->json([
                    'data' => [
                        'status' => 'success',
                        'message' => 'Donation record is empty.',
                        'records' => []
                    ]
                ], 200);
            }
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donation records retrieved successfully.',
                    'records' => $records,
                ]
            ], 200);
        }

        return view('business.donationRecords', ['donation' => $donation, 'records' => $records]);
    }

    public function donationRefresh(Request $request)
    {
        $user = Auth::user();
        $donations = donation::where('user_id', $user->id)->latest()->get();

        if ($request->expectsJson()) {
            if($donations->isEmpty()){
                return response()->json([
                    'data' => [
                        'status' => 'success',
                        'message' => 'No donations found.',
                        'donations' => []
                    ]
                ], 200);
            }
            return response()->json([
                'data' => [
                    'status' => 'success',
                    'message' => 'Donations refreshed successfully.',
                    'donations' => $donations,
                ]
            ], 200);
        }

        return redirect()->back()->with('success', 'Donations refreshed successfully.');
    }

    public function exportUserDonation(Request $request, $id)
    {
        $user = Auth::user();
        $donation = donation::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$donation) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => [
                        'status' => 'error',
                        'message' => 'Donation not found or not authorized',
                    ]
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Donation not found or not authorized.']);
        }

        $fileName = 'donation_records_' . $donation->donation_reference . '.xlsx';

        return Excel::download(new DonationRecordsExport($donation->id), $fileName, ExcelFormat::XLSX);
    }
}
