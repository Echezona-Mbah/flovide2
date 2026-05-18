<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;


class InteracIamTokenService
{
    public function getAccessToken(): string
    {
        $cached = Cache::get('interac:access_token');
        if ($cached) {
            return $cached;
        }

        $tokenUrl = $this->tokenUrl();
        $clientId = (string) config('interac.client_id');
        $privateKeyPath = (string) config('interac.private_key_path');
        $privateKeyId = (string) config('interac.private_key_id');
        $assertionAud = $this->assertionAud();
        $apiAudience = $this->apiAudience();

        if (!$tokenUrl || !$clientId || !$privateKeyPath || !$assertionAud || !$apiAudience) {
            throw new \RuntimeException('Missing Interac IAM config (token_url/client_id/private_key_path/assertion_aud/api_audience).');
        }

        if (!file_exists($privateKeyPath)) {
            throw new \RuntimeException("Private key file not found: {$privateKeyPath}");
        }

        $clientAssertion = $this->buildClientAssertion(
            $clientId,
            $assertionAud,
            $privateKeyPath,
            $privateKeyId
        );

        $response = Http::asForm()
            ->timeout((int) config('interac.timeout', 30))
            ->post($tokenUrl, [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => $clientAssertion,
                'audience' => $apiAudience,
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'Interac token request failed: ' . $response->status() . ' ' . $response->body()
            );
        }

        $json = $response->json();

        $accessToken = $json['access_token'] ?? null;
        $expiresIn = (int) ($json['expires_in'] ?? 900);

        if (!$accessToken) {
            throw new \RuntimeException('Token response missing access_token.');
        }

        $buffer = (int) config('interac.token_ttl_buffer', 60);
        $ttl = max(60, $expiresIn - $buffer);

        Cache::put('interac:access_token', $accessToken, now()->addSeconds($ttl));

        return $accessToken;
    }

    private function buildClientAssertion(
        string $clientId,
        string $assertionAud,
        string $privateKeyPath,
        ?string $kid
    ): string {
        $now = time();

        $header = [
            'alg' => 'PS256',
            'typ' => 'JWT',
        ];

        if (!empty($kid)) {
            $header['kid'] = $kid;
        }

        $payload = [
            'iss' => $clientId,
            'sub' => $clientId,
            'aud' => $assertionAud,
            'iat' => $now,
            'exp' => $now + 300,
            'jti' => (string) Str::uuid(),
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $signingInput = $encodedHeader . '.' . $encodedPayload;

        $privateKeyPem = file_get_contents($privateKeyPath);
        if (!$privateKeyPem) {
            throw new \RuntimeException('Unable to read private key from path.');
        }

        $privateKey = PublicKeyLoader::loadPrivateKey($privateKeyPem)
            ->withPadding(RSA::SIGNATURE_PSS)
            ->withHash('sha256')
            ->withMGFHash('sha256')
            ->withSaltLength(32);

        $signature = $privateKey->sign($signingInput);
        if (!$signature) {
            throw new \RuntimeException('Failed to sign client assertion with PS256.');
        }

        return $signingInput . '.' . $this->base64UrlEncode($signature);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    protected function tokenUrl(): string
    {
        return config('interac.env') === 'production'
            ? (string) config('interac.token_url_prod')
            : (string) config('interac.token_url_staging');
    }

    protected function assertionAud(): string
    {
        return config('interac.env') === 'production'
            ? (string) config('interac.assertion_aud_prod')
            : (string) config('interac.assertion_aud_staging');
    }

    protected function apiAudience(): string
    {
        return config('interac.env') === 'production'
            ? (string) config('interac.api_audience_prod')
            : (string) config('interac.api_audience_staging');
    }

    public function clearCachedToken(): void
    {
        Cache::forget('interac:access_token');
    }
}
