<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;
use App\Models\Currency;

class ExchangeRateSeeder extends Seeder
{
    public function run()
    {
        $currencies = Currency::all();

        if ($currencies->count() < 2) {
            throw new \Exception("Not enough currencies found");
        }

        foreach ($currencies as $fromCurrency) {
            foreach ($currencies as $toCurrency) {

                // ❌ skip same currency
                if ($fromCurrency->id === $toCurrency->id) {
                    continue;
                }

                // ✅ manual rate (you can customize this later)
                $rate = 100;

                ExchangeRate::updateOrCreate(
                    [
                        'from_currency_id' => $fromCurrency->id,
                        'to_currency_id' => $toCurrency->id,
                    ],
                    [
                        'rate' => $rate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}