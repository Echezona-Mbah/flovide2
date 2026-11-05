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
