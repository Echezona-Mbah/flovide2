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

    // ✅ Check if CAC is already uploaded and under review or approved
    if ($user->cac_certificate && Storage::disk('public')->exists($user->cac_certificate)) {
        return response()->json([
            'status'  => false,
            'message' => 'CAC document already uploaded.',
            'data'    => [
                'cac_certificate' => $user->cac_certificate,
                'cac_status'      => $user->cac_status,
            ]
        ], 409); // 409 Conflict
    }

    // ✅ Store new document
    $path = $request->file('document')->store('cac_certificates', 'public');

    $user->cac_certificate = $path;
    $user->cac_status = 'under review';
    $user->save();

    return response()->json([
        'status'  => true,
        'message' => 'CAC document uploaded and under review.',
        'data'    => [
            'cac_certificate' => $path,
            'cac_status'      => $user->cac_status,
        ]
    ], 201);
}


public function handleBvn(Request $request)
{
    $request->validate([
        'bvn' => 'required|digits:11'
    ]);

    $user = auth()->user();

    // ✅ Check if already verified
    if ($user->bvn && $user->bvn_status === 'yes') {
        return response()->json([
            'status'  => false,
            'message' => 'BVN already verified.',
            'data'    => [
                'bvn' => $user->bvn,
            ]
        ], 409);
    }

    $bvn = $request->input('bvn');

    $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
        ->get("https://api.paystack.co/bank/resolve_bvn/{$bvn}");

    if ($response->successful()) {
        $data = $response->json()['data'];

        $user->bvn = $bvn;
        $user->bvn_status = 'yes';
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'BVN verified successfully.',
            'data'    => [
                'bvn'  => $bvn,
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'dob'  => $data['dob']
            ]
        ], 200);
    }

    $errorMessage = $response->json()['message'] ?? 'BVN verification failed.';

    return response()->json([
        'status'  => false,
        'error'   => $errorMessage,
    ], 422);
}


   public function handleValidid(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    // ✅ Check if already uploaded
    if ($user->valid_id && Storage::disk('public')->exists($user->valid_id)) {
        return response()->json([
            'status'  => false,
            'message' => 'Valid ID already uploaded.',
            'data'    => [
                'valid_id'        => $user->valid_id,
                'valid_id_status' => $user->valid_id_status,
            ]
        ], 409);
    }

    // ✅ Store new document
    $path = $request->file('document')->store('valid_ids', 'public');

    $user->valid_id = $path;
    $user->valid_id_status = 'under review';
    $user->save();

    return response()->json([
        'status'  => true,
        'message' => 'Valid ID uploaded and under review.',
        'data'    => [
            'valid_id'        => $path,
            'valid_id_status' => $user->valid_id_status,
        ]
    ], 201);
}

public function handleTin(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    // ✅ Check if already uploaded
    if ($user->tin && Storage::disk('public')->exists($user->tin)) {
        return response()->json([
            'status'  => false,
            'message' => 'Tax Identification Number (TIN) document already uploaded.',
            'data'    => [
                'tin'         => $user->tin,
                'tin_status'  => $user->tin_status,
            ]
        ], 409);
    }

    // ✅ Store new TIN document
    $path = $request->file('document')->store('tin_documents', 'public');

    $user->tin = $path;
    $user->tin_status = 'under review';
    $user->save();

    return response()->json([
        'status'  => true,
        'message' => 'Tax Identification Number (TIN) uploaded and under review.',
        'data'    => [
            'tin'        => $path,
            'tin_status' => $user->tin_status,
        ]
    ], 201);
}


public function handleUtilitybill(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    // ✅ Check if already uploaded
    if ($user->utility_bill && Storage::disk('public')->exists($user->utility_bill)) {
        return response()->json([
            'status'  => false,
            'message' => 'Utility Bill / Proof of Address already uploaded.',
            'data'    => [
                'utility_bill'        => $user->utility_bill,
                'utility_bill_status' => $user->utility_bill_status,
            ]
        ], 409);
    }

    // ✅ Store new Utility Bill document
    $path = $request->file('document')->store('utility_bills', 'public');

    $user->utility_bill = $path;
    $user->utility_bill_status = 'under review';
    $user->save();

    return response()->json([
        'status'  => true,
        'message' => 'Utility Bill / Proof of Address uploaded and under review.',
        'data'    => [
            'utility_bill'        => $path,
            'utility_bill_status' => $user->utility_bill_status,
        ]
    ], 201);
}


}
