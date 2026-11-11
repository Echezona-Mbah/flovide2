<?php

use App\Models\Bank;
use App\Models\MainBank;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

beforeEach(function () {
    Schema::dropIfExists('mainbanks');
    Schema::dropIfExists('banks');

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

    Schema::create('mainbanks', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('country_iso', 10)->default('');
        $table->string('currency', 3);
        $table->string('type', 30)->default('bank');
        $table->string('normalized_name', 190);
        $table->string('flovide_code', 40);
        $table->timestamps();
        $table->unique(['currency', 'flovide_code']);
    });
});

it('syncs provider banks into one main bank record per bank and currency', function () {
    Bank::create([
        'name' => 'Example Bank Plc',
        'country_iso' => 'NG',
        'currency' => 'NGN',
        'bank_code' => '001',
        'provider' => 'provider_a',
        'type' => 'bank',
    ]);

    Bank::create([
        'name' => 'Example Bank Limited',
        'country_iso' => 'NG',
        'currency' => 'NGN',
        'bank_code' => 'bank-001',
        'provider' => 'provider_b',
        'type' => 'bank',
    ]);

    Bank::create([
        'name' => 'Example Bank',
        'country_iso' => 'CA',
        'currency' => 'CAD',
        'bank_code' => 'ca-001',
        'provider' => 'provider_c',
        'type' => 'bank',
    ]);

    $this->artisan('banks:sync-mainbanks')->assertSuccessful();

    expect(MainBank::count())->toBe(2)
        ->and(Bank::where('currency', 'NGN')->pluck('flovide_code')->unique()->all())->toHaveCount(1)
        ->and(MainBank::where('currency', 'NGN')->value('flovide_code'))->toBe(
            Bank::where('currency', 'NGN')->value('flovide_code')
        )
        ->and(MainBank::where('currency', 'CAD')->exists())->toBeTrue();

    $this->artisan('banks:sync-mainbanks')->assertSuccessful();

    expect(MainBank::count())->toBe(2);
});

it('can limit main bank code generation to a single currency', function () {
    Bank::create([
        'name' => 'Uganda Test Bank',
        'country_iso' => 'UG',
        'currency' => 'UGX',
        'bank_code' => 'UGX001',
        'provider' => 'payaza',
        'type' => 'bank',
    ]);

    Bank::create([
        'name' => 'Nigeria Test Bank',
        'country_iso' => 'NG',
        'currency' => 'NGN',
        'bank_code' => 'NGN001',
        'provider' => 'payaza',
        'type' => 'bank',
    ]);

    $this->artisan('banks:sync-mainbanks --currency=UGX')->assertSuccessful();

    expect(MainBank::where('currency', 'UGX')->count())->toBe(1)
        ->and(MainBank::where('currency', 'NGN')->count())->toBe(0)
        ->and(Bank::where('currency', 'UGX')->whereNull('flovide_code')->exists())->toBeFalse()
        ->and(Bank::where('currency', 'NGN')->whereNull('flovide_code')->exists())->toBeTrue();
});

it('can limit main bank code generation to banks supplied by one provider', function () {
    Bank::create([
        'name' => 'Payaza Uganda Bank',
        'country_iso' => 'UG',
        'currency' => 'UGX',
        'bank_code' => 'PAYAZA001',
        'provider' => 'payaza',
        'type' => 'bank',
    ]);

    Bank::create([
        'name' => 'Other Uganda Bank',
        'country_iso' => 'UG',
        'currency' => 'UGX',
        'bank_code' => 'OTHER001',
        'provider' => 'other',
        'type' => 'bank',
    ]);

    $this->artisan('banks:sync-mainbanks --currency=UGX --provider=payaza')->assertSuccessful();

    expect(MainBank::count())->toBe(1)
        ->and(MainBank::where('normalized_name', 'payazaugandabank')->exists())->toBeTrue()
        ->and(Bank::where('provider', 'payaza')->whereNull('flovide_code')->exists())->toBeFalse()
        ->and(Bank::where('provider', 'other')->whereNull('flovide_code')->exists())->toBeTrue();
});

it('reuses an existing shared code for a provider-scoped name variant', function () {
    Bank::create([
        'name' => 'Consolidated Bank Kenya',
        'country_iso' => 'KE',
        'currency' => 'KES',
        'bank_code' => 'ohentpay-code',
        'provider' => 'ohentpay',
        'type' => 'bank',
    ]);

    Bank::create([
        'name' => 'Consolidated Bank Kenya',
        'country_iso' => 'KE',
        'currency' => 'KES',
        'bank_code' => 'payaza-code',
        'provider' => 'payaza',
        'type' => 'bank',
        'flovide_code' => '537',
    ]);

    Bank::create([
        'name' => 'Consolidated Bank',
        'country_iso' => 'KE',
        'currency' => 'KES',
        'bank_code' => 'pivot-code',
        'provider' => 'pivot',
        'type' => 'bank',
        'flovide_code' => '537',
    ]);

    $this->artisan('banks:sync-mainbanks --currency=KES --provider=ohentpay')->assertSuccessful();

    expect(MainBank::where('currency', 'KES')->where('flovide_code', '537')->count())->toBe(1)
        ->and(MainBank::where('currency', 'KES')->where('normalized_name', 'consolidatedbankkenya')->value('flovide_code'))->toBe('537')
        ->and(Bank::where('provider', 'ohentpay')->where('currency', 'KES')->value('flovide_code'))->toBe('537')
        ->and(Bank::where('provider', 'payaza')->where('currency', 'KES')->value('flovide_code'))->toBe('537')
        ->and(Bank::where('provider', 'pivot')->where('currency', 'KES')->value('flovide_code'))->toBe('537');
});
