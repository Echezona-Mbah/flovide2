<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IbanqWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = env('IBANQ_NOTIFICATION_SECRET');

        $timestamp = $request->header('x-ibanq-timestamp');
        $signature = $request->header('x-ibanq-signature');
        $userAgent = $request->header('User-Agent');
        $body = $request->getContent();

        $verified = false;

        if ($timestamp && $signature && $userAgent) {

            preg_match('/ibanq\/(.+)/', $userAgent, $matches);
            $version = $matches[1] ?? '';

            $checkString = $timestamp . '|' . $body . '|' . $version;
            $calculated = hash_hmac('sha256', $checkString, $secret);

            $verified = hash_equals($calculated, $signature);
        }

        $payload = json_decode($body, true);

        // ❌ Invalid signature
        if (!$verified) {
            return response()->json([
                'success' => false,
                'verified' => false,
                'message' => 'Invalid IFX signature',
                'headers' => [
                    'timestamp' => $timestamp,
                    'userAgent' => $userAgent,
                ],
                'payload' => $payload,
            ], 400);
        }

        // ✅ Valid notification
        return response()->json([
            'success' => true,
            'verified' => true,
            'message' => 'Notification received',
            'headers' => [
                'timestamp' => $timestamp,
                'userAgent' => $userAgent,
            ],
            'payload' => $payload,
        ], 200);
    }
}
