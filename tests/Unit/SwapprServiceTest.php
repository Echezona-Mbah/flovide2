<?php

use App\Services\SwapprService;
use App\Services\CadInteracCollectionService;
use App\Models\Beneficia;
use App\Models\PaymentProvider;
use App\Models\PaymentProviderCurrency;
use App\Services\ProviderRoutingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

uses(Tests\TestCase::class);

it('sends authenticated payouts with the persisted idempotency key', function () {
    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.test_api_key' => 'sk_test_example',
    ]);

    Http::fake([
        'api.swappr.me/api/v1/payouts' => Http::response([
            'id' => 'ckpayout123',
            'reference' => 'po_example123',
            'status' => 'queued',
        ], 201),
    ]);

    $payload = [
        'amount_minor' => '150000',
        'currency' => 'NGN',
        'recipient' => [
            'account_number' => '0690000032',
            'bank_code' => '044',
        ],
        'sender' => [
            'first_name' => 'Test',
            'last_name' => 'Sender',
            'id_type' => 'Passport',
            'id_number' => 'TEST123',
            'id_expiry' => '31122030',
            'date_of_birth' => '15081990',
            'country' => 'NG',
        ],
        'merchant_reference' => 'ref-transaction-uuid',
    ];

    $response = app(SwapprService::class)->createPayout($payload, 'transaction-uuid', 'test');

    expect($response['success'])->toBeTrue()
        ->and($response['data']['status'])->toBe('queued');

    Http::assertSent(fn (Request $request) =>
        $request->url() === 'https://api.swappr.me/api/v1/payouts'
        && $request->hasHeader('Authorization', 'Bearer sk_test_example')
        && $request->hasHeader('Idempotency-Key', 'transaction-uuid')
        && $request->data() === $payload
    );
});

it('does not use a key from the wrong Swappr environment', function () {
    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.test_api_key' => 'sk_test_example',
        'services.swappr.live_api_key' => null,
        'services.swappr.api_key' => null,
    ]);

    expect(fn () => app(SwapprService::class)->getBanks('NGN', 'live'))
        ->toThrow(RuntimeException::class, 'Set SWAPPR_LIVE_API_KEY');
});

it('uses the live API key for live bank requests', function () {
    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.test_api_key' => 'sk_test_example',
        'services.swappr.live_api_key' => 'sk_live_example',
    ]);

    Http::fake([
        'api.swappr.me/api/v1/banks*' => Http::response(['data' => []], 200),
    ]);

    app(SwapprService::class)->getBanks('NGN', 'live');

    Http::assertSent(fn (Request $request) =>
        $request->hasHeader('Authorization', 'Bearer sk_live_example')
    );
});

it('resolves NGN account names through Swappr with an idempotency key', function () {
    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.test_api_key' => 'sk_test_example',
    ]);

    Http::fake([
        'api.swappr.me/api/v1/name-enquiry' => Http::response([
            'resolved_name' => 'ADAEZE BLESSING NWAFOR',
            'source' => 'provider',
            'resolved_by_provider' => 'rail-01',
        ], 200),
    ]);

    $response = app(SwapprService::class)->nameEnquiry(
        '0690000032',
        '044',
        'Access Bank',
        'name-enquiry-operation-uuid',
        'test'
    );

    expect($response['success'])->toBeTrue()
        ->and($response['data']['resolved_name'])->toBe('ADAEZE BLESSING NWAFOR');

    Http::assertSent(fn (Request $request) => true);

    $request = Http::recorded()->first()[0];
    expect($request->url())->toBe('https://api.swappr.me/api/v1/name-enquiry')
        ->and($request->hasHeader('Authorization'))->toBeTrue()
        ->and($request->hasHeader('Idempotency-Key', 'name-enquiry-operation-uuid'))->toBeTrue()
        ->and($request->data())->toBe([
        'account_number' => '0690000032',
        'bank_code' => '044',
        'currency' => 'NGN',
        'bank_name' => 'Access Bank',
    ]);
});

