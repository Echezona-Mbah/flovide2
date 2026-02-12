<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IbanqAuthService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('services.ibanq.base_url');
        $this->clientId = env('IBANQ_CLIENT_ID');
        $this->clientSecret = env('IBANQ_CLIENT_SECRET');
        $this->username = env('IBANQ_API_USERNAME');
        $this->password = env('IBANQ_API_PASSWORD');
    }

    /**
     * Get access token from IBANQ using API user credentials
     */
    public function getAccessToken()
    {
        $response = Http::asForm()->post($this->baseUrl . '/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username'      => $this->username,
            'password'      => $this->password,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to get IBANQ access token: ' . $response->body());
        }

        return $response->json()['access_token'];
    }

    /**
     * Get authenticated HTTP client
     */
    public function authenticatedClient()
    {
        $token = $this->getAccessToken();

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ])->baseUrl($this->baseUrl);
    }
}
