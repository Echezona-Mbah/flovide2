<?php

namespace App\Http\Controllers\Interac;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InteracAccountController extends Controller
{
    public function eligibility(Request $request, string $accountNum)
    {
        $this->validateHeaders($request);

        $validator = Validator::make($request->all(), [
            'customer_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
            'alias_ref_id' => ['nullable', 'regex:/^[a-zA-Z0-9\-.]{8,64}$/'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:CAD,USD'],
            'transaction_type' => ['required', 'in:DEBIT,CREDIT'],
            'transaction_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
            'network_payment_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{8,35}$/'],
            'payment_type' => ['required', 'in:ALIAS_REGULAR,ALIAS_AUTODEPOSIT,ALIAS_REALTIME,ACCOUNT_DEPOSIT_REGULAR,ACCOUNT_DEPOSIT_REALTIME,REQUEST_FULFILLMENT'],
        ]);

        if ($validator->fails()) {
            return $this->error('VALIDATION_ERROR', 'Validation failed', 400, $validator->errors());
        }

        // TODO: plug your real eligibility logic here
        return $this->success([
            'result_code' => 'SUCCESS',
            'account_name' => 'Sample Account Name',
            'additional_information' => null,
        ]);
    }

    public function transaction(Request $request, string $accountNum)
    {
        $this->validateHeaders($request);

        $validator = Validator::make($request->all(), [
            'customer_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:CAD,USD'],
            'transaction_type' => ['required', 'in:DEBIT,CREDIT'],
            'transaction_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
            'network_payment_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{8,35}$/'],
            'payment_type' => ['required', 'in:ALIAS_REGULAR,ALIAS_AUTODEPOSIT,ALIAS_REALTIME,ACCOUNT_DEPOSIT_REGULAR,ACCOUNT_DEPOSIT_REALTIME,REQUEST_FULFILLMENT'],
        ]);

        if ($validator->fails()) {
            return $this->error('VALIDATION_ERROR', 'Validation failed', 400, $validator->errors());
        }

        // TODO: do actual ledger posting here
        return response()->json([
            'result_code' => 'SUCCESS',
            'ref_id' => (string) Str::uuid(),
            'additional_information' => null,
        ], 201, $this->responseHeaders($request));
    }

    public function reversal(Request $request, string $accountNum, string $transactionId)
    {
        $this->validateHeaders($request);

        $validator = Validator::make($request->all(), [
            'customer_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:CAD,USD'],
            'transaction_type' => ['required', 'in:DEBIT,CREDIT'],
            'payment_ref_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
        ]);

        if ($validator->fails()) {
            return $this->error('VALIDATION_ERROR', 'Validation failed', 400, $validator->errors());
        }

        // TODO: do actual reversal posting here
        return $this->success([
            'result_code' => 'SUCCESS',
            'ref_id' => (string) Str::uuid(),
            'additional_information' => null,
        ]);
    }

    private function validateHeaders(Request $request): void
    {
        $h = Validator::make($request->headers->all(), [
            'x-pg-interaction-id' => ['required'],
            'x-pg-interaction-timestamp' => ['required'],
            'x-pg-api-token' => ['required'],
        ]);

        if ($h->fails()) {
            abort($this->error('MISSING_HEADER', 'Missing required header', 400, $h->errors()));
        }
    }

    private function responseHeaders(Request $request): array
    {
        return [
            'x-pg-interaction-id' => $request->header('x-pg-interaction-id') ?? (string) Str::uuid(),
            'x-pg-correspondent-id' => (string) Str::uuid(),
        ];
    }

    private function success(array $data)
    {
        return response()->json($data, 200, [
            'x-pg-interaction-id' => request()->header('x-pg-interaction-id') ?? (string) Str::uuid(),
            'x-pg-correspondent-id' => (string) Str::uuid(),
        ]);
    }

    private function error(string $code, string $message, int $status, $details = null)
    {
        return response()->json([
            'error' => [[
                'code' => $code,
                'additional_information' => $details ?: $message,
            ]],
        ], $status, [
            'x-pg-interaction-id' => request()->header('x-pg-interaction-id') ?? (string) Str::uuid(),
            'x-pg-correspondent-id' => (string) Str::uuid(),
        ]);
    }
}