it('validates Swappr sender information formats', function () {
    $sender = [
        'first_name' => 'Test',
        'last_name' => 'Sender',
        'id_type' => 'Passport',
        'id_number' => 'TEST123',
        'id_expiry' => '31122030',
        'date_of_birth' => '15081990',
        'country' => 'NG',
    ];

    expect(Validator::make(
        ['sender' => $sender],
        SwapprService::senderInformationValidationRules()
    )->passes())->toBeTrue();

    $sender['id_expiry'] = '2030-12-31';
    $sender['country'] = 'ng';

    expect(Validator::make(
        ['sender' => $sender],
        SwapprService::senderInformationValidationRules()
    )->fails())->toBeTrue();

});

it('builds sender information from configured actual sender values', function () {
    config([
        'services.swappr.sender' => [
            'first_name' => 'Ada',
            'last_name' => 'Nwafor',
            'id_type' => 'National-ID',
            'id_number' => 'TEST-ID',
            'id_expiry' => '31122030',
            'date_of_birth' => '15081990',
            'country' => 'NG',
        ],
    ]);

    expect(SwapprService::senderInformationFromConfig())->toBe([
        'first_name' => 'Ada',
        'last_name' => 'Nwafor',
        'id_type' => 'National-ID',
        'id_number' => 'TEST-ID',
        'id_expiry' => '31122030',
        'date_of_birth' => '15081990',
        'country' => 'NG',
    ]);

    config(['services.swappr.sender.id_expiry' => '2030-12-31']);

    expect(SwapprService::senderInformationFromConfig())->toBeNull();
});

it('uses only a valid configured Swappr CAD Interac address', function () {
    config(['services.swappr.cad_interac_email' => 'collections@example.com']);

    expect(app(CadInteracCollectionService::class)->swapprRecipientEmail())
        ->toBe('collections@example.com');

    config(['services.swappr.cad_interac_email' => 'not-an-email']);

    expect(app(CadInteracCollectionService::class)->swapprRecipientEmail())
        ->toBeNull();
});

it('syncs a CAD Interac beneficiary using the returned beneficiary ID', function () {
    Schema::dropIfExists('beneficias');
    Schema::create('beneficias', function ($table) {
        $table->uuid('id')->primary();
        $table->string('currency');
        $table->string('alias')->nullable();
        $table->string('beneficiary_name')->nullable();
        $table->string('account_name')->nullable();
        $table->string('first_names')->nullable();
        $table->string('last_name')->nullable();
        $table->string('email')->nullable();
        $table->string('interac_first_name')->nullable();
        $table->string('interac_last_name')->nullable();
        $table->string('mode')->nullable();
        $table->string('swappr_beneficiary_id')->nullable();
        $table->string('provider')->nullable();
        $table->string('bank_code')->nullable();
        $table->string('flovide_bank_code')->nullable();
        $table->timestamps();
    });

    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.live_api_key' => 'sk_live_example',
    ]);

    Http::fake(function (Request $request) {
        if ($request->method() === 'POST' && str_ends_with($request->url(), '/beneficiaries')) {
            return Http::response(null, 200);
        }

        if ($request->method() === 'GET' && str_contains($request->url(), '/beneficiaries?')) {
            return Http::response([
                'object' => 'list',
                'has_more' => false,
                'data' => [
                    [
                        'object' => 'beneficiary',
                        'id' => 'swp_beneficiary_123',
                        'currency' => 'CAD',
                        'interac_email' => 'recipient@example.ca',
                    ],
                ],
            ], 200);
        }

        return Http::response(['message' => 'Unexpected Swappr request'], 500);
    });

    $beneficiary = Beneficia::create([
        'id' => 'local-beneficiary-123',
        'currency' => 'CAD',
        'beneficiary_name' => 'Test Recipient',
        'email' => 'recipient@example.ca',
        'interac_first_name' => 'Test',
        'interac_last_name' => 'Recipient',
        'mode' => 'live',
        'provider' => 'swappr',
    ]);

    $result = app(SwapprService::class)->syncBeneficiary($beneficiary);

    expect($result['success'])->toBeTrue()
        ->and($result['beneficiary_id'])->toBe('swp_beneficiary_123')
        ->and($beneficiary->fresh()->swappr_beneficiary_id)->toBe('swp_beneficiary_123');

    Http::assertSent(fn (Request $request) =>
        $request->url() === 'https://api.swappr.me/api/v1/beneficiaries'
        && $request->data()['currency'] === 'CAD'
        && $request->data()['name'] === 'Test Recipient'
        && $request->data()['interac_email'] === 'recipient@example.ca'
        && $request->data()['first_name'] === 'Test'
        && $request->data()['last_name'] === 'Recipient'
        && $request->data()['external_reference'] === $beneficiary->id
    );

    Http::assertSent(function (Request $request) use ($beneficiary) {
        if ($request->method() !== 'GET') {
            return false;
        }

        $query = [];
        parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

        return str_starts_with($request->url(), 'https://api.swappr.me/api/v1/beneficiaries?')
            && ($query['currency'] ?? null) === 'CAD'
            && ($query['limit'] ?? null) === '100'
            && ($query['external_reference'] ?? null) === $beneficiary->id
            && !array_key_exists('q', $query);
    });
});

