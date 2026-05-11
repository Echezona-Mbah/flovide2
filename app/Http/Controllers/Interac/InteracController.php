<?php

namespace App\Http\Controllers\Interac;

use App\Http\Controllers\Controller;
use App\Services\InteracService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InteracController extends Controller
{
    public function __construct(protected InteracService $interacService) {}

    public function createCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['nullable', 'string', 'min:6', 'max:12', 'regex:/^[a-zA-Z0-9]+$/'],
            'name' => ['required', 'array'],
            'name.display_name' => ['required', 'string', 'min:1', 'max:80'],
            'name.individual_name.first_name' => ['nullable', 'string', 'min:1', 'max:100'],
            'name.individual_name.last_name' => ['nullable', 'string', 'min:1', 'max:100'],
            'name.business_name.legal_name' => ['nullable', 'string', 'min:3', 'max:100'],
            'type' => ['required', 'in:INDIVIDUAL,SMALL_BUSINESS,CORPORATION'],
            'language' => ['required', 'in:EN,FR'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'email' => ['required', 'array', 'min:1', 'max:2'],
            'email.*.type' => ['required', 'in:PRIMARY,SECONDARY'],
            'email.*.address' => ['required', 'email', 'max:64'],
            'email.*.enable_notification' => ['nullable', 'boolean'],
            'phone' => ['nullable', 'array', 'max:4'],
            'phone.*.type' => ['required_with:phone', 'in:HOME,WORK,MOBILE,WORK_MOBILE'],
            'phone.*.number' => ['required_with:phone', 'string', 'size:10'],
            'phone.*.enable_notification' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
        }

        return $this->respond($this->interacService->createCustomer($validator->validated()), 201);
    }

    public function getCustomer(string $customerId)
    {
        $this->validateCustomerId($customerId);
        return $this->respond($this->interacService->getCustomer($customerId), 200);
    }

    public function updateCustomer(Request $request, string $customerId)
    {
        $this->validateCustomerId($customerId);
        return $this->respond($this->interacService->updateCustomer($customerId, $request->all()), 204);
    }

    public function enableCustomer(string $customerId)
    {
        $this->validateCustomerId($customerId);
        return $this->respond($this->interacService->enableCustomer($customerId), 204);
    }

    public function disableCustomer(string $customerId)
    {
        $this->validateCustomerId($customerId);
        return $this->respond($this->interacService->disableCustomer($customerId), 204);
    }

    public function createAlias(Request $request, string $customerId)
    {
        $this->validateCustomerId($customerId);

        $validator = Validator::make($request->all(), [
            'alias' => ['required', 'array'],
            'alias.email_address' => ['nullable', 'email', 'max:64'],
            'alias.mobile_number' => ['nullable', 'string', 'size:10'],
            'account_number' => ['required', 'regex:/^[0-9]{3}-[0-9]{5}-[0-9]{2,24}$/'],
            'account_open_date' => ['required', 'date_format:Y-m-d\TH:i:s.v\Z'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
        }

        $alias = $validator->validated()['alias'];
        if (empty($alias['email_address']) && empty($alias['mobile_number'])) {
            return $this->errorResponse('Either alias.email_address or alias.mobile_number is required', 'VALIDATION_ERROR', 422);
        }
        if (!empty($alias['email_address']) && !empty($alias['mobile_number'])) {
            return $this->errorResponse('email_address and mobile_number are mutually exclusive', 'VALIDATION_ERROR', 422);
        }

        return $this->respond($this->interacService->createAlias($customerId, $validator->validated()), 201);
    }

    public function deleteAlias(string $customerId, string $aliasId)
    {
        $this->validateCustomerId($customerId);
        return $this->respond($this->interacService->deleteAlias($customerId, $aliasId), 204);
    }

    public function getAlias(string $customerId, string $aliasId)
    {
        $this->validateCustomerId($customerId);
        return $this->respond($this->interacService->getAlias($customerId, $aliasId), 200);
    }

    public function listAliases(Request $request, string $customerId)
    {
        $this->validateCustomerId($customerId);
        $offset = (int) $request->query('offset', 0);
        $maxItems = (int) $request->query('max_items', 0);

        return $this->respond($this->interacService->listAliases($customerId, $offset, $maxItems), 200);
    }

    protected function validateCustomerId(string $customerId): void
    {
        if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $customerId)) {
            abort(response()->json([
                'success' => false,
                'message' => 'Invalid customer_id format',
                'code' => 'VALIDATION_ERROR',
                'errors' => ['customer_id' => ['customer_id must be alphanumeric and 6-12 chars']],
                'data' => null,
            ], 422));
        }
    }


    public function retrievePaymentOptions(Request $request)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'contact' => ['required', 'array'],
        'contact.email_address' => ['nullable', 'email', 'max:64'],
        'contact.mobile_number' => ['nullable', 'string', 'size:10'],
        'contact.account_number' => ['nullable', 'regex:/^[0-9]{3}-[0-9]{5}-[0-9]{2,24}$/'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->retrievePaymentOptions($validator->validated()), 200);
}

