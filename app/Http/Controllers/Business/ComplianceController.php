<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SumsubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class ComplianceController extends Controller
{
        protected $sumsub;




    //     public function index(Request $request)
    // {
    
    //     return view('business.compliance', [
    //         'user' => auth()->user()
    //     ]);
    // }


    public function index(Request $request)
{
    $actor = auth()->user();
    $team = \App\Models\TeamMembers::where('user_id', $actor->id)->first();
    $isOwner = $team ? false : true;

    $user = $isOwner ? $actor : null;

    return view('business.compliance', compact('user', 'isOwner'));
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

            case 'proof_of_identity':
                return $this->handleProofOfIdentity($request);

            case 'ownership':
                return $this->handleOwnership($request);

            case 'organisational_chart':
                return $this->handleOrganisationalChart($request);

            case 'register_of_directors':
                return $this->handleRegisterOfDirectors($request);

            case 'formation_document':
                return $this->handleFormationDocument($request);

            default:
                return back()->with('error', 'Invalid document type.');
        }
    }


public function handleCac(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->cac_certificate && Storage::disk('public')->exists($user->cac_certificate)) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'CAC document already uploaded.',
                'code' => 'CAC_ALREADY_UPLOADED',
                'data' => [
                    'cac_certificate' => $user->cac_certificate,
                    'cac_status' => $user->cac_status,
                ]
            ], 409);
        }

        return back()->with('error','CAC document already uploaded.');
    }

    $path = $request->file('document')->store('cac_certificates','public');

    $user->cac_certificate = $path;
    $user->cac_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'CAC document uploaded and under review.',
            'code' => 'CAC_UPLOADED',
            'data' => [
                'cac_certificate' => $path,
                'cac_status' => $user->cac_status,
            ]
        ], 201);
    }

    return back()->with('success','CAC document uploaded successfully.');
}



public function handleBvn(Request $request)
{
    $request->validate([
        'bvn' => 'required|digits:11'
    ]);

    $user = auth()->user();

    if ($user->bvn && $user->bvn_status === 'yes') {

        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'BVN already verified.'
            ], 409);
        }

        return back()->with('error','BVN already verified.');
    }

    $bvn = $request->bvn;

    $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
        ->get("https://api.paystack.co/bank/resolve_bvn/{$bvn}");

    if ($response->successful()) {

        $data = $response->json()['data'];

        $user->bvn = $bvn;
        $user->bvn_status = 'yes';
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'BVN verified successfully.',
                'code' => 'BVN_VERIFIED',
                'data' => [
                    'bvn' => $bvn,
                    'name' => $data['first_name'].' '.$data['last_name'],
                    'dob' => $data['dob']
                ]
            ], 200);

        }

        return back()->with('success','BVN verified successfully.');
    }

    $errorMessage = $response->json()['message'] ?? 'BVN verification failed';

    if ($request->expectsJson()) {
        return response()->json([
            'success' => false,
            'message' => $errorMessage,
            'code' => 'BVN_VERIFICATION_FAILED',
            'data' => null
        ], 422);
    }

    return back()->with('error',$errorMessage);
}


public function handleValidid(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->valid_id && Storage::disk('public')->exists($user->valid_id)) {

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => false,
                'message' => 'Valid ID already uploaded.',
                'code' => 'VALID_ID_ALREADY_UPLOADED',
                'data'    => [
                    'valid_id' => $user->valid_id,
                    'valid_id_status' => $user->valid_id_status,
                ]
            ],409);
        }

        return back()->with('error','Valid ID already uploaded.');
    }

    $path = $request->file('document')->store('valid_ids', 'public');

    $user->valid_id = $path;
    $user->valid_id_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'status' => true,
            'message' => 'Valid ID uploaded and under review.',
            'code' => 'VALID_ID_UPLOADED',
            'data' => [
                'valid_id' => $path,
                'valid_id_status' => $user->valid_id_status
            ]
        ],201);
    }

    return back()->with('success','Valid ID uploaded successfully.');
}



public function handleTin(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->tin && Storage::disk('public')->exists($user->tin)) {

        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'TIN document already uploaded.',
                'code' => 'TIN_ALREADY_UPLOADED',
                'data' => [
                    'tin' => $user->tin,
                    'tin_status' => $user->tin_status
                ]
            ],409);
        }

        return back()->with('error','TIN document already uploaded.');
    }

    $path = $request->file('document')->store('tin_documents', 'public');

    $user->tin = $path;
    $user->tin_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'status' => true,
            'message' => 'TIN uploaded and under review.',
            'code' => 'TIN_UPLOADED',
            'data' => [
                'tin' => $path,
                'tin_status' => $user->tin_status
            ]
        ],201);
    }

    return back()->with('success','TIN uploaded successfully.');
}


