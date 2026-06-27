<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Personal\User; // your personal user model
use App\Models\Personal;

class ComplianceController extends Controller
{

    // Generate Sumsub SDK token
// public function getSumsubToken($user)
// {
//     // make sure $user is a Personal model
//     if (!$user || !($user instanceof \App\Models\Personal)) {
//         throw new \Exception("Invalid user passed to getSumsubToken");
//     }

//     $ts = time();
//     $uri = '/resources/accessTokens/sdk';

//     $body = json_encode([
//         'userId' => (string) $user->id,
//         'levelName' => 'id-and-liveness',
//         'ttlInSecs' => 600
//     ]);

//     $signature = hash_hmac(
//         'sha256',
//         $ts.'POST'.$uri.$body,
//         env('SUMSUB_SECRET_KEY')
//     );

//     $client = new Client();

//     $response = $client->post(
//         'https://api.sumsub.com'.$uri,
//         [
//             'headers' => [
//                 'X-App-Token' => env('SUMSUB_APP_TOKEN'),
//                 'X-App-Access-Ts' => $ts,
//                 'X-App-Access-Sig' => $signature,
//                 'Content-Type' => 'application/json'
//             ],
//             'body' => $body
//         ]
//     );

//     return json_decode($response->getBody(), true); // return array, not Response
// }


    public function store(Request $request)
    {
        $formType = $request->input('document_type');

        switch ($formType) {
            case 'nin':
                return $this->handleNin($request);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid document type.',
                ], 422);
        }
    }

    public function handleNin(Request $request)
    {
        $request->validate([
            'nin' => 'required|digits:11'
        ]);

        $user = Auth::guard('personal-api')->user();

        if ($user->nin && $user->nin_status === 'under review') {
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

        $ninExists = Personal::where('nin', $request->nin)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($ninExists) {
            return response()->json([
                'success' => false,
                'message' => 'This NIN is already linked to another personal account.',
                'code' => 'NIN_DUPLICATE',
            ], 409);
        }

        $user->nin = $request->nin;
        $user->nin_status = 'under review';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'NIN submitted successfully.',
            'code' => 'NIN_SUBMITTED',
            'data' => [
                'nin'        => $user->nin,
                'nin_status' => $user->nin_status,
            ]
        ], 201);
    }



public function getSumsubToken($user = null)
{
    if (!$user) {
        $user = Auth::guard('personal-api')->user();
    }

    if (!$user) {
        throw new \Exception("Invalid user passed to getSumsubToken");
    }

    $ts = time();
    $uri = '/resources/accessTokens/sdk';

    $body = json_encode([
        'userId' => $user->id,
        'levelName' => 'id-and-liveness',
        'ttlInSecs' => 600
    ]);

    $signature = hash_hmac(
        'sha256',
        $ts.'POST'.$uri.$body,
        env('SUMSUB_SECRET_KEY')
    );

    $client = new \GuzzleHttp\Client();
    $response = $client->post('https://api.sumsub.com'.$uri, [
        'headers' => [
            'X-App-Token' => env('SUMSUB_APP_TOKEN'),
            'X-App-Access-Ts' => $ts,
            'X-App-Access-Sig' => $signature,
            'Content-Type' => 'application/json'
        ],
        'body' => $body
    ]);

    // ✅ Return decoded array instead of response
    return json_decode($response->getBody(), true);
}

    // Webhook from Sumsub
    public function handle(Request $request)
    {
        Log::info('Sumsub Personal Webhook', $request->all());

        $externalUserId = $request->input('externalUserId');
        $reviewStatus = $request->input('reviewResult.reviewAnswer');

        if (!$externalUserId) {
            return response()->json(['status'=>'no user']);
        }

        // IMPORTANT: find user without auth
        $user = \App\Models\Personal::find($externalUserId);

        if (!$user) {
            return response()->json(['status'=>'user not found']);
        }

        if ($reviewStatus === 'GREEN') {

            $user->identity_verification_status = 'confirmed';
            $user->selfie_verification_status = 'confirmed';

        } elseif ($reviewStatus === 'RED') {

            $user->identity_verification_status = 'rejected';
            $user->selfie_verification_status = 'rejected';

        }

        $user->save();

        return response()->json(['status'=>'ok']);
    }


public function status(Request $request)
{
    $user = auth('personal-api')->user();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated'
        ], 401);
    }

    // Pass the Personal model, NOT the id
    $tokenResponse = $this->getSumsubToken($user); 

    return response()->json([
        'status' => true,
        'message' => 'Compliance status fetched successfully',
        'data' => $user->complianceStatus($tokenResponse)
    ]);
}


}