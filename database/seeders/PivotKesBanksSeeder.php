<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class PivotKesBanksSeeder extends Seeder
{
    public function run(): void
    {
        // [bank code, bank name] — pairs (not array keys) so codes like "01" keep their leading zero
        $banks = [
            ['01', 'KCB'],
            ['02', 'Standard Chartered'],
            ['03', 'Absa Bank Kenya'],
            ['05', 'Bank of India'],
            ['06', 'Bank of Baroda'],
            ['07', 'NCBA'],
            ['10', 'Prime Bank'],
            ['11', 'Co-operative Bank'],
            ['12', 'National Bank'],
            ['16', 'Citibank'],
            ['18', 'Middle East Bank'],
            ['19', 'Bank of Africa'],
            ['23', 'Consolidated Bank'],
            ['25', 'Credit Bank'],
            ['26', 'Access Bank'],
            ['31', 'Stanbic Bank'],
            ['35', 'African Banking Corp'],
            ['43', 'Ecobank'],
            ['49', 'Spire Bank'],
            ['50', 'Paramount Bank'],
            ['51', 'Kingdom Bank'],
            ['53', 'Guaranty Trust Bank'],
            ['54', 'Victoria Commercial Bank'],
            ['55', 'Guardian Bank'],
            ['57', 'I&M Bank'],
            ['59', 'Development Bank of Kenya'],
            ['60', 'SBM Bank'],
            ['61', 'HFC'],
            ['63', 'DTB'],
            ['65', 'Mayfair CIB'],
            ['66', 'Sidian Bank'],
            ['68', 'Equity Bank'],
            ['70', 'Family Bank'],
            ['72', 'Gulf African Bank'],
            ['74', 'First Community Bank'],
            ['75', 'DIB Bank'],
            ['76', 'UBA Kenya'],
        ];

        $count = 0;

        foreach ($banks as [$code, $name]) {
            Bank::updateOrCreate(
                [
                    'provider'    => 'pivot',
                    'country_iso' => 'KE',
                    'currency'    => 'KES',
                    'bank_code'   => $code,
                ],
                [
                    'name'      => $name,
                    'sort_code' => $code, // your Pivot UGX flow reads sort_code first, then bank_code
                    'type'      => 'bank',
                ]
            );
            $count++;
        }

        $this->command?->info("Seeded {$count} Pivot KES banks.");
    }
}