it('prepares a saved CAD beneficiary for payout without persisting the Swappr ID locally', function () {
    Schema::dropIfExists('beneficias');
    Schema::create('beneficias', function ($table) {
        $table->uuid('id')->primary();
        $table->string('currency');
        $table->string('alias')->nullable();
        $table->string('beneficiary_name')->nullable();
        $table->string('account_name')->nullable();
        $table->string('first_names')->nullable();
        $table->string('last_name')->nullable();
        $table->string('email')->nullable();
        $table->string('interac_first_name')->nullable();
        $table->string('interac_last_name')->nullable();
        $table->string('mode')->nullable();
        $table->string('swappr_beneficiary_id')->nullable();
        $table->string('provider')->nullable();
        $table->string('bank_code')->nullable();
        $table->string('flovide_bank_code')->nullable();
        $table->timestamps();
    });

    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.live_api_key' => 'sk_live_example',
    ]);

    Http::fake(function (Request $request) {
        if ($request->method() === 'POST' && str_ends_with($request->url(), '/beneficiaries')) {
            return Http::response(['created' => true], 201);
        }

        if ($request->method() === 'GET' && str_contains($request->url(), '/beneficiaries?')) {
            return Http::response([
                'object' => 'list',
                'has_more' => false,
                'data' => [
                    [
                        'object' => 'beneficiary',
                        'id' => 'ben_swappr_cad_123',
                        'currency' => 'CAD',
                        'interac_email' => 'jane.doe@example.com',
                    ],
                ],
            ], 200);
        }

        return Http::response(['message' => 'Unexpected Swappr request'], 500);
    });

    $beneficiary = Beneficia::create([
        'id' => 'local-beneficiary-456',
        'currency' => 'CAD',
        'beneficiary_name' => 'Jane Doe',
        'email' => 'jane.doe@example.com',
        'interac_first_name' => 'Jane',
        'interac_last_name' => 'Doe',
        'mode' => 'live',
        'provider' => 'blaaiz_interac',
    ]);

    $result = app(SwapprService::class)->createCadBeneficiaryForPayout(
        $beneficiary,
        'payout-beneficiary-transaction-456'
    );

    expect($result['success'])->toBeTrue()
        ->and($result['beneficiary_id'])->toBe('ben_swappr_cad_123')
        ->and($beneficiary->fresh()->swappr_beneficiary_id)->toBeNull();

    Http::assertSent(fn (Request $request) =>
        $request->url() === 'https://api.swappr.me/api/v1/beneficiaries'
        && $request->hasHeader('Idempotency-Key', 'payout-beneficiary-transaction-456')
        && $request->data() === [
            'currency' => 'CAD',
            'name' => 'Jane Doe',
            'interac_email' => 'jane.doe@example.com',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'external_reference' => $beneficiary->id,
        ]
    );

    Http::assertSent(function (Request $request) {
        if ($request->method() !== 'GET') {
            return false;
        }

        $query = [];
        parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

        return str_starts_with($request->url(), 'https://api.swappr.me/api/v1/beneficiaries?')
            && ($query['currency'] ?? null) === 'CAD'
            && ($query['limit'] ?? null) === '100'
            && !array_key_exists('q', $query);
    });
});

