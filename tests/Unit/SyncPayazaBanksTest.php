<?php

use App\Models\Bank;
use App\Services\PayazaService;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

beforeEach(function () {
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
        $table->string('provider')->default('pivot');
        $table->string('type')->default('bank');
        $table->string('flovide_code')->nullable();
        $table->timestamps();
    });
});

it('syncs Payaza UGX banks and mobile money without deleting or replacing existing codes', function () {
    Bank::create([
        'name' => 'Existing Payaza Bank',
        'country_iso' => 'UG',
        'currency' => 'UGX',
        'bank_code' => 'FTBUGA',
        'provider' => 'payaza',
        'type' => 'bank',
        'bank_nibss_code' => 'KEEP-NIBSS-CODE',
        'sort_code' => 'KEEP-SORT-CODE',
        'flovide_code' => '101',
    ]);

    Bank::create([
        'name' => 'Unrelated NGN Bank',
        'country_iso' => 'NG',
        'currency' => 'NGN',
        'bank_code' => '044',
        'provider' => 'pivot',
        'type' => 'bank',
    ]);

    $payaza = Mockery::mock(PayazaService::class);
    $payaza->shouldReceive('getBanks')
        ->once()
        ->with('UGX')
        ->andReturn([
            'success' => true,
            'data' => [
                'data' => [
                    ['id' => 3582, 'name' => 'Existing Payaza Bank Updated', 'code' => 'FTBUGA', 'type' => 'sabs', 'currency_code' => 'UGX', 'country_code' => 'UGA'],
                    ['id' => 1430, 'name' => 'MTN Uganda', 'code' => 'MTNUGA', 'type' => 'mobile_money', 'currency_code' => 'UGX', 'country_code' => 'UGA'],
                ],
            ],
        ]);
    $this->app->instance(PayazaService::class, $payaza);

    $this->artisan('payaza:sync-banks UGX')->assertSuccessful();

    expect(Bank::count())->toBe(3)
        ->and(Bank::where('currency', 'UGX')->where('provider', 'payaza')->count())->toBe(2)
        ->and(Bank::where('bank_code', 'FTBUGA')->value('name'))->toBe('Existing Payaza Bank Updated')
        ->and(Bank::where('bank_code', 'FTBUGA')->value('flovide_code'))->toBe('101')
        ->and(Bank::where('bank_code', 'FTBUGA')->value('bank_nibss_code'))->toBe('KEEP-NIBSS-CODE')
        ->and(Bank::where('bank_code', 'FTBUGA')->value('sort_code'))->toBe('KEEP-SORT-CODE')
        ->and(Bank::where('bank_code', 'MTNUGA')->value('type'))->toBe('mobile_money')
        ->and(Bank::where('bank_code', 'MTNUGA')->value('country_iso'))->toBe('UG')
        ->and(Bank::where('bank_code', '044')->exists())->toBeTrue();
});

it('does not modify the bank table when Payaza returns no usable list', function () {
    Bank::create([
        'name' => 'Keep Existing Bank',
        'country_iso' => 'NG',
        'currency' => 'NGN',
        'bank_code' => '044',
        'provider' => 'pivot',
        'type' => 'bank',
    ]);

    $payaza = Mockery::mock(PayazaService::class);
    $payaza->shouldReceive('getBanks')
        ->once()
        ->with('UGX')
        ->andReturn(['success' => true, 'data' => ['data' => []]]);
    $this->app->instance(PayazaService::class, $payaza);

    $this->artisan('payaza:sync-banks UGX')->assertFailed();

    expect(Bank::count())->toBe(1)
        ->and(Bank::where('bank_code', '044')->exists())->toBeTrue();
});
