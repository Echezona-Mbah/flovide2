<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'key' => 'pivot',
                'name' => 'Pivot',
                'is_enabled' => true,
                'priority' => 10,
                'currencies' => [
                    ['currency' => 'UGX', 'transfer_method' => null],
                    ['currency' => 'KES', 'transfer_method' => null],
                ],
            ],
            [
                'key' => 'payaza',
                'name' => 'Payaza',
                'is_enabled' => true,
                'priority' => 20,
                'currencies' => [
                    ['currency' => 'NGN', 'transfer_method' => null],
                    ['currency' => 'TZS', 'transfer_method' => null],
                    ['currency' => 'XOF', 'transfer_method' => null],
                    ['currency' => 'XAF', 'transfer_method' => null],
                    ['currency' => 'ZAR', 'transfer_method' => null],
                    ['currency' => 'KES', 'transfer_method' => null],
                    ['currency' => 'UGX', 'transfer_method' => 'mobile'],
                    ['currency' => 'GHS', 'transfer_method' => null],
                ],
            ],
            [
                'key' => 'app_mobile',
                'name' => 'AppMobile (Orchard)',
                'is_enabled' => true,
                'priority' => 15,
                'currencies' => [
                    ['currency' => 'GHS', 'transfer_method' => null],
                ],
            ],
            [
                'key' => 'ohentpay',
                'name' => 'OhentPay',
                'is_enabled' => true,
                'priority' => 5, // highest priority — tried first when it supports the currency
                'currencies' => [
                    ['currency' => 'KES', 'transfer_method' => null],
                ],
            ],
            [
                'key' => 'blaaiz_interac',
                'name' => 'Blaaiz Interac',
                'is_enabled' => true,
                'priority' => 30,
                'currencies' => [
                    ['currency' => 'CAD', 'transfer_method' => null],
                ],
            ],
        ];

        foreach ($providers as $data) {
            $currencies = $data['currencies'];
            unset($data['currencies']);

            $provider = PaymentProvider::updateOrCreate(['key' => $data['key']], $data);

            foreach ($currencies as $c) {
                $provider->currencies()->updateOrCreate(
                    ['currency' => $c['currency'], 'transfer_method' => $c['transfer_method']],
                    ['is_enabled' => true]
                );
            }
        }
    }
}