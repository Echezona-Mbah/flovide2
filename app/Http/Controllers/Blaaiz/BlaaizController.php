<?php

namespace App\Http\Controllers\Blaaiz;

use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use App\Models\TeamMembers;
use App\Services\BlaaizService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BlaaizController extends Controller
{

    public function acceptInteracMoneyRequest(Request $request, BlaaizService $blaaiz)
        {
                // Log::info('[Interac] Request received', $request->all());
            // dd('ffffff');
            $validated = $request->validate([
                'security_answer' => 'nullable|string|max:255',
                'email'           => 'nullable|email',
                'amount'          => 'nullable|numeric|min:0',
                'currency'        => 'nullable|string|max:10',
                'interac_type'    => 'nullable|string|in:standard,auto',
            ]);

                    Log::info('[Interac] Validation passed', $validated);


            // Generate a unique reference number server-side
            $referenceNumber = 'ITC-' . strtoupper(Str::random(10));
            Log::info('[Interac] Generated reference number', ['reference_number' => $referenceNumber]);

            $payload = [
                'reference_number' => $referenceNumber,
            ];

            if (!empty($validated['security_answer'])) {
                $payload['security_answer'] = $validated['security_answer'];
                            Log::info('[Interac] Security answer included in payload');

            }

            if (!empty($validated['email'])) {
                $payload['email'] = $validated['email'];
            }

                    Log::info('[Interac] Sending payload to Blaaiz', $payload);


            $response = $blaaiz->initiateInteracMoneyRequest($payload);

            Log::info('[Interac] Blaaiz response received', [
                'success' => $response['success'],
                'status'  => $response['status'],
                'data'    => $response['data'],
            ]);
            // $response = ['success'];
            // ── Save to transaction history on success ───────────────────────────
            if ($response['success']) {
                $user    = auth()->user();
                $team    = TeamMembers::where('user_id', $user->id)->first();
                $ownerId = $team ? $team->owner_id : $user->id;

                // Pull any useful fields from Blaaiz's response data
                $responseData = $response['data'] ?? [];

                TransactionHistory::create([
                    'user_id'          => $ownerId,
                    'personal_id'      => null,
                    'payment_provider' => 'interac',
                    'transaction_type' => 'payment',
                    'method'           => 'credit',
                    'payment_method'   => $validated['interac_type'] ?? 'standard',
                    'sender'           => $validated['email'] ?? null,
                    'amount'           => $validated['amount'] ?? ($responseData['amount'] ?? 0),
                    'currency'         => $validated['currency'] ?? ($responseData['currency'] ?? 'CAD'),
                    'status'           => 'pending',
                    'reference'        => $referenceNumber,
                    'payment_reference'=> $responseData['reference'] ?? $referenceNumber,
                    'order_id'         => $responseData['id'] ?? null,
                ]);
            }

            return response()->json($response, $response['status']);
    }



     public function initiateInteracMoneyRequest(Request $request, BlaaizService $blaaiz)
    {
        // Log::info('[Interac Initiate] Request received', $request->all());
        $validated = $request->validate([
            'amount'   => 'required|numeric|min:0.1',
            'email'    => 'required|email',
            'currency' => 'nullable|string|max:10',
            'balance_id' => 'required|string',
        ]);

        // Check currency/balance belongs to this user
        $user = auth()->user();
        $balance = \App\Models\Balance::where('id', $validated['balance_id'])
            ->where('user_id', $user->id)
            ->first();

        

        if (!$balance) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found or does not belong to your account.',
                'code'    => 'INVALID_WALLET',
            ], 422);
        }

        $currency = strtoupper($validated['currency'] ?? $balance->currency ?? 'CAD');
        $amount   = (float) $validated['amount'];

        $platformFee = 0;

        $userFee = \App\Models\UserCurrencyFee::where('user_id', $user->id)
            ->where('currency', $currency)
            ->first();

        Log::info('[Interac Initiate] Fee lookup', [
            'user_id'  => $user->id,
            'currency' => $currency,
            'found'    => (bool) $userFee,
            'enabled'  => $userFee->collection_enabled ?? null,
        ]);

        if (!$userFee || !$userFee->collection_enabled) {
            $msg = "Contact your marketer to enable collection pricing for {$currency}.";
            return response()->json([
                'success' => false,
                'message' => $msg,
                'code'    => 'COLLECTION_DISABLED',
                'data'    => null,
            ], 422);
        }

        if ($userFee->collection_min > 0 && $amount < $userFee->collection_min) {
            $msg = "Minimum top-up for {$currency} is " . number_format($userFee->collection_min, 2);
            return response()->json([
                'success' => false,
                'message' => $msg,
                'code'    => 'BELOW_COLLECTION_MIN',
                'data'    => null,
            ], 422);
        }

        if ($userFee->collection_max > 0 && $amount > $userFee->collection_max) {
            $msg = "Maximum top-up for {$currency} is " . number_format($userFee->collection_max, 2);
            return response()->json([
                'success' => false,
                'message' => $msg,
                'code'    => 'ABOVE_COLLECTION_MAX',
                'data'    => null,
            ], 422);
        }

        // Use the model's own calculator instead of re-deriving the formula here
      $platformFee = $userFee->calcCollectionFee($amount);
        $netAmount   = $userFee->collectionAmountAfterFee($amount);

        Log::info('[Interac Initiate] Fee calculated', [
            'platform_fee' => $platformFee,
            'net_amount'   => $netAmount,
        ]);


        if ($amount <= $platformFee) {
            $msg = "Amount must be greater than the platform fee of " . number_format($platformFee, 2) . " {$currency}.";
            return response()->json([
                'success' => false,
                'message' => $msg,
                'code'    => 'AMOUNT_BELOW_FEE',
                'data'    => [
                    'amount'       => $amount,
                    'platform_fee' => $platformFee,
                ],
            ], 422);
        }


    
        $payload = [
            'amount' => $validated['amount'],
            'email'  => $validated['email'],
        ];
 
        Log::info('[Interac Initiate] Sending payload to Blaaiz', $payload);
 
        $response = $blaaiz->initiateInteracMoneyRequest($payload);
 
        Log::info('[Interac Initiate] Blaaiz response', [
            'success' => $response['success'],
            'status'  => $response['status'],
            'data'    => $response['data'],
        ]);
 
        if (!$response['success']) {
            Log::warning('[Interac Initiate] Blaaiz rejected request', $response['data'] ?? []);
 
            $errorMsg = $response['data']['message']
                ?? $response['data']['error_description']
                ?? 'Interac request failed. Please try again.';
 
            return response()->json([
                'success' => false,
                'message' => $errorMsg,
                'code'    => 'INTERAC_INITIATE_FAILED',
                'data'    => $response['data'] ?? null,
            ], $response['status']);
        }
 
        // ── Save to transaction history ──────────────────────────────────────
        $user         = auth()->user();
        // $team         = TeamMembers::where('user_id', $user->id)->first();
        // $ownerId      = $team ? $team->owner_id : $user->id;
        $responseData = $response['data'];
 
        $transaction = TransactionHistory::create([
            'user_id'          => $user->id,
            'balance_id'       => $balance->id,
            'personal_id'      => null,
            'payment_provider' => 'interac',
            'transaction_type' => 'payment',
            'method'           => 'credit',
            'payment_method'   => 'auto',
            'sender'           => $validated['email'],
            'amount'           => $validated['amount'],
            'fees'              => $platformFee,
            'platform_fee'      => $platformFee,
            'recipient_amount'  => $netAmount,
            'currency'         => $validated['currency'] ?? 'CAD',
            'status'           => 'pending',
            'reference'        => $responseData['reference'],        // e.g. CA1MRYdVQK2h
            'payment_reference'=> $responseData['reference'],
            'order_id'         => $responseData['transaction_id'],   // e.g. 48e3afc4-e928-...
        ]);
 
        Log::info('[Interac Initiate] Transaction saved', [
            'transaction_id'   => $transaction->id,
            'blaaiz_tx_id'     => $responseData['transaction_id'],
            'reference'        => $responseData['reference'],
            'amount'           => $validated['amount'],
            'currency'         => $validated['currency'] ?? 'CAD',
            'expires_at'       => $responseData['expires_at'],
        ]);
 
        return response()->json([
            'success' => true,
            'message' => $responseData['message'] ?? 'Interac money request sent successfully.',
            'code'    => 'INTERAC_INITIATED',
            'data'    => [
                'transaction_id' => $responseData['transaction_id'],
                'reference'      => $responseData['reference'],
                'expires_at'     => $responseData['expires_at'],
                'amount'         => $validated['amount'],
                'currency'       => $validated['currency'] ?? 'CAD',
                'email'          => $validated['email'],
            ],
        ], 200);
    }



    public function simulateInteracWebhook(Request $request, BlaaizService $blaaiz)
    {
        $request->validate([
            'interac_email' => 'required|email',
            'amount'        => 'required|numeric|min:1',
        ]);

        Log::info('[Interac Simulate] Triggering mock webhook', $request->all());

        $response = $blaaiz->simulateInteracWebhook(
            $request->interac_email,
            $request->amount
        );

        Log::info('[Interac Simulate] Response', $response);

        return response()->json([
            'success' => $response['success'],
            'message' => $response['data']['message'] ?? 'Webhook triggered',
            'code'    => $response['success'] ? 'WEBHOOK_SIMULATED' : 'WEBHOOK_FAILED',
            'data'    => $response['data'],
        ], $response['status']);
    }



    public function createCustomer(Request $request, BlaaizService $blaaiz)
    {
        Log::info('[Customer] Request received', $request->all());

        $type = $request->input('type');



        // Base validation
        $rules = [
            'type'    => 'required|in:individual,business',
            'email'   => 'required|email',
            'country' => 'required|string|size:2',
            'phone'   => 'nullable|string|max:20',
        ];


        // Individual-specific rules
        if ($type === 'individual') {
            $rules = array_merge($rules, [
                'first_name'      => 'required|string|max:100',
                'last_name'       => 'required|string|max:100',
                'id_type'         => 'required|in:drivers_license,passport,resident_permit',
                'id_number'       => 'required|string|max:100',
                'dob'             => 'nullable|date',
                'street'          => 'nullable|string|max:255',
                'city'            => 'nullable|string|max:100',
                'state'           => 'nullable|string|max:100',
                'zip_code'        => 'nullable|string|max:20',
                'id_expiry_date'  => 'nullable|date',
                'id_issue_date'   => 'nullable|date',
            ]);
        }

        // Business-specific rules
        if ($type === 'business') {
            $rules = array_merge($rules, [
                'business_name'        => 'required|string',
                'registration_number'  => 'required|string',
                'incorporation_country'=> 'required|string',
                'kyb_scope'            => 'nullable|in:FULL,MINIMAL',
                'business_type'        => 'nullable',
                'trading_name'         => 'nullable|string',
                'incorporation_date'   => 'nullable|date',
                'industry_type'        => 'nullable|string',
                'business_description' => 'nullable|string',
                'website'              => 'nullable|url',
                'source_of_funds'      => 'nullable',
                'estimated_annual_revenue' => 'nullable',
                'expected_monthly_payments' => 'nullable|integer|min:0',
                'account_purpose'      => 'nullable',
            ]);
        }

            // dd('ddd');


        $validated = $request->validate($rules);

        // dd($validated);

        Log::info('[Customer] Validation passed', ['type' => $type]);

        // Build payload — only include non-null values
        $payload = array_filter($validated, fn($v) => !is_null($v) && $v !== '');

        $response = $blaaiz->createCustomer($payload);

        Log::info('[Customer] Blaaiz response', [
            'success' => $response['success'],
            'status'  => $response['status'],
            'data'    => $response['data'],
        ]);

        if (!$response['success']) {
            Log::warning('[Customer] Blaaiz rejected request', $response['data'] ?? []);

            $errorMsg = $response['data']['message']
                ?? $response['data']['error_description']
                ?? 'Customer creation failed. Please try again.';

            return response()->json([
                'success' => false,
                'message' => $errorMsg,
                'code'    => 'CUSTOMER_CREATE_FAILED',
                'data'    => $response['data'] ?? null,
            ], $response['status']);
        }

        $responseData = $response['data']['data'] ?? $response['data'];

        // Save to local customers table
        $user = auth()->user();

        \App\Models\User::create([
            'user_id'             => $user->id,
            'blaaiz_id'           => $responseData['id'] ?? null,
            'email'               => $responseData['email'] ?? $validated['email'],
            'firstname'          => $responseData['first_name'] ?? null,
            'lastname'           => $responseData['last_name'] ?? null,
            'business_name'       => $responseData['business_name'] ?? null,
            'countries_id'             => $responseData['country'] ?? $validated['country'],
            'street_address'             => $responseData['id_type'] ?? null,
            'city'           => $responseData['id_number'] ?? null,
            'selfie_verification_status' => $responseData['verification_status'] ?? 'PENDING',
        ]);

        Log::info('[Customer] Customer saved locally', [
            'blaaiz_id' => $responseData['id'] ?? null,
            'user_id'   => $user->id,
            'type'      => $type,
        ]);

        return response()->json([
            'success' => true,
            'message' => $response['data']['message'] ?? 'Customer created successfully.',
            'code'    => 'CUSTOMER_CREATED',
            'data'    => $responseData,
        ], 201);
    }


    public function listCustomers(Request $request, BlaaizService $blaaiz)
    {
        Log::info('[Customer] List request', $request->all());

        $filters = array_filter([
            'email'               => $request->query('email'),
            'id_number'           => $request->query('id_number'),
            'registration_number' => $request->query('registration_number'),
            'verification_status' => $request->query('verification_status'),
            'type'                => $request->query('type'),
            'paginate'            => $request->query('paginate'),
            'page'                => $request->query('page'),
        ], fn($v) => !is_null($v) && $v !== '');

        $response = $blaaiz->listCustomers($filters);

        Log::info('[Customer] List response', [
            'success' => $response['success'],
            'status'  => $response['status'],
        ]);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['data']['message'] ?? 'Failed to retrieve customers.',
                'code'    => 'CUSTOMER_LIST_FAILED',
                'data'    => $response['data'] ?? null,
            ], $response['status']);
        }

        return response()->json([
            'success' => true,
            'message' => $response['data']['message'] ?? 'Customers retrieved successfully.',
            'code'    => 'CUSTOMER_LIST_SUCCESS',
            'data'    => $response['data']['data'] ?? [],
            'links'   => $response['data']['links'] ?? null,  // only present when paginate=true
            'meta'    => $response['data']['meta']  ?? null,  // only present when paginate=true
        ], 200);
    }

    public function getCustomer(Request $request, BlaaizService $blaaiz, string $customerId)
    {
        Log::info('[Customer] Get request', ['customer_id' => $customerId]);

        $response = $blaaiz->getCustomer($customerId);

        Log::info('[Customer] Get response', [
            'success'     => $response['success'],
            'status'      => $response['status'],
            'customer_id' => $customerId,
        ]);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['data']['message'] ?? 'Customer not found.',
                'code'    => 'CUSTOMER_NOT_FOUND',
                'data'    => null,
            ], $response['status']);
        }

        $customer = $response['data']['data'] ?? $response['data'];

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully.',
            'code'    => 'CUSTOMER_FOUND',
            'data'    => $customer,
        ], 200);
    }

    public function listWallets(BlaaizService $blaaiz)
    {
        Log::info('[Wallet] List request');

        $response = $blaaiz->wallets();

        Log::info('[Wallet] List response', [
            'success' => $response['success'],
            'status'  => $response['status'],
        ]);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['data']['message'] ?? 'Failed to retrieve wallets.',
                'code'    => 'WALLET_LIST_FAILED',
                'data'    => $response['data'] ?? null,
            ], $response['status']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Wallets retrieved successfully.',
            'code'    => 'WALLET_LIST_SUCCESS',
            'data'    => $response['data'],
        ], 200);
    }

    public function createPayout(Request $request, BlaaizService $blaaiz)
    {
        Log::info('[Payout] Request received', $request->all());

        $method = $request->input('method');
        $toCurrency = strtoupper($request->input('to_currency_id', ''));

        // Base rules
        $rules = [
            'wallet_id'        => 'required|string',
            'customer_id'      => 'required|string',
            'method'           => 'required|in:bank_transfer,interac,crypto,wire,ach',
            'from_currency_id' => 'required|string',
            'to_currency_id'   => 'required|string',
            'from_amount'      => 'nullable|numeric|min:0.01',
            'to_amount'        => 'nullable|numeric|min:0.01',
            'note'             => 'nullable|string|max:255',
        ];

        // Interac
        if ($method === 'interac') {
            $rules['email']              = 'required|email';
            $rules['interac_first_name'] = 'required|string';
            $rules['interac_last_name']  = 'required|string';
        }

        // Bank transfer
        if ($method === 'bank_transfer') {
            if ($toCurrency === 'NGN') {
                $rules['bank_id']        = 'required|string';
                $rules['account_number'] = 'required|string';
            }
            if ($toCurrency === 'GBP') {
                $rules['account_number'] = 'required|string';
                $rules['account_name']   = 'required|string';
                $rules['sort_code']      = 'required|string';
                $rules['country']        = 'required|string';
                $rules['street']         = 'required|string';
                $rules['city']           = 'required|string';
                $rules['zip_code']       = 'required|string';
            }
            if ($toCurrency === 'EUR') {
                $rules['iban']           = 'required|string';
                $rules['bic_code']       = 'required|string';
                $rules['account_name']   = 'required|string';
                $rules['country']        = 'required|string';
                $rules['street']         = 'required|string';
                $rules['city']           = 'required|string';
                $rules['zip_code']       = 'required|string';
            }
        }

        // ACH / Wire (USD)
        if (in_array($method, ['ach', 'wire'])) {
            $rules['type']           = 'required|in:individual,business';
            $rules['account_number'] = 'required|string';
            $rules['account_name']   = 'required|string';
            $rules['account_type']   = 'required|in:checking,savings';
            $rules['bank_name']      = 'required|string';
            $rules['routing_number'] = 'required|string';
            $rules['country']        = 'required|string';
            $rules['state']          = 'required|string';
            $rules['street']         = 'required|string';
            $rules['city']           = 'required|string';
            $rules['zip_code']       = 'required|string';

            if ($method === 'wire') {
                $rules['swift_code'] = 'required|string';
            }
        }

        // Crypto
        if ($method === 'crypto') {
            $rules['wallet_address'] = 'required|string';
            $rules['wallet_token']   = 'required|in:USDT,USDC';
            $rules['wallet_network'] = 'required|in:BSC_MAINNET,ETHEREUM_MAINNET,TRON_MAINNET,MATIC_MAINNET';
        }

        $request->validate($rules);

        // Build payload — strip nulls
        $payload = array_filter($request->all(), fn($v) => !is_null($v) && $v !== '');
        unset($payload['_token']);

        Log::info('[Payout] Sending payload to Blaaiz', $payload);

        $response = $blaaiz->payout($payload);

        Log::info('[Payout] Blaaiz response', [
            'success' => $response['success'],
            'status'  => $response['status'],
            'data'    => $response['data'],
        ]);

        if (!$response['success']) {
            $errorMsg = $response['data']['message']
                ?? $response['data']['error_description']
                ?? 'Payout failed. Please try again.';

            return response()->json([
                'success' => false,
                'message' => $errorMsg,
                'code'    => 'PAYOUT_FAILED',
                'data'    => $response['data'] ?? null,
            ], $response['status']);
        }

        $transaction = $response['data']['transaction'] ?? $response['data'];

        return response()->json([
            'success' => true,
            'message' => $response['data']['message'] ?? 'Payout initiated successfully.',
            'code'    => 'PAYOUT_SUCCESS',
            'data'    => $transaction,
        ], 200);
    }


    public function getWebhookUrls(BlaaizService $blaaiz)
{
    $response = $blaaiz->getWebhookUrls();

    if (!$response['success']) {
        return response()->json([
            'success' => false,
            'message' => $response['data']['message'] ?? 'Failed to retrieve webhook URLs.',
            'code'    => 'WEBHOOK_FETCH_FAILED',
            'data'    => $response['data'] ?? null,
        ], $response['status']);
    }

    return response()->json([
        'success' => true,
        'message' => 'Webhook URLs retrieved.',
        'code'    => 'WEBHOOK_FETCHED',
        'data'    => $response['data'],
    ], 200);
}

