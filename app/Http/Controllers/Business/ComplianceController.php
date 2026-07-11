<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SumsubService;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComplianceController extends Controller
{
    protected $sumsub;
    protected FirebaseNotificationService $firebase;

    public function __construct(FirebaseNotificationService $firebase)
    {
        $this->firebase = $firebase;
    }

    // ── Shared notification helper ──────────────────────────────────────────
    protected function sendComplianceNotification(User $user, string $title, string $body, array $data = []): void
    {
        if (empty($user->device_token)) {
            return;
        }

        $sent = $this->firebase->sendToToken($user->device_token, $title, $body, array_merge([
            'type' => 'compliance',
        ], $data));

        if (!$sent) {
            Log::warning('Compliance push notification failed', [
                'user_id' => $user->id,
                'title' => $title,
            ]);
        }
    }

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

            case 'nin':
                return $this->handleNin($request);

            default:
                return back()->with('error', 'Invalid document type.');
        }
    }

    public function handleNin(Request $request)
    {
        $request->validate([
            'nin' => 'required|digits:11'
        ]);

        $user = auth()->user();

        if ($user->nin && $user->nin_status === 'under review') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIN already submitted.',
                    'code' => 'NIN_ALREADY_SUBMITTED',
                    'data' => [
                        'nin' => $user->nin,
                        'nin_status' => $user->nin_status,
                    ]
                ], 409);
            }

            return back()->with('error', 'NIN already submitted.');
        }

        $ninExists = User::where('nin', $request->nin)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($ninExists) {
            return response()->json([
                'success' => false,
                'message' => 'This NIN is already linked to another business account.',
                'code' => 'NIN_DUPLICATE',
            ], 409);
        }

        $user->nin = $request->nin;
        $user->nin_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'NIN Submitted',
            'Your NIN has been submitted and is under review.',
            ['document' => 'nin', 'status' => 'under review']
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'NIN submitted successfully.',
                'code' => 'NIN_SUBMITTED',
                'data' => [
                    'nin' => $user->nin,
                    'nin_status' => $user->nin_status,
                ]
            ], 201);
        }

        return back()->with('success', 'NIN submitted successfully.');
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

            return back()->with('error', 'CAC document already uploaded.');
        }

        $path = $request->file('document')->store('cac_certificates', 'public');

        $user->cac_certificate = $path;
        $user->cac_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'CAC Document Submitted',
            'Your CAC document has been uploaded and is under review.',
            ['document' => 'cac', 'status' => 'under review']
        );

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

        return back()->with('success', 'CAC document uploaded successfully.');
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

            return back()->with('error', 'BVN already verified.');
        }

        $bvn = $request->bvn;

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get("https://api.paystack.co/bank/resolve_bvn/{$bvn}");

        if ($response->successful()) {
            $data = $response->json()['data'];

            $user->bvn = $bvn;
            $user->bvn_status = 'yes';
            $user->save();

            $this->sendComplianceNotification(
                $user,
                'BVN Verified',
                'Your BVN has been successfully verified.',
                ['document' => 'bvn', 'status' => 'confirmed']
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'BVN verified successfully.',
                    'code' => 'BVN_VERIFIED',
                    'data' => [
                        'bvn' => $bvn,
                        'name' => $data['first_name'] . ' ' . $data['last_name'],
                        'dob' => $data['dob']
                    ]
                ], 200);
            }

            return back()->with('success', 'BVN verified successfully.');
        }

        $errorMessage = $response->json()['message'] ?? 'BVN verification failed';

        $this->sendComplianceNotification(
            $user,
            'BVN Verification Failed',
            $errorMessage,
            ['document' => 'bvn', 'status' => 'failed']
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'code' => 'BVN_VERIFICATION_FAILED',
                'data' => null
            ], 422);
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
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Valid ID already uploaded.',
                    'code' => 'VALID_ID_ALREADY_UPLOADED',
                    'data' => [
                        'valid_id' => $user->valid_id,
                        'valid_id_status' => $user->valid_id_status,
                    ]
                ], 409);
            }

            return back()->with('error', 'Valid ID already uploaded.');
        }

        $path = $request->file('document')->store('valid_ids', 'public');

        $user->valid_id = $path;
        $user->valid_id_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'Valid ID Submitted',
            'Your Valid ID has been uploaded and is under review.',
            ['document' => 'valid_id', 'status' => 'under review']
        );

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Valid ID uploaded and under review.',
                'code' => 'VALID_ID_UPLOADED',
                'data' => [
                    'valid_id' => $path,
                    'valid_id_status' => $user->valid_id_status
                ]
            ], 201);
        }

        return back()->with('success', 'Valid ID uploaded successfully.');
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
                ], 409);
            }

            return back()->with('error', 'TIN document already uploaded.');
        }

        $path = $request->file('document')->store('tin_documents', 'public');

        $user->tin = $path;
        $user->tin_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'TIN Document Submitted',
            'Your TIN document has been uploaded and is under review.',
            ['document' => 'tin', 'status' => 'under review']
        );

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'TIN uploaded and under review.',
                'code' => 'TIN_UPLOADED',
                'data' => [
                    'tin' => $path,
                    'tin_status' => $user->tin_status
                ]
            ], 201);
        }

        return back()->with('success', 'TIN uploaded successfully.');
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
                ], 409);
            }

            return back()->with('error', 'Utility Bill already uploaded.');
        }

        $path = $request->file('document')->store('utility_bills', 'public');

        $user->utility_bill = $path;
        $user->utility_bill_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'Utility Bill Submitted',
            'Your utility bill has been uploaded and is under review.',
            ['document' => 'utility_bill', 'status' => 'under review']
        );

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Utility bill uploaded.',
                'code' => 'UTILITY_BILL_UPLOADED',
                'data' => [
                    'utility_bill' => $path,
                    'utility_bill_status' => $user->utility_bill_status
                ]
            ], 201);
        }

        return back()->with('success', 'Utility bill uploaded successfully.');
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

            return back()->with('error', 'Proof of Identity already uploaded.');
        }

        $path = $request->file('document')->store('proof_of_identity', 'public');

        $user->proof_of_identity = $path;
        $user->proof_of_identity_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'Proof of Identity Submitted',
            'Your Proof of Identity has been uploaded and is under review.',
            ['document' => 'proof_of_identity', 'status' => 'under review']
        );

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

        return back()->with('success', 'Proof of Identity uploaded successfully.');
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

            return back()->with('error', 'Ownership document already uploaded.');
        }

        $path = $request->file('document')->store('ownership_documents', 'public');

        $user->ownership_document = $path;
        $user->ownership_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'Ownership Document Submitted',
            'Your ownership document has been uploaded and is under review.',
            ['document' => 'ownership', 'status' => 'under review']
        );

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

        return back()->with('success', 'Ownership document uploaded successfully.');
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

            return back()->with('error', 'Organisational chart already uploaded.');
        }

        $path = $request->file('document')->store('organisational_charts', 'public');

        $user->organisational_chart = $path;
        $user->organisational_chart_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'Organisational Chart Submitted',
            'Your organisational chart has been uploaded and is under review.',
            ['document' => 'organisational_chart', 'status' => 'under review']
        );

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

        return back()->with('success', 'Organisational chart uploaded successfully.');
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

            return back()->with('error', 'Register of Directors already uploaded.');
        }

        $path = $request->file('document')->store('register_of_directors', 'public');

        $user->register_of_directors = $path;
        $user->register_of_directors_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'Register of Directors Submitted',
            'Your Register of Directors document has been uploaded and is under review.',
            ['document' => 'register_of_directors', 'status' => 'under review']
        );

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

        return back()->with('success', 'Register of Directors uploaded successfully.');
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

            return back()->with('error', 'Formation document already uploaded.');
        }

        $path = $request->file('document')->store('formation_documents', 'public');

        $user->formation_document = $path;
        $user->formation_document_status = 'under review';
        $user->save();

        $this->sendComplianceNotification(
            $user,
            'Formation Document Submitted',
            'Your formation document has been uploaded and is under review.',
            ['document' => 'formation_document', 'status' => 'under review']
        );

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

        return back()->with('success', 'Formation document uploaded successfully.');
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

        $applicantId = $data['externalUserId'] ?? $data['applicantId'] ?? null;
        Log::info('Applicant ID extracted', ['applicantId' => $applicantId]);

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

        if ($reviewStatus === 'GREEN') {
            $this->sendComplianceNotification(
                $user,
                'Identity Verified',
                'Your identity verification was successful.',
                ['document' => 'identity_verification', 'status' => 'confirmed']
            );
        } elseif ($reviewStatus === 'RED') {
            $this->sendComplianceNotification(
                $user,
                'Identity Verification Failed',
                'Your identity verification could not be confirmed. Please review and resubmit.',
                ['document' => 'identity_verification', 'status' => 'rejected']
            );
        }

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
}