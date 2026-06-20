<?php

namespace App\Http\Middleware;

use App\Models\WebhookSetting;
use Closure;
use Illuminate\Http\Request;

class EnsureIpWhitelisted
{
    public function handle(Request $request, Closure $next)
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (! $publicKey || ! $secretKey) {
            return response()->json([
                'success' => false,
                'message' => 'Missing API keys',
            ], 401);
        }

        $setting = WebhookSetting::query()
            ->where(function ($q) use ($publicKey, $secretKey) {
                $q->where('live_public_key', $publicKey)
                    ->where('live_secret_key', $secretKey);
            })
            ->orWhere(function ($q) use ($publicKey, $secretKey) {
                $q->where('test_public_key', $publicKey)
                    ->where('test_secret_key', $secretKey);
            })
            ->first();

        if (! $setting) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid public key or secret key',
            ], 401);
        }

        $isLive = $setting->live_public_key === $publicKey
            && $setting->live_secret_key === $secretKey;

        $whitelist = $isLive
            ? ($setting->live_ip_whitelist ?? [])
            : ($setting->test_ip_whitelist ?? []);

        if (is_string($whitelist)) {
            $whitelist = json_decode($whitelist, true) ?: [];
        }

        /*
         * This gets the API domain being hit, e.g.
         * flovide.com, api.flovide.com
         */
        $host = $request->getHost();

        /*
         * Resolve that domain to IP address(es).
         */
        $hostIps = gethostbynamel($host) ?: [];

        /*
         * Allow whitelist to contain either:
         * - the domain: flovide.com
         * - the resolved IP: 123.123.123.123
         */
        $allowed = in_array($host, $whitelist, true)
            || count(array_intersect($hostIps, $whitelist)) > 0;

        if (! $allowed) {
            return response()->json([
                'success' => false,
                'message' => 'API domain is not whitelisted',
                'domain' => $host,
                'resolved_ips' => $hostIps,
            ], 403);
        }

        return $next($request);
    }
}