it('submits a CAD payout using the Swappr beneficiary ID without saving it locally', function () {
    Schema::dropIfExists('transactions_history');
    Schema::dropIfExists('balances');
    Schema::dropIfExists('beneficias');

    Schema::create('transactions_history', function ($table) {
        $table->string('id')->primary();
        $table->string('balance_id')->nullable();
        $table->string('fee_balance_id')->nullable();
        $table->string('reference')->nullable();
        $table->string('recipient_account_name')->nullable();
        $table->string('status')->nullable();
        $table->string('payment_provider')->nullable();
        $table->string('failure_reason')->nullable();
        $table->string('order_id')->nullable();
        $table->decimal('total_amount', 12, 2)->nullable();
        $table->decimal('fees', 12, 2)->nullable();
        $table->decimal('recipient_amount', 12, 2)->nullable();
        $table->string('mode')->nullable();
        $table->timestamps();
    });

    Schema::create('balances', function ($table) {
        $table->string('id')->primary();
        $table->decimal('amount', 12, 2)->default(0);
        $table->timestamps();
    });

    Schema::create('beneficias', function ($table) {
        $table->uuid('id')->primary();
        $table->string('currency');
        $table->string('alias')->nullable();
        $table->string('beneficiary_name')->nullable();
        $table->string('account_name')->nullable();
        $table->string('first_names')->nullable();
        $table->string('last_name')->nullable();
        $table->string('email')->nullable();
        $table->string('interac_first_name')->nullable();
        $table->string('interac_last_name')->nullable();
        $table->string('mode')->nullable();
        $table->string('swappr_beneficiary_id')->nullable();
        $table->string('provider')->nullable();
        $table->string('bank_code')->nullable();
        $table->string('flovide_bank_code')->nullable();
        $table->timestamps();
    });

    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.test_api_key' => 'sk_test_example',
    ]);

    Http::fake([
        'api.swappr.me/api/v1/beneficiaries' => Http::response([
            'data' => ['id' => 'ben_swappr_cad_789'],
        ], 201),
        'api.swappr.me/api/v1/payouts' => Http::response([
            'id' => 'po_swappr_cad_789',
            'status' => 'queued',
        ], 201),
    ]);

    $beneficiary = Beneficia::create([
        'id' => 'local-beneficiary-789',
        'currency' => 'CAD',
        'beneficiary_name' => 'Jane Doe',
        'email' => 'jane.doe@example.com',
        'interac_first_name' => 'Jane',
        'interac_last_name' => 'Doe',
        'mode' => 'test',
        'provider' => 'blaaiz_interac',
    ]);
    $transaction = \App\Models\TransactionHistory::create([
        'id' => 'transaction-cad-789',
        'reference' => 'CA-PAY-789',
        'status' => 'pending',
        'mode' => 'test',
    ]);

    $result = app(SwapprService::class)->submitPayout($transaction, [
        'amount_minor' => '75000',
        'currency' => 'CAD',
        'method' => 'interac',
        'recipient' => [
            'email' => 'jane.doe@example.com',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ],
        'merchant_reference' => 'CA-PAY-789',
    ], $beneficiary);

    expect($result['success'])->toBeTrue()
        ->and($beneficiary->fresh()->swappr_beneficiary_id)->toBeNull();

    Http::assertSent(fn (Request $request) =>
        $request->url() === 'https://api.swappr.me/api/v1/payouts'
        && $request->data() === [
            'amount_minor' => '75000',
            'currency' => 'CAD',
            'method' => 'interac',
            'merchant_reference' => 'CA-PAY-789',
            'beneficiary_id' => 'ben_swappr_cad_789',
        ]
    );
});

