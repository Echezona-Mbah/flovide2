<?php

use App\Http\Controllers\Business\AddBeneficiariesController;
use App\Models\Bank;
use Illuminate\Http\Request;
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
        $table->string('flovide_code')->nullable();
        $table->string('provider')->nullable();
        $table->string('type')->nullable();
        $table->timestamps();
    });
});

it('returns OhentPay KES banks using their Flovide codes', function () {
    Bank::create([
        'name' => 'Kenya Test Bank',
        'country_iso' => 'KE',
        'currency' => 'KES',
        'bank_code' => 'ohentpay-bank-12',
        'flovide_code' => '512',
        'provider' => 'ohentpay',
        'type' => 'bank',
    ]);

    Bank::create([
        'name' => 'Other Provider Bank',
        'country_iso' => 'KE',
        'currency' => 'KES',
        'bank_code' => 'other-bank-12',
        'flovide_code' => '512',
        'provider' => 'payaza',
        'type' => 'bank',
    ]);

    $response = app(AddBeneficiariesController::class)->banks(Request::create('/banks/filter', 'GET', [
        'country' => 'KE',
        'currency' => 'KES',
        'provider' => 'ohentpay',
    ]));

    expect($response->getStatusCode())->toBe(200)
        ->and($response->getData(true))->toBe([
            [
                'id' => 1,
                'name' => 'Kenya Test Bank',
                'bank_code' => '512',
                'type' => 'bank',
                'country_iso' => 'KE',
                'provider' => 'ohentpay',
            ],
        ]);
});