public function handleUtilitybill(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->utility_bill && Storage::disk('public')->exists($user->utility_bill)) {

        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Utility Bill already uploaded.',
                'code' => 'UTILITY_BILL_ALREADY_UPLOADED',
                'data' => [
                    'utility_bill' => $user->utility_bill,
                    'utility_bill_status' => $user->utility_bill_status
                ]
            ],409);
        }

        return back()->with('error','Utility Bill already uploaded.');
    }

    $path = $request->file('document')->store('utility_bills','public');

    $user->utility_bill = $path;
    $user->utility_bill_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'status'=>true,
            'message'=>'Utility bill uploaded.',
            'code' => 'UTILITY_BILL_UPLOADED',
            'data'=>[
                'utility_bill'=>$path,
                'utility_bill_status'=>$user->utility_bill_status
            ]
        ],201);
    }

    return back()->with('success','Utility bill uploaded successfully.');
}


public function handleProofOfIdentity(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->proof_of_identity && Storage::disk('public')->exists($user->proof_of_identity)) {

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Proof of Identity already uploaded.',
                'code' => 'PROOF_OF_IDENTITY_ALREADY_UPLOADED',
                'data' => [
                    'proof_of_identity' => $user->proof_of_identity,
                    'proof_of_identity_status' => $user->proof_of_identity_status
                ]
            ], 409);
        }

        return back()->with('error','Proof of Identity already uploaded.');
    }

    $path = $request->file('document')->store('proof_of_identity','public');

    $user->proof_of_identity = $path;
    $user->proof_of_identity_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Proof of Identity uploaded.',
            'code' => 'PROOF_OF_IDENTITY_UPLOADED',
            'data' => [
                'proof_of_identity' => $path,
                'proof_of_identity_status' => $user->proof_of_identity_status
            ]
        ], 201);
    }

    return back()->with('success','Proof of Identity uploaded successfully.');
}


public function handleOwnership(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->ownership_document && Storage::disk('public')->exists($user->ownership_document)) {

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Ownership document already uploaded.',
                'code' => 'OWNERSHIP_ALREADY_UPLOADED',
                'data' => [
                    'ownership_document' => $user->ownership_document,
                    'ownership_status' => $user->ownership_status
                ]
            ], 409);
        }

        return back()->with('error','Ownership document already uploaded.');
    }

    $path = $request->file('document')->store('ownership_documents','public');

    $user->ownership_document = $path;
    $user->ownership_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Ownership document uploaded.',
            'code' => 'OWNERSHIP_UPLOADED',
            'data' => [
                'ownership_document' => $path,
                'ownership_status' => $user->ownership_status
            ]
        ], 201);
    }

    return back()->with('success','Ownership document uploaded successfully.');
}


public function handleOrganisationalChart(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->organisational_chart && Storage::disk('public')->exists($user->organisational_chart)) {

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Organisational chart already uploaded.',
                'code' => 'ORGANISATIONAL_CHART_ALREADY_UPLOADED',
                'data' => [
                    'organisational_chart' => $user->organisational_chart,
                    'organisational_chart_status' => $user->organisational_chart_status
                ]
            ], 409);
        }

        return back()->with('error','Organisational chart already uploaded.');
    }

    $path = $request->file('document')->store('organisational_charts','public');

    $user->organisational_chart = $path;
    $user->organisational_chart_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Organisational chart uploaded.',
            'code' => 'ORGANISATIONAL_CHART_UPLOADED',
            'data' => [
                'organisational_chart' => $path,
                'organisational_chart_status' => $user->organisational_chart_status
            ]
        ], 201);
    }

    return back()->with('success','Organisational chart uploaded successfully.');
}


