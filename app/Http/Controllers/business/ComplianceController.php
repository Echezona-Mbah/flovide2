<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;



class ComplianceController extends Controller
{
        public function index(Request $request)
    {
    
        return view('business.compliance', [
            'user' => auth()->user()
        ]);
    }

          public function store(Request $request)
    {
        $formType = $request->input('document_type');

        switch ($formType) {
            case 'cac':
                return $this->handleCac($request);
            case 'bvn':
                return $this->handleBvn($request);
            case 'valid_id':
                return $this->handleValidid($request);
            case 'tin':
                return $this->handleTin($request);
            case 'utility_bill':
                return $this->handleUtilitybill($request);
            default:
                return back()->with('error', 'Invalid bill payment type.');
        }
    }


    public function handleCac(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $user = auth()->user();

        if ($user->cac_certificate && Storage::disk('public')->exists($user->cac_certificate)) {
            Storage::disk('public')->delete($user->cac_certificate);
        }

        $path = $request->file('document')->store('cac_certificates', 'public');

        $user->cac_certificate = $path;
        $user->cac_status = 'under review';
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'CAC document uploaded and under review.',
                'data' => [
                    'cac_certificate' => $path,
                    'cac_status' => $user->cac_status,
                ]
            ],201);
        }

        return back()->with('success', 'CAC certificate uploaded successfully and is now under review.');
    }

    public function handleBvn(Request $request)
    {
        $request->validate([
            'bvn' => 'required|digits:11'
        ]);

        $bvn = $request->input('bvn');

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get("https://api.paystack.co/bank/resolve_bvn/{$bvn}");

        if ($response->successful()) {
            $data = $response->json()['data'];

            $user = auth()->user();
            $user->bvn = $bvn;
            $user->bvn_status = 'yes';
            $user->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'BVN verified successfully',
                    'data' => [
                        'bvn' => $bvn,
                        'name' => $data['first_name'] . ' ' . $data['last_name'],
                        'dob' => $data['dob']
                    ]
                ]);
            }

            return back()->with('success', 'BVN verified successfully');
        }
        $errorMessage = $response->json()['message'] ?? 'BVN verification failed.';

        if ($request->expectsJson()) {
            return response()->json(['error' => $errorMessage], 422);
        }

        return back()->with('error', $errorMessage);
    }

     public function handleValidid(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $user = auth()->user();

        if ($user->valid_id && Storage::disk('public')->exists($user->valid_id)) {
            Storage::disk('public')->delete($user->valid_id);
        }

        $path = $request->file('document')->store('ValiadID', 'public');

        $user->valid_id = $path;
        $user->valid_id_status = 'under review';
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Valid ID of Directors/Owners uploaded and under review.',
                'data' => [
                    'valid_id' => $path,
                    'valid_id_status' => $user->valid_id_status,
                ]
            ],201);
        }

        return back()->with('success', 'Valid ID of Directors/Owners uploaded successfully and is now under review.');
    }
     public function handleTin(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $user = auth()->user();

        if ($user->tin && Storage::disk('public')->exists($user->tin)) {
            Storage::disk('public')->delete($user->tin);
        }

        $path = $request->file('document')->store('Tax', 'public');

        $user->tin = $path;
        $user->tin_status = 'under review';
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tax Identification Number (TIN) uploaded and under review.',
                'data' => [
                    'Tax' => $path,
                    'tax_status' => $user->tin_status,
                ]
            ],201);
        }

        return back()->with('success', 'Tax Identification Number (TIN) uploaded successfully and is now under review.');
    }

    public function handleUtilitybill(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $user = auth()->user();

        if ($user->utility_bill && Storage::disk('public')->exists($user->utility_bill)) {
            Storage::disk('public')->delete($user->utility_bill);
        }

        $path = $request->file('document')->store('Utilitybill', 'public');

        $user->utility_bill = $path;
        $user->utility_bill_status = 'under review';
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Utility Bill / Proof of Address document uploaded and under review.',
                'data' => [
                    'utility_bill' => $path,
                    'utility_bill_status' => $user->utility_bill_status,
                ]
            ],201);
        }

        return back()->with('success', 'Utility Bill / Proof of Address uploaded successfully and is now under review.');
    }

}
