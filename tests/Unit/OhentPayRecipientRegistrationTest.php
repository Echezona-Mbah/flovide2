<?php

use App\Models\Beneficia;
use App\Services\OhentPayService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

beforeEach(function () {
    Schema::dropIfExists('beneficias');
    Schema::dropIfExists('banks');

    Schema::create('banks', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('country_iso')->nullable();
        $table->string('currency')->nullable();
        $table->string('bank_code')->nullable();
        $table->string('provider_bank_id')->nullable();
        $table->string('bank_nibss_code')->nullable();
        $table->string('sort_code')->nullable();
        $table->string('provider')->nullable();
        $table->string('type')->nullable();
        $table->string('flovide_code')->nullable();
        $table->timestamps();
    });

    Schema::create('beneficias', function ($table) {
        $table->uuid('id')->primary();
        $table->string('country')->nullable();
        $table->string('currency')->nullable();
        $table->string('type')->nullable();
        $table->string('alias')->nullable();
        $table->string('first_names')->nullable();
        $table->string('last_name')->nullable();
        $table->string('beneficiary_name')->nullable();
        $table->string('account_number')->nullable();
        $table->string('account_name')->nullable();
        $table->string('bank')->nullable();
        $table->string('bank_code')->nullable();
        $table->string('flovide_bank_code')->nullable();
        $table->string('provider')->nullable();
        $table->string('ohentpay_recipient_id')->nullable();
        $table->timestamps();
    });

    config([
        'services.ohentpay.enabled' => true,
        'services.ohentpay.api_key' => 'ohentpay-test-token',
        'services.ohentpay.base_url' => 'https://api.ohentpay.test',
    ]);
});

it('registers a local beneficiary with OhentPay and saves its recipient id', function () {
    \App\Models\Bank::create([
        'name' => 'Kenya Test Bank',
        'country_iso' => 'KE',
        'currency' => 'KES',
        'bank_code' => 'provider-bank-12',
        'provider_bank_id' => '12',
        'provider' => 'ohentpay',
        'type' => 'bank',
        'flovide_code' => '501',
    ]);

    $beneficiary = Beneficia::create([
        'id' => 'local-beneficiary-123',
        'country' => 'KE',
        'currency' => 'KES',
        'type' => 'individual',
        'alias' => 'Jane Recipient',
        'account_name' => 'Jane Recipient',
        'account_number' => '1234567890',
        'bank_code' => '501',
        'flovide_bank_code' => '501',
        'provider' => 'ohentpay',
    ]);

    Http::fake([
        'api.ohentpay.test/recipients' => Http::response(['id' => 'ohentpay-recipient-456'], 201),
    ]);

    $result = app(OhentPayService::class)->registerBeneficiary($beneficiary);

    expect($result['success'])->toBeTrue()
        ->and($result['registered'])->toBeTrue()
        ->and($result['recipient_id'])->toBe('ohentpay-recipient-456')
        ->and($beneficiary->fresh()->ohentpay_recipient_id)->toBe('ohentpay-recipient-456');

    Http::assertSent(fn (Request $request) =>
        $request->method() === 'POST'
        && $request->url() === 'https://api.ohentpay.test/recipients'
        && $request->data() === [
            'country' => 'KE',
            'currency' => 'KES',
            'alias' => 'Jane Recipient',
            'type' => 'personal',
            'account_name' => 'Jane Recipient',
            'bank_id' => '12',
            'account_number' => '1234567890',
        ]
    );
});

it('does not register an already-linked beneficiary a second time', function () {
    $beneficiary = Beneficia::create([
        'id' => 'local-beneficiary-existing',
        'currency' => 'KES',
        'ohentpay_recipient_id' => 'ohentpay-recipient-existing',
    ]);

    Http::fake();

    $result = app(OhentPayService::class)->registerBeneficiary($beneficiary);

    expect($result['success'])->toBeTrue()
        ->and($result['registered'])->toBeFalse()
        ->and($result['recipient_id'])->toBe('ohentpay-recipient-existing');

    Http::assertNothingSent();
});

it('reports a registration failure without saving a recipient id', function () {
    \App\Models\Bank::create([
        'name' => 'Kenya Test Bank',
        'country_iso' => 'KE',
        'currency' => 'KES',
        'bank_code' => 'provider-bank-12',
        'provider_bank_id' => '12',
        'provider' => 'ohentpay',
        'type' => 'bank',
        'flovide_code' => '501',
    ]);

    $beneficiary = Beneficia::create([
        'id' => 'local-beneficiary-failed',
        'country' => 'KE',
        'currency' => 'KES',
        'type' => 'individual',
        'account_name' => 'Jane Recipient',
        'account_number' => '1234567890',
        'flovide_bank_code' => '501',
        'provider' => 'ohentpay',
    ]);

    Http::fake([
        'api.ohentpay.test/recipients' => Http::response(['message' => 'Invalid bank account'], 422),
    ]);

    $result = app(OhentPayService::class)->registerBeneficiary($beneficiary);

    expect($result['success'])->toBeFalse()
        ->and($result['code'])->toBe('OHENTPAY_RECIPIENT_REGISTRATION_FAILED')
        ->and($result['message'])->toBe('Invalid bank account')
        ->and($beneficiary->fresh()->ohentpay_recipient_id)->toBeNull();
});
