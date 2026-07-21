<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\TeamMembers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    private function resolveOwnerAndMember(Request $request, ?User $actor): array
    {
        if (! $actor) {
            return [null, null, null, null]; // ownerId, memberId, role, ownerUser
        }

        $team = TeamMembers::where('user_id', $actor->id)->first();

        $ownerId  = $team ? $team->owner_id : $actor->id;
        $memberId = $team ? $actor->id : null;
        $role     = $team ? $team->role : 'Owner';
        $owner    = User::find($ownerId);

        return [$ownerId, $memberId, $role, $owner];
    }

    public function index(Request $request)
    {
        $actor = auth()->user();
        [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);

        if (! $owner) {
            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Owner account not found',
                    'code' => 'OWNER_NOT_FOUND',
                    'data' => null
                ], 422)
                : back()->with('error', 'Owner account not found');
        }

        $webhookSetting = $owner->webhookSetting()->firstOrCreate(
            ['user_id' => $owner->id],
            [
                'live_secret_key' => 'sk_live_' . Str::lower(Str::random(32)),
                'live_public_key' => 'pk_live_' . Str::lower(Str::random(32)),
                'live_ip_whitelist' => [],
                'live_callback_url' => null,
                'live_webhook_url' => null,
                'test_secret_key' => 'sk_test_' . Str::lower(Str::random(32)),
                'test_public_key' => 'pk_test_' . Str::lower(Str::random(32)),
                'test_ip_whitelist' => [],
                'test_callback_url' => null,
                'test_webhook_url' => null,
            ]
        );

        $payload = [
            'mode' => session('mode', 'live'),
            'settings' => [
                'live' => [
                    'secret_key' => $webhookSetting->live_secret_key,
                    'public_key' => $webhookSetting->live_public_key,
                    'ip_whitelist' => is_array($webhookSetting->live_ip_whitelist) ? implode(', ', $webhookSetting->live_ip_whitelist) : '',
                    'callback_url' => $webhookSetting->live_callback_url,
                    'webhook_url' => $webhookSetting->live_webhook_url,
                ],
                'test' => [
                    'secret_key' => $webhookSetting->test_secret_key,
                    'public_key' => $webhookSetting->test_public_key,
                    'ip_whitelist' => is_array($webhookSetting->test_ip_whitelist) ? implode(', ', $webhookSetting->test_ip_whitelist) : '',
                    'callback_url' => $webhookSetting->test_callback_url,
                    'webhook_url' => $webhookSetting->test_webhook_url,
                ],
            ],
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Webhook settings fetched successfully',
                'code' => 'WEBHOOK_SETTINGS_FETCHED',
                'data' => $payload
            ], 200);
        }

        return view('business.webhook', $payload);
    }

    public function update(Request $request)
    {
        $actor = auth()->user();
        [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);

        if (! $owner) {
            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Owner account not found',
                    'code' => 'OWNER_NOT_FOUND',
                    'data' => null
                ], 422)
                : back()->with('error', 'Owner account not found');
        }

        $validated = $request->validate([
            'mode' => ['required', 'in:live,test'],
            'live_ip_whitelist' => ['nullable', 'string'],
            'live_callback_url' => ['nullable', 'url'],
            'live_webhook_url' => ['nullable', 'url'],
            'test_ip_whitelist' => ['nullable', 'string'],
            'test_callback_url' => ['nullable', 'url'],
            'test_webhook_url' => ['nullable', 'url'],
        ]);

        $webhookSetting = $owner->webhookSetting()->firstOrCreate(
            ['user_id' => $owner->id],
            [
                'live_secret_key' => 'sk_live_' . Str::lower(Str::random(32)),
                'live_public_key' => 'pk_live_' . Str::lower(Str::random(32)),
                'test_secret_key' => 'sk_test_' . Str::lower(Str::random(32)),
                'test_public_key' => 'pk_test_' . Str::lower(Str::random(32)),
            ]
        );

        $liveIps = !empty($validated['live_ip_whitelist'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['live_ip_whitelist']))))
            : [];

        $testIps = !empty($validated['test_ip_whitelist'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['test_ip_whitelist']))))
            : [];

        $webhookSetting->live_ip_whitelist = $liveIps;
        $webhookSetting->live_callback_url = $validated['live_callback_url'] ?? null;
        $webhookSetting->live_webhook_url = $validated['live_webhook_url'] ?? null;

        $webhookSetting->test_ip_whitelist = $testIps;
        $webhookSetting->test_callback_url = $validated['test_callback_url'] ?? null;
        $webhookSetting->test_webhook_url = $validated['test_webhook_url'] ?? null;

        if (!$webhookSetting->live_secret_key) {
            $webhookSetting->live_secret_key = 'sk_live_' . Str::lower(Str::random(32));
        }
        if (!$webhookSetting->live_public_key) {
            $webhookSetting->live_public_key = 'pk_live_' . Str::lower(Str::random(32));
        }
        if (!$webhookSetting->test_secret_key) {
            $webhookSetting->test_secret_key = 'sk_test_' . Str::lower(Str::random(32));
        }
        if (!$webhookSetting->test_public_key) {
            $webhookSetting->test_public_key = 'pk_test_' . Str::lower(Str::random(32));
        }

        $webhookSetting->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => ucfirst($validated['mode']) . ' webhook settings saved successfully.',
                'code' => 'WEBHOOK_SETTINGS_UPDATED',
                'data' => ['mode' => $validated['mode']]
            ], 200);
        }

        return back()->with([
            'success' => ucfirst($validated['mode']) . ' webhook settings saved successfully.',
            'mode' => $validated['mode'],
        ]);
    }

    public function regenerateSecret(Request $request)
    {
        $actor = auth()->user();
        [$ownerId, $memberId, $role, $owner] = $this->resolveOwnerAndMember($request, $actor);

        if (! $owner) {
            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Owner account not found',
                    'code' => 'OWNER_NOT_FOUND',
                    'data' => null
                ], 422)
                : back()->with('error', 'Owner account not found');
        }

        $validated = $request->validate([
            'mode' => ['required', 'in:live,test'],
        ]);

        $webhookSetting = $owner->webhookSetting()->firstOrCreate(
            ['user_id' => $owner->id],
            [
                'live_secret_key' => 'sk_live_' . Str::lower(Str::random(32)),
                'live_public_key' => 'pk_live_' . Str::lower(Str::random(32)),
                'test_secret_key' => 'sk_test_' . Str::lower(Str::random(32)),
                'test_public_key' => 'pk_test_' . Str::lower(Str::random(32)),
            ]
        );

        if ($validated['mode'] === 'live') {
            $webhookSetting->live_secret_key = 'sk_live_' . Str::lower(Str::random(32));
            if (!$webhookSetting->live_public_key) {
                $webhookSetting->live_public_key = 'pk_live_' . Str::lower(Str::random(32));
            }
        } else {
            $webhookSetting->test_secret_key = 'sk_test_' . Str::lower(Str::random(32));
            if (!$webhookSetting->test_public_key) {
                $webhookSetting->test_public_key = 'pk_test_' . Str::lower(Str::random(32));
            }
        }

        $webhookSetting->save();

        $payload = [
            'mode' => $validated['mode'],
            'secret_key' => $validated['mode'] === 'live' ? $webhookSetting->live_secret_key : $webhookSetting->test_secret_key,
            'public_key' => $validated['mode'] === 'live' ? $webhookSetting->live_public_key : $webhookSetting->test_public_key,
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Webhook secret regenerated successfully.',
                'code' => 'WEBHOOK_SECRET_REGENERATED',
                'data' => $payload
            ], 200);
        }

        return back()->with([
            'success' => 'Webhook secret regenerated successfully.',
            'mode' => $validated['mode'],
        ]);
    }
}