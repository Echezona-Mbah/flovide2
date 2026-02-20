<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PivotService
{
    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        // Directly read from .env
        $this->baseUrl = env('PIVOT_BASE_URL');
        $this->username = env('PIVOT_USERNAME');
        $this->password = env('PIVOT_PASSWORD');
    }

    // ===============================
    // AUTH API
    // ===============================
    public function authenticate()
    {
        $url = $this->baseUrl . '/api/v1/auth/token';

        // Debug: log URL
        logger('Pivot Auth URL: ' . $url);

        $response = Http::post($url, [
            'username' => $this->username,
            'password' => $this->password,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body(),
        ];
    }


        public function getToken()
{
    return Cache::remember('pivot_token', 300, function () {

        $auth = $this->authenticate();

        if (!isset($auth['tokenResponse']['accessToken'])) {
            throw new \Exception('Pivot auth failed');
        }

        return $auth['tokenResponse']['accessToken'];
    });
}
    // ===============================
    // POST TRANSACTION
    // ===============================
    public function postTransaction($token, $data)
    {
        $url = $this->baseUrl . '/api/v1/post/payment';

        $response = Http::withToken($token)
            ->post($url, $data); // sends JSON automatically

        return $response->json();
    }

//     public function postTransaction($data)
// {
//     $token = $this->getToken();

//     $url = $this->baseUrl . '/api/v1/post/payment';

//     return Http::withToken($token)
//         ->post($url, $data)
//         ->json();
// }


    public function queryPaymentStatus($token, $data)
    {
        $url = $this->baseUrl . '/api/v1/payment/query/status';

        $response = Http::withToken($token)
            ->post($url, $data);

        return $response->json();
    }



    
    public function accountValidation($token, $data)
    {
        $url = $this->baseUrl . '/api/v1/account/validation';

        // Send POST request with Bearer token
        $response = Http::withToken($token)
            ->post($url, $data);

        return $response->json();
    }

        // Card Payment

        public function postCardPayment($token, $data)
    {
        $url = $this->baseUrl . '/api/v1/post/payment';

        // Send POST request with Bearer token
        $response = Http::withToken($token)
            ->post($url, $data);

        return $response->json();
    }


}
