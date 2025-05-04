<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Australia', 'code' => 'AU', 'currency' => 'Australian Dollar', 'currency_code' => 'AUD'],
            ['name' => 'Brazil', 'code' => 'BR', 'currency' => 'Real', 'currency_code' => 'BRL'],
            ['name' => 'Canada', 'code' => 'CA', 'currency' => 'Canadian Dollar', 'currency_code' => 'CAD'],
            ['name' => 'China', 'code' => 'CN', 'currency' => 'Renminbi (Yuan)', 'currency_code' => 'CNY'],
            ['name' => 'Egypt', 'code' => 'EG', 'currency' => 'Egyptian Pound', 'currency_code' => 'EGP'],
            ['name' => 'France', 'code' => 'FR', 'currency' => 'Euro', 'currency_code' => 'EUR'],
            ['name' => 'Germany', 'code' => 'DE', 'currency' => 'Euro', 'currency_code' => 'EUR'],
            ['name' => 'Ghana', 'code' => 'GH', 'currency' => 'Ghanaian Cedi', 'currency_code' => 'GHS'],
            ['name' => 'India', 'code' => 'IN', 'currency' => 'Indian Rupee', 'currency_code' => 'INR'],
            ['name' => 'Japan', 'code' => 'JP', 'currency' => 'Yen', 'currency_code' => 'JPY'],
            ['name' => 'Kenya', 'code' => 'KE', 'currency' => 'Kenyan Shilling', 'currency_code' => 'KES'],
            ['name' => 'Mexico', 'code' => 'MX', 'currency' => 'Peso', 'currency_code' => 'MXN'],
            ['name' => 'Nigeria', 'code' => 'NG', 'currency' => 'Naira', 'currency_code' => 'NGN'],
            ['name' => 'Russia', 'code' => 'RU', 'currency' => 'Ruble', 'currency_code' => 'RUB'],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'currency' => 'Riyal', 'currency_code' => 'SAR'],
            ['name' => 'South Africa', 'code' => 'ZA', 'currency' => 'Rand', 'currency_code' => 'ZAR'],
            ['name' => 'South Korea', 'code' => 'KR', 'currency' => 'Won', 'currency_code' => 'KRW'],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'currency' => 'Dirham', 'currency_code' => 'AED'],
            ['name' => 'United Kingdom', 'code' => 'GB', 'currency' => 'Pound Sterling', 'currency_code' => 'GBP'],
            ['name' => 'United States', 'code' => 'US', 'currency' => 'Dollar', 'currency_code' => 'USD'],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name' => $country['name'],
                'code' => $country['code'],
                'currency' => $country['currency'],
                'currency_code' => $country['currency_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