public function initiatePayment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'amount' => ['required', 'numeric', 'min:0.01'],
        'payment_type' => ['required', 'in:ALIAS_REGULAR,ALIAS_AUTODEPOSIT,ALIAS_REALTIME,ACCOUNT_DEPOSIT_REGULAR,ACCOUNT_DEPOSIT_REALTIME,REQUEST_FULFILLMENT'],
        'contact' => ['required', 'array'],
        'contact.name' => ['required', 'string', 'min:1', 'max:100'],
        'account_name' => ['required', 'string', 'min:3', 'max:80'],
        'account_number' => ['required', 'regex:/^[0-9]{3}-[0-9]{5}-[0-9]{2,24}$/'],
        'device_info' => ['required', 'array'],
        'device_info.authentication_method' => ['required', 'in:PASSWORD,PERSONAL_VERIFICATION_QUESTION,FINGERPRINT,BIOMETRICS,ONE_TIME_PASSWORD,NONE'],
        'device_info.ip_address' => ['required', 'ip'],
        'device_info.device_fingerprint' => ['required', 'string', 'min:1', 'max:256'],
        'device_info.device_fingerprint_type' => ['required', 'in:UNIQUE_DEVICE_IDENTIFIER,COOKIE_DEVICE_IDENTIFIER'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->initiatePayment($validator->validated()), 201);
}

public function submitPayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'account_transaction_ref_id' => ['required', 'string', 'min:16', 'max:36'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->submitPayment($paymentRefId, $validator->validated()), 201);
}

public function reverseInitiatedPayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'reason_code' => ['required', 'in:CUSTOMER_INITIATED,AGENT_INITIATED,SYSTEM_INITIATED,OTHER'],
        'reason_description' => ['nullable', 'string', 'min:2', 'max:100'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->reverseInitiatedPayment($paymentRefId, $validator->validated()), 204);
}

public function cancelPayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'reason_code' => ['required', 'in:CUSTOMER_INITIATED,AGENT_INITIATED,SYSTEM_INITIATED,OTHER'],
        'reason_description' => ['nullable', 'string', 'min:2', 'max:100'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->cancelPayment($paymentRefId, $validator->validated()), 204);
}

public function getPayment(string $paymentRefId)
{
    return $this->respond($this->interacService->getPayment($paymentRefId), 200);
}

public function listPayments(Request $request)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'from_date' => ['nullable', 'date'],
        'to_date' => ['nullable', 'date'],
        'type' => ['nullable', 'in:INBOUND,OUTBOUND'],
        'offset' => ['nullable', 'integer', 'min:0'],
        'max_items' => ['nullable', 'integer', 'min:1', 'max:25'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    $query = array_filter($validator->validated(), fn ($v) => $v !== null);
    return $this->respond($this->interacService->listPayments($query), 200);
}



public function retrieveIncomingPayment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'interac_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{8,35}$/'],
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->retrieveIncomingPayment($validator->validated()), 200);
}

public function authenticateIncomingPayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'payment_authentication.answer' => ['required', 'string', 'min:3', 'max:25'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->authenticateIncomingPayment($paymentRefId, $validator->validated()), 204);
}

public function initiateReceivePayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'accountName' => ['required', 'string', 'min:3', 'max:80'],
        'accountNumber' => ['nullable', 'regex:/^[0-9]{3}-[0-9]{5}-[0-9]{2,24}$/'],
        'device_info' => ['required', 'array'],
        'device_info.authentication_method' => ['required', 'in:PASSWORD,PERSONAL_VERIFICATION_QUESTION,FINGERPRINT,BIOMETRICS,ONE_TIME_PASSWORD,NONE'],
        'device_info.ip_address' => ['required', 'ip'],
        'device_info.device_fingerprint' => ['required', 'string', 'min:1', 'max:256'],
        'device_info.device_fingerprint_type' => ['required', 'in:COOKIE_DEVICE_IDENTIFIER,UNIQUE_DEVICE_IDENTIFIER'],
        'memo' => ['nullable', 'string', 'max:420'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->initiateReceivePayment($paymentRefId, $validator->validated()), 201);
}

public function submitReceivePayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'accountTransactionRefId' => ['required', 'string', 'min:16', 'max:36', 'regex:/^[a-zA-Z0-9\-.]+$/'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->submitReceivePayment($paymentRefId, $validator->validated()), 201);
}

public function reverseReceivePayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'reason_code' => ['required', 'in:CUSTOMER_INITIATED,AGENT_INITIATED,SYSTEM_INITIATED,OTHER'],
        'reason_description' => ['nullable', 'string', 'min:2', 'max:100'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->reverseReceivePayment($paymentRefId, $validator->validated()), 204);
}

public function declineReceivePayment(Request $request, string $paymentRefId)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'reason_code' => ['required', 'in:CUSTOMER_INITIATED,AGENT_INITIATED,SYSTEM_INITIATED,OTHER'],
        'reason_description' => ['nullable', 'string', 'min:2', 'max:100'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->declineReceivePayment($paymentRefId, $validator->validated()), 204);
}


