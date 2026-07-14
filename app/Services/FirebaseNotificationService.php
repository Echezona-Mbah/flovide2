<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class FirebaseNotificationService
{
    private function accessToken(): ?string
    {
        $projectId = config('services.firebase.project_id');
        $clientEmail = config('services.firebase.client_email');
        $privateKey = config('services.firebase.private_key');

        if (!$projectId || !$clientEmail || !$privateKey) {
            return null;
        }

        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $creds = new ServiceAccountCredentials($scopes, [
            'type' => 'service_account',
            'project_id' => $projectId,
            'client_email' => $clientEmail,
            'private_key' => $privateKey,
            'token_uri' => 'https://oauth2.googleapis.com/token',
        ]);

        $tokenArr = $creds->fetchAuthToken();
        return $tokenArr['access_token'] ?? null;
    }

    public function sendNotification(?string $deviceToken, string $title, string $body, ?string $imageUrl = null, array $data = []): bool
    {
        return $this->sendToToken($deviceToken, $title, $body, $data);
    }

    public function sendToToken(?string $deviceToken, string $title, string $body, array $data = []): bool
    {
        if (!$deviceToken) {
            return false;
        }

        $accessToken = $this->accessToken();
        $projectId = config('services.firebase.project_id');

        if (!$accessToken || !$projectId) {
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $this->stringifyData($data),
                'android' => ['priority' => 'high'],
                'apns' => [
                    'headers' => ['apns-priority' => '10'],
                    'payload' => ['aps' => ['sound' => 'default']],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)->post($url, $payload);

        \Log::info('FCM PUSH RESPONSE', [
            'device_token' => substr($deviceToken, 0, 20) . '...',
            'status' => $response->status(),
            'body' => $response->json(),
        ]);


        return $response->successful();
    }

    private function stringifyData(array $data): array
    {
        $out = [];
        foreach ($data as $key => $value) {
            $out[(string) $key] = is_scalar($value) ? (string) $value : json_encode($value);
        }
        return $out;
    }

    public function sendSilentToToken(?string $deviceToken, array $data = []): bool
    {
        if (!$deviceToken) {
            return false;
        }

        $accessToken = $this->accessToken();
        $projectId = config('services.firebase.project_id');

        if (!$accessToken || !$projectId) {
            Log::warning('FCM silent push missing credentials', [
                'has_access_token' => (bool) $accessToken,
                'has_project_id' => (bool) $projectId,
            ]);

            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $deviceToken,

                // Data-only payload. No title, no body.
                'data' => $this->stringifyData($data),

                'android' => [
                    'priority' => 'high',
                ],

                'apns' => [
                    'headers' => [
                        'apns-priority' => '5',
                        'apns-push-type' => 'background',
                    ],
                    'payload' => [
                        'aps' => [
                            'content-available' => 1,
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)->post($url, $payload);

        Log::info('FCM SILENT PUSH RESPONSE', [
            'device_token' => substr($deviceToken, 0, 20) . '...',
            'status' => $response->status(),
            'body' => $response->json(),
            'payload' => $payload,
        ]);

        return $response->successful();
    }

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



}