public function registerWebhookUrls(Request $request, BlaaizService $blaaiz)
{
    $validated = $request->validate([
        'collection_url' => 'required|url',
        'payout_url'     => 'required|url',
    ]);

    $response = $blaaiz->registerWebhookUrls(
        $validated['collection_url'],
        $validated['payout_url']
    );

    if (!$response['success']) {
        return response()->json([
            'success' => false,
            'message' => $response['data']['message'] ?? 'Failed to register webhook URLs.',
            'code'    => 'WEBHOOK_REGISTER_FAILED',
            'data'    => $response['data'] ?? null,
        ], $response['status']);
    }

    return response()->json([
        'success' => true,
        'message' => 'Webhook URLs registered successfully.',
        'code'    => 'WEBHOOK_REGISTERED',
        'data'    => $response['data'],
    ], 200);
}








public function initiateAutoDeposit(Request $request)
{
    $validated = $request->validate([
        'amount'     => 'required|numeric|min:0.1',
        'currency'   => 'nullable|string|max:10',
        'balance_id' => 'required|string',
    ]);

    $user = auth()->user();
    $balance = \App\Models\Balance::where('id', $validated['balance_id'])
        ->where('user_id', $user->id)
        ->first();

    if (!$balance) {
        return response()->json([
            'success' => false,
            'message' => 'Wallet not found or does not belong to your account.',
            'code'    => 'INVALID_WALLET',
        ], 422);
    }

    $currency = strtoupper($validated['currency'] ?? $balance->currency ?? 'CAD');

    if ($currency !== 'CAD') {
        return response()->json([
            'success' => false,
            'message' => 'Auto Deposit is only available for the CAD wallet.',
            'code'    => 'AUTODEPOSIT_CAD_ONLY',
        ], 422);
    }

    $amount = (float) $validated['amount'];

    $userFee = \App\Models\UserCurrencyFee::where('user_id', $user->id)
        ->where('currency', $currency)
        ->first();

    Log::info('[Interac AutoDeposit] Fee lookup', [
        'user_id'  => $user->id,
        'currency' => $currency,
        'found'    => (bool) $userFee,
        'enabled'  => $userFee->collection_enabled ?? null,
    ]);

    if (!$userFee || !$userFee->collection_enabled) {
        return response()->json([
            'success' => false,
            'message' => "Contact your marketer to enable collection pricing for {$currency}.",
            'code'    => 'COLLECTION_DISABLED',
        ], 422);
    }

    if ($userFee->collection_min > 0 && $amount < $userFee->collection_min) {
        return response()->json([
            'success' => false,
            'message' => "Minimum top-up for {$currency} is " . number_format($userFee->collection_min, 2),
            'code'    => 'BELOW_COLLECTION_MIN',
        ], 422);
    }

    if ($userFee->collection_max > 0 && $amount > $userFee->collection_max) {
        return response()->json([
            'success' => false,
            'message' => "Maximum top-up for {$currency} is " . number_format($userFee->collection_max, 2),
            'code'    => 'ABOVE_COLLECTION_MAX',
        ], 422);
    }

    $platformFee = $userFee->calcCollectionFee($amount);
    $netAmount   = $userFee->collectionAmountAfterFee($amount);

    if ($amount <= $platformFee) {
        return response()->json([
            'success' => false,
            'message' => "Amount must be greater than the platform fee of " . number_format($platformFee, 2) . " {$currency}.",
            'code'    => 'AMOUNT_BELOW_FEE',
        ], 422);
    }

    $reference = 'ADEP-' . strtoupper(\Illuminate\Support\Str::random(10));

    $transaction = TransactionHistory::create([
        'user_id'           => $user->id,
        'balance_id'        => $balance->id,
        'personal_id'       => null,
        'payment_provider'  => 'interac',
        'transaction_type'  => 'payment',
        'method'            => 'credit',
        'payment_method'    => 'wallet_autodeposit', // distinct from 'standard'/'auto' request flows
        'sender'            => null,
        'amount'            => $amount,
        'fees'              => $platformFee,
        'platform_fee'      => $platformFee,
        'recipient_amount'  => $netAmount,
        'currency'          => $currency,
        'status'            => 'pending',
        'reference'         => $reference,
        'payment_reference' => $reference,
        'order_id'          => null,
    ]);

    Log::info('[Interac AutoDeposit] Pending transaction created', [
        'tx_id'     => $transaction->id,
        'reference' => $reference,
        'amount'    => $amount,
        'currency'  => $currency,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'We are watching for your Interac e-Transfer.',
        'code'    => 'AUTODEPOSIT_PENDING',
        'data'    => [
            'reference'     => $reference,
            'deposit_email' => 'payment@flovide.com',
            'amount'        => $amount,
            'currency'      => $currency,
        ],
    ], 200);
}