public function createRequestPayment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'end_to_end_id' => ['nullable', 'string', 'min:6', 'max:32', 'regex:/^[a-zA-Z0-9\-.]+$/'],
        'contact' => ['required', 'array'],
        'contact.name' => ['required', 'string', 'min:3', 'max:100'],
        'contact.email_address' => ['nullable', 'email', 'max:64'],
        'contact.mobile_number' => ['nullable', 'string', 'size:10'],
        'contact.amount' => ['required', 'numeric', 'min:0.01'],
        'contact.account_name' => ['required', 'string', 'min:3', 'max:80'],
        'contact.account_number' => ['required', 'regex:/^[0-9]{3}-[0-9]{5}-[0-9]{2,24}$/'],
        'contact.options.amount_modification' => ['nullable', 'boolean'],
        'contact.options.expire_after_days' => ['nullable', 'integer', 'min:1'],
        'contact.options.enable_notification' => ['nullable', 'boolean'],
        'language' => ['nullable', 'in:EN,FR'],
        'memo' => ['nullable', 'string', 'max:420'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->createRequestPayment($validator->validated()), 201);
}

public function getRequestPayment(string $requestId)
{
    if (!preg_match('/^[a-zA-Z0-9]{16,36}$/', $requestId)) {
        return $this->errorResponse('Invalid request_id format', 'VALIDATION_ERROR', 422);
    }

    return $this->respond($this->interacService->getRequestPayment($requestId), 200);
}

public function cancelRequestPayment(Request $request, string $requestId)
{
    if (!preg_match('/^[a-zA-Z0-9]{16,36}$/', $requestId)) {
        return $this->errorResponse('Invalid request_id format', 'VALIDATION_ERROR', 422);
    }

    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'end_to_end_id' => ['required', 'string', 'min:6', 'max:36', 'regex:/^[a-zA-Z0-9\-.]+$/'],
        'reason_code' => ['required', 'in:CUSTOMER_INITIATED,AGENT_INITIATED,SYSTEM_INITIATED,OTHER'],
        'reason_description' => ['nullable', 'string', 'min:2', 'max:100'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->cancelRequestPayment($requestId, $validator->validated()), 204);
}

public function retrieveIncomingRequestPayment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'network_request_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{8,35}$/'],
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->retrieveIncomingRequestPayment($validator->validated()), 200);
}

public function declineIncomingRequestPayment(Request $request, string $networkRequestRefId)
{
    if (!preg_match('/^[a-zA-Z0-9]{6,36}$/', $networkRequestRefId)) {
        return $this->errorResponse(
            'Invalid network_request_ref_id format',
            'VALIDATION_ERROR',
            422,
            ['network_request_ref_id' => ['Must be alphanumeric and 6-36 characters']]
        );
    }

    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'reason_code' => ['required', 'in:CUSTOMER_INITIATED,AGENT_INITIATED,SYSTEM_INITIATED,OTHER'],
        'reason_description' => ['nullable', 'string', 'min:2', 'max:100'],
    ]);

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond(
        $this->interacService->declineIncomingRequestPayment($networkRequestRefId, $validator->validated()),
        204
    );
}


public function updateFraudStatus(Request $request)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => ['required', 'regex:/^[a-zA-Z0-9]{6,12}$/'],
        'ref_id' => ['required', 'array'],
        'ref_id.payment_ref_id' => ['required', 'regex:/^[a-zA-Z0-9]{16,36}$/'],
        'fraud_data' => ['required', 'array'],
        'fraud_data.fraud_status' => ['required', 'in:CONFIRM_FRAUD,CONFIRM_LEGITIMATE,SCAM,PRESUME_LEGITIMATE,SUSPICIOUS'],
        'fraud_data.fraud_type' => ['nullable', 'in:ACCOUNT_TAKEOVER,INTERCEPTED_PAYMENT,INVSTM_LOAN_SCAM,JOB_SCAM,BUYER_SELLER_SCAM,ROMANCE_SCAM,THRT_EMERG_SCAM,BUSINESS_EMAIL_COMPROMISE,VENDOR_EMAIL_COMPROMISE,APPLICATION_FRAUD,FRAUD_BUSINESS,OTHER'],
        'fraud_data.memo' => ['nullable', 'string', 'min:1', 'max:420'],
    ]);

    $validator->after(function ($validator) use ($request) {
        $status = $request->input('fraud_data.fraud_status');
        $type = $request->input('fraud_data.fraud_type');
        $memo = $request->input('fraud_data.memo');

        if (in_array($status, ['CONFIRM_FRAUD', 'SCAM'], true) && empty($type)) {
            $validator->errors()->add('fraud_data.fraud_type', 'fraud_type is required when fraud_status is CONFIRM_FRAUD or SCAM.');
        }

        if ($status === 'SCAM' && $type === 'OTHER' && empty($memo)) {
            $validator->errors()->add('fraud_data.memo', 'memo is required when fraud_status is SCAM and fraud_type is OTHER.');
        }
    });

    if ($validator->fails()) {
        return $this->errorResponse('Validation failed', 'VALIDATION_ERROR', 422, $validator->errors());
    }

    return $this->respond($this->interacService->updateFraudStatus($validator->validated()), 204);
}



    protected function respond(array $result, int $successStatus)
    {
        if (!$result['success']) {
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
