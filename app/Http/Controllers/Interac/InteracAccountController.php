<?php

namespace App\Http\Controllers\Interac;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InteracAccountController extends Controller
{
    public function eligibility(Request $request, string $account_num)
    {
        $this->validateHeaders($request);
        $this->validateAccountNum($account_num);

        $validator = Validator::make($request->all(), [
            'customer_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
            'alias_ref_id' => ['nullable', 'regex:/^[a-zA-Z0-9\-.]{8,64}$/'],
            'end_to_end_id' => ['nullable', 'regex:/^[a-zA-Z0-9\-.]{6,36}$/'],

            'debtor_name' => ['nullable', 'string', 'min:3', 'max:100'],
            'debtor_email' => ['nullable', 'email', 'min:3', 'max:64'],
            'debtor_fi_name' => ['nullable', 'string', 'min:3', 'max:100'],
            'debtor_fi_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{1,35}$/'],

            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:CAD,USD'],
            'transaction_type' => ['required', 'in:DEBIT,CREDIT'],

            'request_payment_ref_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
            'transaction_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
            'network_payment_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{8,35}$/'],

            // Keep both to avoid provider/sample mismatch during integration
            'payment_type' => ['required', 'in:ALIAS_REGULAR,ALIAS_AUTODEPOSIT,ALIAS_REALTIME,ACCOUNT_DEPOSIT_REGULAR,ACCOUNT_DEPOSIT_REALTIME,REQUEST_FULFILLMENT,REGULAR_PAYMENT'],

            'memo' => ['nullable', 'string', 'max:420'],
            'remittance' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 400, $validator->errors());
        }

        $result = [
            'success' => true,
            'message' => 'Eligibility checked successfully',
            'code' => 'ELIGIBILITY_SUCCESS',
            'data' => [
                'result_code' => 'SUCCESS',
                'account_name' => 'Sample Account Name',
                'additional_information' => null,
            ],
            'headers' => $this->responseHeaders($request),
        ];

        return $this->respond($result, 200);
    }

    public function transaction(Request $request, string $account_num)
    {
        $this->validateHeaders($request);
        $this->validateAccountNum($account_num);

        $validator = Validator::make($request->all(), [
            'customer_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
            'end_to_end_id' => ['nullable', 'regex:/^[a-zA-Z0-9\-.]{6,36}$/'],

            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:CAD,USD'],

            'debtor_fi_name' => ['nullable', 'string', 'min:3', 'max:100'],
            'debtor_fi_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{1,35}$/'],

            'transaction_type' => ['required', 'in:DEBIT,CREDIT'],
            'request_payment_ref_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
            'transaction_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
            'network_payment_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{8,35}$/'],

            // Keep both to avoid provider/sample mismatch during integration
            'payment_type' => ['required', 'in:ALIAS_REGULAR,ALIAS_AUTODEPOSIT,ALIAS_REALTIME,ACCOUNT_DEPOSIT_REGULAR,ACCOUNT_DEPOSIT_REALTIME,REQUEST_FULFILLMENT,REGULAR_PAYMENT'],

            'payment_network_date' => ['required', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 400, $validator->errors());
        }

        $result = [
            'success' => true,
            'message' => 'Transaction posted successfully',
            'code' => 'TRANSACTION_SUCCESS',
            'data' => [
                'result_code' => 'SUCCESS',
                'ref_id' => (string) Str::uuid(),
                'additional_information' => null,
            ],
            'headers' => $this->responseHeaders($request),
        ];

        return $this->respond($result, 201);
    }

    public function reversal(Request $request, string $account_num, string $transaction_id)
    {
        $this->validateHeaders($request);
        $this->validateAccountNum($account_num);
        $this->validateTransactionId($transaction_id);

        $validator = Validator::make($request->all(), [
            'customer_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
            'end_to_end_id' => ['nullable', 'regex:/^[a-zA-Z0-9\-.]{6,36}$/'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'in:CAD,USD'],
            'transaction_type' => ['required', 'in:DEBIT,CREDIT'],
            'payment_ref_id' => ['nullable', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
            'status' => ['required', 'in:ACCEPTED,QUEUED,AVAILABLE,CANCELLED,FAILED,EXPIRED,DECLINED,DEPOSIT_INITIATED,SECURITY_ANSWER_FAILURE,DEPOSIT_FAILED,DEPOSIT_PENDING,DEPOSIT_COMPLETE,FAILED_PENDING'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 400, $validator->errors());
        }

        $result = [
            'success' => true,
            'message' => 'Reversal successful',
            'code' => 'REVERSAL_SUCCESS',
            'data' => [
                'result_code' => 'SUCCESS',
                'ref_id' => (string) Str::uuid(),
                'additional_information' => null,
            ],
            'headers' => $this->responseHeaders($request),
        ];

        return $this->respond($result, 200);
    }

   private function validateHeaders(Request $request): void
{
    if (app()->environment('local')) {
        return; // skip strict header check locally
    }

    $missing = [];
    if (! $request->header('x-pg-interaction-id')) $missing['x-pg-interaction-id'] = ['The x-pg-interaction-id field is required.'];
    if (! $request->header('x-pg-interaction-timestamp')) $missing['x-pg-interaction-timestamp'] = ['The x-pg-interaction-timestamp field is required.'];
    if (! $request->header('x-pg-api-token')) $missing['x-pg-api-token'] = ['The x-pg-api-token field is required.'];

    if (!empty($missing)) {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            $this->errorResponse('Missing required header', 'MISSING_HEADER', 400, $missing)
        );
    }
}

    private function validateAccountNum(string $account_num): void
    {
        if (!preg_match('/^[0-9]{3}-[0-9]{5}-[0-9]{2,24}$/', $account_num)) {
            throw new HttpResponseException(
                $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 400, [
                    'account_num' => ['Invalid account number format.']
                ])
            );
        }
    }

    private function validateTransactionId(string $transaction_id): void
    {
        if (!preg_match('/^[a-zA-Z0-9]{16,36}$/', $transaction_id)) {
            throw new HttpResponseException(
                $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 400, [
                    'transaction_id' => ['Invalid transaction_id format.']
                ])
            );
        }
    }

    private function responseHeaders(Request $request): array
    {
        return [
            'x-pg-interaction-id' => $request->header('x-pg-interaction-id') ?? (string) Str::uuid(),
            'x-pg-correspondent-id' => (string) Str::uuid(),
        ];
    }

    protected function respond(array $result, int $successStatus)
    {
        if (! $result['success']) {
            return $this->errorResponse(
                $result['message'] ?? 'Request failed',
                $result['code'] ?? 'SERVER_ERROR',
                $result['status'] ?? 500,
                $result['errors'] ?? null,
                $result['data'] ?? null
            );
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'code' => $result['code'],
            'data' => $result['data'],
            'meta' => [
                'x_pg_interaction_id' => $result['headers']['x-pg-interaction-id'] ?? null,
                'x_pg_correspondent_id' => $result['headers']['x-pg-correspondent-id'] ?? null,
            ],
        ], $successStatus);
    }

    protected function errorResponse(string $message, string $code, int $status, $errors = null, $data = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
            'data' => $data,
        ], $status);
    }
}