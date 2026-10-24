<?php

use App\Services\SwapprService;
use App\Services\CadInteracCollectionService;
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