public function handleRegisterOfDirectors(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->register_of_directors && Storage::disk('public')->exists($user->register_of_directors)) {

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Register of Directors already uploaded.',
                'code' => 'REGISTER_OF_DIRECTORS_ALREADY_UPLOADED',
                'data' => [
                    'register_of_directors' => $user->register_of_directors,
                    'register_of_directors_status' => $user->register_of_directors_status
                ]
            ], 409);
        }

        return back()->with('error','Register of Directors already uploaded.');
    }

    $path = $request->file('document')->store('register_of_directors','public');

    $user->register_of_directors = $path;
    $user->register_of_directors_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Register of Directors uploaded.',
            'code' => 'REGISTER_OF_DIRECTORS_UPLOADED',
            'data' => [
                'register_of_directors' => $path,
                'register_of_directors_status' => $user->register_of_directors_status
            ]
        ], 201);
    }

    return back()->with('success','Register of Directors uploaded successfully.');
}


public function handleFormationDocument(Request $request)
{
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ]);

    $user = auth()->user();

    if ($user->formation_document && Storage::disk('public')->exists($user->formation_document)) {

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Formation document already uploaded.',
                'code' => 'FORMATION_DOCUMENT_ALREADY_UPLOADED',
                'data' => [
                    'formation_document' => $user->formation_document,
                    'formation_document_status' => $user->formation_document_status
                ]
            ], 409);
        }

        return back()->with('error','Formation document already uploaded.');
    }

    $path = $request->file('document')->store('formation_documents','public');

    $user->formation_document = $path;
    $user->formation_document_status = 'under review';
    $user->save();

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Formation document uploaded.',
            'code' => 'FORMATION_DOCUMENT_UPLOADED',
            'data' => [
                'formation_document' => $path,
                'formation_document_status' => $user->formation_document_status
            ]
        ], 201);
    }

    return back()->with('success','Formation document uploaded successfully.');
}










private function generateSignature($method, $uri, $body = '')
{
    $ts = time();

    $stringToSign = $ts . strtoupper($method) . $uri . $body;

    return hash_hmac(
        'sha256',
        $stringToSign,
        env('SUMSUB_SECRET_KEY')
    );
}



// public function getSumsubToken($user = null)
// {
//     if (!$user) {
//      $user = auth()->user();
//     }

//     if (!$user) {
//         throw new \Exception("Invalid user passed to getSumsubToken");
//     }

//     $ts = time();
//     $uri = '/resources/accessTokens/sdk';

//     $body = json_encode([
//         'userId' => $user->id,
//         'levelName' => 'id-and-liveness',
//         'ttlInSecs' => 600
//     ]);

//     $signature = hash_hmac(
//         'sha256',
//         $ts.'POST'.$uri.$body,
//         env('SUMSUB_SECRET_KEY')
//     );

//     $client = new \GuzzleHttp\Client();
//     $response = $client->post('https://api.sumsub.com'.$uri, [
//         'headers' => [
//             'X-App-Token' => env('SUMSUB_APP_TOKEN'),
//             'X-App-Access-Ts' => $ts,
//             'X-App-Access-Sig' => $signature,
//             'Content-Type' => 'application/json'
//         ],
//         'body' => $body
//     ]);

//     // ✅ Return decoded array instead of response
//     return json_decode($response->getBody(), true);
// }


