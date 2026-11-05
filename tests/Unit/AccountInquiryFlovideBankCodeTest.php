<?php

use App\Http\Controllers\AccountInquiryController;
use App\Models\Bank;
use App\Models\PaymentProvider;
use App\Models\PaymentProviderCurrency;
use App\Services\OhentPayService;
use App\Services\OrchardService;
use App\Services\PayazaService;
use App\Services\PivotService;
use App\Services\ProviderRoutingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

beforeEach(function () {
    Schema::dropIfExists('banks');
    Schema::dropIfExists('payment_provider_currencies');
    Schema::dropIfExists('payment_providers');

    Schema::create('banks', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('country_iso')->nullable();
        $table->string('currency')->nullable();
        $table->string('bank_code')->nullable();
        $table->string('flovide_code')->nullable();
        $table->string('provider')->nullable();
        $table->string('type')->nullable();
        $table->timestamps();
    });

    Schema::create('payment_providers', function ($table) {
        $table->id();
        $table->string('key');
        $table->string('name');
        $table->boolean('is_enabled')->default(true);
        $table->integer('priority')->default(0);
        $table->timestamps();
    });

    Schema::create('payment_provider_currencies', function ($table) {
        $table->id();
        $table->foreignId('payment_provider_id');
        $table->string('currency');
        $table->string('transfer_method')->nullable();
        $table->boolean('is_enabled')->default(true);
        $table->boolean('collection_enabled')->default(false);
        $table->timestamps();
    });
});

it('accepts a Flovide code and sends the mapped Payaza code to account inquiry', function () {
    Cache::flush();

    $provider = PaymentProvider::create([
        'key' => 'payaza',
        'name' => 'Payaza',
        'is_enabled' => true,
        'priority' => 1,
    ]);

    PaymentProviderCurrency::create([
        'payment_provider_id' => $provider->id,
        'currency' => 'NGN',
        'transfer_method' => null,
        'is_enabled' => true,
        'collection_enabled' => false,
    ]);

    Bank::create([
        'name' => 'Test Bank',
        'country_iso' => 'NG',
        'currency' => 'NGN',
        'bank_code' => '057',
        'flovide_code' => '003',
        'provider' => 'payaza',
        'type' => 'bank',
    ]);

    $payaza = Mockery::mock(PayazaService::class);
    $payaza->shouldReceive('accountEnquiry')
        ->once()
        ->with('NGN', '057', '0000000000')
        ->andReturn([
            'success' => true,
            'data' => [
                'response_content' => ['account_name' => 'Test Account'],
            ],
        ]);

    $controller = new AccountInquiryController(
        Mockery::mock(PivotService::class),
        $payaza,
        Mockery::mock(OrchardService::class),
        Mockery::mock(OhentPayService::class),
        app(ProviderRoutingService::class),
    );

    $providerCodeResponse = $controller->unifiedAccountInquiry(Request::create('/api/account-inquiry', 'POST', [
        'currency' => 'NGN',
        'bank_code' => '057',
        'account_number' => '0000000000',
        'mode' => 'live',
    ]));

    expect($providerCodeResponse->getStatusCode())->toBe(422);

    $flovideCodeResponse = $controller->unifiedAccountInquiry(Request::create('/api/account-inquiry', 'POST', [
        'currency' => 'NGN',
        'bank_code' => '003',
        'account_number' => '0000000000',
        'mode' => 'live',
    ]));

    expect($flovideCodeResponse->getStatusCode())->toBe(200)
        ->and($flovideCodeResponse->getData(true)['data']['account_name'])->toBe('Test Account');
});
