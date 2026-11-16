<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\ExchangeRate;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WebhookSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SwapController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $setting = $this->resolveKeyOwner($request);

        if (! $setting) {
            return $this->error('Invalid public key or secret key', 'UNAUTHORIZED', 401);
        }

        $isTestUrl = str_starts_with($request->path(), 'api/test/');
        $keyMode = $this->resolveKeyMode($request, $setting);

        if (($isTestUrl && $keyMode !== 'test') || (! $isTestUrl && $keyMode !== 'live')) {
            return $this->error(
                $isTestUrl ? 'Test API requires test keys' : 'Live API requires live keys',
                'INVALID_KEY_MODE',
                403,
                $isTestUrl ? 'test' : 'live'
            );
        }

        $mode = $isTestUrl ? 'test' : 'live';
        $validator = Validator::make($request->all(), [
            'from_currency' => ['required', 'string', 'size:3'],
            'to_currency' => ['required', 'string', 'size:3'],
            'amount' => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'code' => 'VALIDATION_ERROR',
                'errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $from = strtoupper($request->input('from_currency'));
        $to = strtoupper($request->input('to_currency'));
        $amount = (float) $request->input('amount');

        if ($from === $to) {
            return $this->error('From and To currency cannot be the same.', 'SAME_CURRENCY', 422, $mode);
        }

        $owner = User::find($setting->user_id);
        if (! $owner) {
            return $this->error('Owner account not found', 'OWNER_NOT_FOUND', 422, $mode);
        }

        $result = DB::transaction(function () use ($setting, $owner, $mode, $from, $to, $amount) {
            $balances = Balance::query()
                ->where('user_id', $setting->user_id)
                ->where('mode', $mode)
                ->whereIn('currency', [$from, $to])
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $fromBalance = $balances->firstWhere('currency', $from);
            $toBalance = $balances->firstWhere('currency', $to);

            if (! $fromBalance || ! $toBalance) {
                return $this->error('Invalid wallet selection.', 'INVALID_WALLET', 422, $mode);
            }

            if ($fromBalance->is_locked || $toBalance->is_locked) {
                return $this->error('One or more wallets are locked.', 'WALLET_LOCKED', 403, $mode);
            }

            if ((float) $fromBalance->amount < $amount) {
                return $this->error('Insufficient balance.', 'INSUFFICIENT_BALANCE', 422, $mode);
            }

            $rate = ExchangeRate::query()
                ->whereHas('fromCurrency', fn ($query) => $query->where('code', $from))
                ->whereHas('toCurrency', fn ($query) => $query->where('code', $to))
                ->first();

            if (! $rate || (float) $rate->rate <= 0) {
                return $this->error('Rate not found.', 'RATE_NOT_FOUND', 404, $mode);
            }

            $converted = round($amount * (float) $rate->rate, 2);
            $fromBalance->amount = (float) $fromBalance->amount - $amount;
            $toBalance->amount = (float) $toBalance->amount + $converted;
            $fromBalance->save();
            $toBalance->save();

            $reference = 'ref-' . Str::uuid();
            $ownerName = $owner->business_name ?: $owner->name;
            $transaction = TransactionHistory::create([
                'amount' => $amount,
                'currency' => $from,
                'balance_id' => $fromBalance->id,
                'api_source' => true,
                'status' => 'success',
                'type' => 'Swap',
                'method' => 'Swap ' . $from . ' to ' . $to,
                'reference' => $reference,
                'user_id' => $setting->user_id,
                'mode' => $mode,
                'recipient_country' => strtoupper(substr($to, 0, 2)),
                'sender_id' => $setting->user_id,
                'sender' => $ownerName,
                'recipient_account_number' => $from,
                'recipient_account_name' => $ownerName,
                'to_currency' => $to,
                'recipient_amount' => $converted,
                'exchange_rate' => $rate->rate,
                'single_rate' => $rate->rate,
            ]);

            return [
                'transaction' => $transaction,
                'from_balance' => $fromBalance,
                'to_balance' => $toBalance,
                'rate' => (float) $rate->rate,
                'converted' => $converted,
            ];
        }, 3);

        if ($result instanceof JsonResponse) {
            return $result;
        }

        $transaction = $result['transaction'];
        return response()->json([
            'success' => true,
            'message' => 'Exchange completed successfully!',
            'code' => 'EXCHANGE_SUCCESS',
            'mode' => $mode,
            'data' => [
                'from_currency' => $from,
                'to_currency' => $to,
                'amount' => $amount,
                'converted' => $result['converted'],
                'rate' => $result['rate'],
                'reference' => $transaction->reference,
                'balances' => [
                    [
                        'id' => $result['from_balance']->id,
                        'currency' => $from,
                        'amount' => (float) $result['from_balance']->amount,
                    ],
                    [
                        'id' => $result['to_balance']->id,
                        'currency' => $to,
                        'amount' => (float) $result['to_balance']->amount,
                    ],
                ],
                'transaction' => [
                    'id' => $transaction->id,
                    'reference' => $transaction->reference,
                    'status' => $transaction->status,
                    'method' => $transaction->method,
                    'type' => $transaction->type,
                    'amount' => (float) $transaction->amount,
                    'currency' => $transaction->currency,
                    'to_currency' => $transaction->to_currency,
                    'recipient_amount' => (float) $transaction->recipient_amount,
                    'exchange_rate' => $result['rate'],
                    'created_at' => $transaction->created_at?->toIso8601String(),
                ],
            ],
        ], 200);
    }

    private function error(string $message, string $code, int $status, ?string $mode = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'mode' => $mode,
            'data' => null,
        ], $status);
    }

    private function resolveKeyOwner(Request $request): ?WebhookSetting
    {
        $publicKey = $request->header('X-Public-Key');
        $secretKey = $request->header('X-Secret-Key');

        if (! $publicKey || ! $secretKey) {
            return null;
        }

        return WebhookSetting::query()
            ->where(function ($query) use ($publicKey, $secretKey) {
                $query->where('live_public_key', $publicKey)
                    ->where('live_secret_key', $secretKey);
            })
            ->orWhere(function ($query) use ($publicKey, $secretKey) {
                $query->where('test_public_key', $publicKey)
                    ->where('test_secret_key', $secretKey);
            })
            ->first();
    }

    private function resolveKeyMode(Request $request, WebhookSetting $setting): string
    {
        return $setting->test_public_key === $request->header('X-Public-Key')
            && $setting->test_secret_key === $request->header('X-Secret-Key')
                ? 'test'
                : 'live';
    }
}