public function initiateAutoDepositPersonal(Request $request)
{
    $validated = $request->validate([
        'amount'     => 'required|numeric|min:0.1',
        'currency'   => 'nullable|string|max:10',
        'balance_id' => 'required|string',
    ]);

    $personalId = auth('personal-api')->id();

    if (!$personalId) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
            'code'    => 'UNAUTHENTICATED',
        ], 401);
    }

    $balance = \App\Models\Balance::where('id', $validated['balance_id'])
        ->where('personal_id', $personalId)
        ->first();

    if (!$balance) {
        return response()->json([
            'success' => false,
            'message' => 'Wallet not found or does not belong to your account.',
            'code'    => 'INVALID_WALLET',
        ], 422);
    }

    $currency = strtoupper($validated['currency'] ?? $balance->currency ?? 'CAD');

    if ($currency !== 'CAD') {
        return response()->json([
            'success' => false,
            'message' => 'Auto Deposit is only available for the CAD wallet.',
            'code'    => 'AUTODEPOSIT_CAD_ONLY',
        ], 422);
    }

    $amount = (float) $validated['amount'];

    $currencyFee = \App\Models\Currency::where('code', $currency)
        ->where('is_active', true)
        ->first();

    Log::info('[Interac AutoDeposit - Personal] Fee lookup', [
        'personal_id' => $personalId,
        'currency'    => $currency,
        'found'       => (bool) $currencyFee,
    ]);

    if (!$currencyFee) {
        return response()->json([
            'success' => false,
            'message' => "Collection is not currently available for {$currency}.",
            'code'    => 'COLLECTION_DISABLED',
        ], 422);
    }

    // ── No fee for personal Auto Deposit ──────────────────────────────
    $platformFee = 0;
    $netAmount   = $amount;

    $reference = 'ADEP-' . strtoupper(\Illuminate\Support\Str::random(10));

    $transaction = TransactionHistory::create([
        'user_id'           => null,
        'personal_id'       => $personalId,
        'balance_id'        => $balance->id,
        'payment_provider'  => 'interac',
        'transaction_type'  => 'payment',
        'method'            => 'credit',
        'payment_method'    => 'wallet_autodeposit',
        'sender'            => null,
        'amount'            => $amount,
        'fees'              => $platformFee,
        'platform_fee'      => $platformFee,
        'recipient_amount'  => $netAmount,
        'currency'          => $currency,
        'status'            => 'pending',
        'reference'         => $reference,
        'payment_reference' => $reference,
        'order_id'          => null,
    ]);

    Log::info('[Interac AutoDeposit - Personal] Pending transaction created', [
        'tx_id'       => $transaction->id,
        'personal_id' => $personalId,
        'reference'   => $reference,
        'amount'      => $amount,
        'currency'    => $currency,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'We are watching for your Interac e-Transfer.',
        'code'    => 'AUTODEPOSIT_PENDING',
        'data'    => [
            'method'     => 'credit',
            'reference'     => $reference,
            'deposit_email' => 'payments@flovide.com',
            'amount'        => $amount,
            'currency'      => $currency,
        ],
    ], 200);
}


public function checkInteracStatus(Request $request, string $reference)
{
    $user = auth()->user();

    $tx = TransactionHistory::where('reference', $reference)
        ->where('user_id', $user->id)
        ->first();

    if (!$tx) {
        return response()->json([
            'success' => false,
            'message' => 'Transaction not found.',
            'code'    => 'TX_NOT_FOUND',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'code'    => 'TX_STATUS',
        'data'    => [
            'reference' => $tx->reference,
            'status'    => $tx->status, // pending | success | failed
            'amount'    => $tx->amount,
            'currency'  => $tx->currency,
        ],
    ], 200);
}


}