public function getSumsubToken()
{
    $userId = auth()->id();
    $ts = time();
    $uri = '/resources/accessTokens/sdk';

    $body = json_encode([
        'userId' => (string) $userId,
        'levelName' => 'id-and-liveness',
        'ttlInSecs' => 600
    ], JSON_UNESCAPED_SLASHES);

    $signature = hash_hmac(
        'sha256',
        $ts . 'POST' . $uri . $body,
        config('services.sumsub.secret_key')
    );

    $client = new Client();

    $response = $client->post(
        'https://api.sumsub.com' . $uri,
        [
            'headers' => [
                'X-App-Token' => config('services.sumsub.app_token'),
                'X-App-Access-Ts' => $ts,
                'X-App-Access-Sig' => $signature,
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ]
    );

    return response()->json(
        json_decode($response->getBody(), true)
    );
}



public function handle(Request $request)
{
    Log::info('Webhook reached');

    $payload = $request->getContent();
    $data = json_decode($payload, true);

    Log::info('Payload decoded', $data ?? []);

    if (!$data) {
        Log::warning('Empty or invalid JSON payload', ['payload' => $payload]);
        return response('Invalid JSON or empty payload', 200);
    }

    // Extract applicant ID
    $applicantId = $data['externalUserId'] ?? $data['applicantId'] ?? null;
    Log::info('Applicant ID extracted', ['applicantId' => $applicantId]);

    // Extract review status
    $reviewStatus = null;
    if (isset($data['reviewResult'])) {
        $reviewStatus = $data['reviewResult']['reviewAnswer'] ?? null;
    } elseif (isset($data['reviewStatus'])) {
        $reviewStatus = $data['reviewStatus'];
    }

    Log::info('Review status extracted', ['reviewStatus' => $reviewStatus]);

    if (!$applicantId) {
        Log::warning('Applicant ID missing', ['payload' => $data]);
        return response('Applicant ID missing', 400);
    }

    if (!$reviewStatus) {
        Log::warning('Status missing', ['payload' => $data]);
        return response('Status missing', 200);
    }

    // --- Update user using Eloquent ---
    $user = User::find($applicantId);
    if (!$user) {
        Log::warning('User not found for applicantId', ['applicantId' => $applicantId]);
        return response('User not found', 404);
    }

    if ($reviewStatus === 'GREEN') {
        $user->identity_verification_status = 'confirmed';
        $user->selfie_verification_status = 'confirmed';
    } elseif ($reviewStatus === 'RED') {
        $user->identity_verification_status = 'rejected';
        $user->selfie_verification_status = 'rejected';
    } else {
        // For statuses like 'init', 'pending', etc.
        $user->identity_verification_status = $reviewStatus;
        Log::info('Sumsub webhook unknown reviewAnswer', [
            'user_id' => $user->id,
            'reviewAnswer' => $reviewStatus
        ]);
    }

    $user->save();

    Log::info('User verification updated', [
        'user_id' => $user->id,
        'identity_verification_status' => $user->identity_verification_status,
        'selfie_verification_status' => $user->selfie_verification_status
    ]);

    return response('OK', 200);
}


public function status(Request $request)
{
    $user = auth()->user();

    $tokenResponse = $this->getSumsubToken()->getData(true);

    return response()->json([
        'status' => true,
        'message' => 'Compliance status fetched successfully',
        'data' => $user->complianceStatus($tokenResponse)
    ]);
}


// public function status(Request $request)
// {
//     $user = auth()->user();
//     $tokenResponse = $this->getSumsubToken()->getData(true);


//     $data = [
//         'cac' => [
//             'document' => $user->cac_certificate,
//             'status' => $user->cac_status
//         ],

//         'bvn' => [
//             'number' => $user->bvn,
//             'status' => $user->bvn_status
//         ],

//         'valid_id' => [
//             'document' => $user->valid_id,
//             'status' => $user->valid_id_status
//         ],

//         'tin' => [
//             'document' => $user->tin,
//             'status' => $user->tin_status
//         ],

//         'utility_bill' => [
//             'document' => $user->utility_bill,
//             'status' => $user->utility_bill_status
//         ],

//         'proof_of_identity' => [
//             'document' => $user->proof_of_identity,
//             'status' => $user->proof_of_identity_status
//         ],

//         'ownership' => [
//             'document' => $user->ownership_document,
//             'status' => $user->ownership_status
//         ],

//         'organisational_chart' => [
//             'document' => $user->organisational_chart,
//             'status' => $user->organisational_chart_status
//         ],

//         'register_of_directors' => [
//             'document' => $user->register_of_directors,
//             'status' => $user->register_of_directors_status
//         ],

//         'formation_document' => [
//             'document' => $user->formation_document,
//             'status' => $user->formation_document_status
//         ],

//         'identity_verification' => [
//             'identity_status' => $user->identity_verification_status,
//             'selfie_status' => $user->selfie_verification_status,
//             'token' => $tokenResponse['token'],
//             'userId' => $tokenResponse['userId']
//         ],
//     ];

//     return response()->json([
//         'status' => true,
//         'message' => 'Compliance status fetched successfully',
//         'data' => $data
//     ]);
// }



// public function createSumsubApplicant(SumsubService $sumsub)
// {
//     $user = auth()->user();

//     if ($user->sumsub_applicant_id) {
//         return response()->json([
//             "status" => true,
//             "message" => "Applicant already exists",
//             "applicant_id" => $user->sumsub_applicant_id
//         ]);
//     }
//     // dd($sumsub->getLevels());
//     $response = $sumsub->createApplicant($user);

//     if (!isset($response['id'])) {
//         return response()->json([
//             "status" => false,
//             "message" => "Failed to create applicant",
//             "response" => $response
//         ]);
//     }

//     $user->sumsub_applicant_id = $response['id'];
//     $user->save();




//     return response()->json([
//         "status" => true,
//         "message" => "Applicant created",
//         "applicant_id" => $response['id']
//     ]);
// }

}
