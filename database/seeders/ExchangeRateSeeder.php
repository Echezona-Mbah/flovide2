<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run()
    {
        $data = [
            ['currency_code' => 'NGN', 'country_name' => 'Nigeria', 'rate' => 1550, 'transfer_fee' => 100],
            ['currency_code' => 'USD', 'country_name' => 'United States', 'rate' => 1, 'transfer_fee' => 1],
            ['currency_code' => 'KES', 'country_name' => 'Kenya', 'rate' => 130, 'transfer_fee' => 20],
            ['currency_code' => 'GHS', 'country_name' => 'Ghana', 'rate' => 15, 'transfer_fee' => 5],
            ['currency_code' => 'ZAR', 'country_name' => 'South Africa', 'rate' => 18, 'transfer_fee' => 10],
            ['currency_code' => 'GBP', 'country_name' => 'United Kingdom', 'rate' => 0.77, 'transfer_fee' => 0.5],
            ['currency_code' => 'EUR', 'country_name' => 'Eurozone', 'rate' => 0.9, 'transfer_fee' => 0.6],
            ['currency_code' => 'CAD', 'country_name' => 'Canada', 'rate' => 1.3, 'transfer_fee' => 1.2],
            ['currency_code' => 'AUD', 'country_name' => 'Australia', 'rate' => 1.5, 'transfer_fee' => 1.3],
            ['currency_code' => 'JPY', 'country_name' => 'Japan', 'rate' => 140, 'transfer_fee' => 120],
            ['currency_code' => 'CNY', 'country_name' => 'China', 'rate' => 7.2, 'transfer_fee' => 6],
            ['currency_code' => 'INR', 'country_name' => 'India', 'rate' => 83, 'transfer_fee' => 70],
            ['currency_code' => 'BRL', 'country_name' => 'Brazil', 'rate' => 5.2, 'transfer_fee' => 4],
            ['currency_code' => 'MXN', 'country_name' => 'Mexico', 'rate' => 17, 'transfer_fee' => 15],
            ['currency_code' => 'AED', 'country_name' => 'United Arab Emirates', 'rate' => 3.67, 'transfer_fee' => 3],
            ['currency_code' => 'SAR', 'country_name' => 'Saudi Arabia', 'rate' => 3.75, 'transfer_fee' => 3],
            ['currency_code' => 'TRY', 'country_name' => 'Turkey', 'rate' => 32, 'transfer_fee' => 25],
            ['currency_code' => 'RUB', 'country_name' => 'Russia', 'rate' => 91, 'transfer_fee' => 80],
            ['currency_code' => 'CHF', 'country_name' => 'Switzerland', 'rate' => 0.88, 'transfer_fee' => 0.7],
            ['currency_code' => 'SEK', 'country_name' => 'Sweden', 'rate' => 10.8, 'transfer_fee' => 9],
            ['currency_code' => 'NOK', 'country_name' => 'Norway', 'rate' => 10.6, 'transfer_fee' => 9],
            ['currency_code' => 'DKK', 'country_name' => 'Denmark', 'rate' => 6.7, 'transfer_fee' => 5],
            ['currency_code' => 'PLN', 'country_name' => 'Poland', 'rate' => 4.1, 'transfer_fee' => 3],
            ['currency_code' => 'THB', 'country_name' => 'Thailand', 'rate' => 35, 'transfer_fee' => 30],
            ['currency_code' => 'MYR', 'country_name' => 'Malaysia', 'rate' => 4.7, 'transfer_fee' => 4],
            ['currency_code' => 'IDR', 'country_name' => 'Indonesia', 'rate' => 15500, 'transfer_fee' => 14000],
            ['currency_code' => 'PHP', 'country_name' => 'Philippines', 'rate' => 58, 'transfer_fee' => 50],
            ['currency_code' => 'PKR', 'country_name' => 'Pakistan', 'rate' => 280, 'transfer_fee' => 250],
            ['currency_code' => 'BDT', 'country_name' => 'Bangladesh', 'rate' => 117, 'transfer_fee' => 100],
            ['currency_code' => 'EGP', 'country_name' => 'Egypt', 'rate' => 47, 'transfer_fee' => 40],
            ['currency_code' => 'TWD', 'country_name' => 'Taiwan', 'rate' => 32, 'transfer_fee' => 30],
            ['currency_code' => 'HKD', 'country_name' => 'Hong Kong', 'rate' => 7.8, 'transfer_fee' => 6],
            ['currency_code' => 'SGD', 'country_name' => 'Singapore', 'rate' => 1.35, 'transfer_fee' => 1.1],
            ['currency_code' => 'NZD', 'country_name' => 'New Zealand', 'rate' => 1.65, 'transfer_fee' => 1.4],
        ];


        foreach ($data as $item) {
            ExchangeRate::updateOrCreate(
                ['currency_code' => $item['currency_code']],
                $item
            );
        }
    }
}
