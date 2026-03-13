<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SumsubService
{
    protected $appToken;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->appToken  = env('SUMSUB_APP_TOKEN');
        $this->secretKey = env('SUMSUB_SECRET_KEY');
        $this->baseUrl   = env('SUMSUB_BASE_URL');
    }

    /**
     * Generate Sumsub headers
     */
    private function headers($method, $url, $body = '')
    {
        $ts = time();

        $signature = hash_hmac(
            'sha256',
            $ts . strtoupper($method) . $url . $body,
            $this->secretKey
        );

        return [
            'X-App-Token' => $this->appToken,
            'X-App-Access-Ts' => $ts,
            'X-App-Access-Sig' => $signature,
            'Content-Type' => 'application/json'
        ];
    }




public function uploadDocument(string $applicantId, string $filePath, string $type): array
{
    set_time_limit(300); // allow large files

    $url = "/resources/applicants/$applicantId/info/idDoc";
    $file = storage_path("app/public/" . $filePath);

    if (!file_exists($file)) {
        return ['status' => false, 'message' => 'File not found locally.'];
    }

    $headers = $this->headers('POST', $url);

    try {
        $response = Http::timeout(300)
            ->retry(3, 2000)
            ->withHeaders($headers)
            ->withOptions(['verify' => false]) // dev only
            ->attach(
                'content', 
                file_get_contents($file), 
                basename($file)
            )
            ->attach(
                'metadata',
                json_encode([
                    'idDocType' => $type,
                    'country' => 'NGA'
                ]),
                'metadata.json',
                ['Content-Type' => 'application/json']
            )
            ->post($this->baseUrl . $url);

        return $response->json();
    } catch (\Exception $e) {
        return [
            'status' => false,
            'message' => 'Sumsub upload failed: ' . $e->getMessage()
        ];
    }
}



    /**
     * Create applicant
     */
    public function createApplicant($user)
    {
      $path = "/resources/applicants?levelName=id-and-liveness";
        $body = json_encode([
            "externalUserId" => (string) $user->id,
            "email" => $user->email
        ]);

        $response = Http::withHeaders(
            $this->headers("POST", $path, $body)
        )->post(
            $this->baseUrl . $path,
            json_decode($body, true)
        );

        return $response->json();
    }


public function getLevels()
{
    $path = "/resources/verification/levels";

    $response = Http::withHeaders($this->headers("GET", $path))
                    ->get($this->baseUrl . $path);

    return $response->json();
}

    /**
     * Upload ID Document
     */
// public function uploadDocument($applicantId, $filePath, $type)
// {
//     $url = "/resources/applicants/$applicantId/info/idDoc";

//     $file = storage_path("app/public/" . $filePath);

//     $response = Http::withHeaders($this->headers("POST", $url))
//         // attach the file
//         ->attach(
//             'content',
//             file_get_contents($file),
//             basename($file)
//         )
//         // attach the metadata JSON with proper mime type
//         ->attach(
//             'metadata',
//             json_encode([
//                 "idDocType" => $type,
//                 "country" => "NGA"
//             ]),
//             'metadata.json',
//             ['Content-Type' => 'application/json'] // critical for Sumsub
//         )
//         ->post($this->baseUrl . $url);

//     return $response->json();
// }

    /**
     * Check verification status
     */
    public function checkStatus($applicantId)
    {
        $url = "/resources/applicants/$applicantId/one";

        $response = Http::withHeaders(
            $this->headers("GET", $url)
        )->get(
            $this->baseUrl . $url
        );

        return $response->json();
    }





    public function startKyc(SumsubService $sumsub)
{
    $user = auth()->user();

    if (!$user->sumsub_applicant_id) {

        $result = $sumsub->createApplicant($user);

        if(isset($result['id'])){
            $user->sumsub_applicant_id = $result['id'];
            $user->save();
        } else {
            return response()->json([
                "status"=>false,
                "message"=>"Failed to create applicant",
                "response"=>$result
            ]);
        }
    }

    return response()->json([
        "status"=>true,
        "message"=>"KYC started"
    ]);
}

}