it('creates unique references for manual Swappr Interac reconciliation', function () {
    $collections = app(CadInteracCollectionService::class);
    $first = $collections->newReference();
    $second = $collections->newReference();

    expect($first)
        ->toStartWith('SWP-')
        ->and($second)->not->toBe($first);
});

it('routes collections independently from payout currency mappings', function () {
    Schema::dropIfExists('payment_provider_currencies');
    Schema::dropIfExists('payment_providers');
    Schema::create('payment_providers', function ($table) {
        $table->id();
        $table->string('key');
        $table->string('name');
        $table->boolean('is_enabled');
        $table->unsignedInteger('priority');
        $table->timestamps();
    });
    Schema::create('payment_provider_currencies', function ($table) {
        $table->id();
        $table->unsignedBigInteger('payment_provider_id');
        $table->string('currency', 6);
        $table->string('transfer_method')->nullable();
        $table->boolean('is_enabled');
        $table->boolean('collection_enabled');
        $table->timestamps();
    });

    $blaaiz = PaymentProvider::create([
        'key' => 'blaaiz_interac',
        'name' => 'Blaaiz Interac',
        'is_enabled' => true,
        'priority' => 30,
    ]);
    $swappr = PaymentProvider::create([
        'key' => 'swappr',
        'name' => 'Swappr',
        'is_enabled' => true,
        'priority' => 1,
    ]);
    PaymentProviderCurrency::create([
        'payment_provider_id' => $blaaiz->id,
        'currency' => 'CAD',
        'is_enabled' => true,
        'collection_enabled' => true,
    ]);
    $swapprCad = PaymentProviderCurrency::create([
        'payment_provider_id' => $swappr->id,
        'currency' => 'CAD',
        'is_enabled' => true,
        'collection_enabled' => false,
    ]);

    Cache::flush();
    $routing = app(ProviderRoutingService::class);

    expect($routing->resolveForCollection('CAD')?->key)->toBe('blaaiz_interac')
        ->and($routing->resolveForCurrency('CAD')?->key)->toBe('swappr');

    $swapprCad->collection_enabled = true;
    $swapprCad->save();
    ProviderRoutingService::clearCache();

    expect($routing->resolveForCollection('CAD')?->key)->toBe('swappr')
        ->and($routing->resolveForCurrency('CAD')?->key)->toBe('swappr');
});

it('saves a Swappr beneficiary with an idempotency key', function () {
    config([
        'services.swappr.base_url' => 'https://api.swappr.me/api/v1',
        'services.swappr.test_api_key' => 'sk_test_example',
    ]);

    Http::fake([
        'api.swappr.me/api/v1/beneficiaries' => Http::response([
            'id' => 'ckben_example',
            'currency' => 'CAD',
        ], 201),
    ]);

    $payload = [
        'currency' => 'CAD',
        'name' => 'Jane Doe',
        'interac_email' => 'jane@example.com',
        'interac_first_name' => 'Jane',
        'interac_last_name' => 'Doe',
    ];

    $response = app(SwapprService::class)->upsertBeneficiary($payload, 'beneficiary-operation-uuid', 'test');

    expect($response['success'])->toBeTrue();

    Http::assertSent(fn (Request $request) =>
        $request->url() === 'https://api.swappr.me/api/v1/beneficiaries'
        && $request->hasHeader('Authorization', 'Bearer sk_test_example')
        && $request->hasHeader('Idempotency-Key', 'beneficiary-operation-uuid')
        && $request->data() === $payload
    );
});
