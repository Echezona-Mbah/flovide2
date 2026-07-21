<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        Currency::insert([
            // Global
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'country_code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'country_code' => 'EU',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'country_code' => 'GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Africa
            [
                'code' => 'NGN',
                'name' => 'Nigerian Naira',
                'symbol' => '₦',
                'country_code' => 'NG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GHS',
                'name' => 'Ghana Cedi',
                'symbol' => '₵',
                'country_code' => 'GH',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'KES',
                'name' => 'Kenyan Shilling',
                'symbol' => 'KSh',
                'country_code' => 'KE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ZAR',
                'name' => 'South African Rand',
                'symbol' => 'R',
                'country_code' => 'ZA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'UGX',
                'name' => 'Ugandan Shilling',
                'symbol' => 'USh',
                'country_code' => 'UG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'XOF',
                'name' => 'West African CFA Franc',
                'symbol' => 'CFA',
                'country_code' => 'SN', // Senegal (common usage)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EGP',
                'name' => 'Egyptian Pound',
                'symbol' => '£',
                'country_code' => 'EG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
