<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            // 🇳🇬 Nigeria
            ['name' => 'Access Bank', 'country_code' => 'NG'],
            ['name' => 'GTBank', 'country_code' => 'NG'],
            ['name' => 'First Bank', 'country_code' => 'NG'],
            ['name' => 'UBA', 'country_code' => 'NG'],
            ['name' => 'Zenith Bank', 'country_code' => 'NG'],

            // 🇺🇸 United States
            ['name' => 'Bank of America', 'country_code' => 'US'],
            ['name' => 'Wells Fargo', 'country_code' => 'US'],
            ['name' => 'JPMorgan Chase', 'country_code' => 'US'],
            ['name' => 'Citibank', 'country_code' => 'US'],
            ['name' => 'Capital One', 'country_code' => 'US'],

            // 🇬🇧 United Kingdom
            ['name' => 'Barclays', 'country_code' => 'GB'],
            ['name' => 'HSBC', 'country_code' => 'GB'],
            ['name' => 'Lloyds Bank', 'country_code' => 'GB'],
            ['name' => 'NatWest', 'country_code' => 'GB'],
            ['name' => 'Monzo', 'country_code' => 'GB'],

            // 🇨🇦 Canada
            ['name' => 'Royal Bank of Canada', 'country_code' => 'CA'],
            ['name' => 'TD Canada Trust', 'country_code' => 'CA'],
            ['name' => 'Scotiabank', 'country_code' => 'CA'],
            ['name' => 'BMO Bank of Montreal', 'country_code' => 'CA'],
            ['name' => 'CIBC', 'country_code' => 'CA'],

            // 🇮🇳 India
            ['name' => 'State Bank of India', 'country_code' => 'IN'],
            ['name' => 'HDFC Bank', 'country_code' => 'IN'],
            ['name' => 'ICICI Bank', 'country_code' => 'IN'],
            ['name' => 'Axis Bank', 'country_code' => 'IN'],
            ['name' => 'Punjab National Bank', 'country_code' => 'IN'],

            // 🇦🇺 Australia
            ['name' => 'Commonwealth Bank', 'country_code' => 'AU'],
            ['name' => 'Westpac', 'country_code' => 'AU'],
            ['name' => 'ANZ', 'country_code' => 'AU'],
            ['name' => 'National Australia Bank', 'country_code' => 'AU'],

            // 🇿🇦 South Africa
            ['name' => 'Standard Bank', 'country_code' => 'ZA'],
            ['name' => 'First National Bank', 'country_code' => 'ZA'],
            ['name' => 'ABSA', 'country_code' => 'ZA'],
            ['name' => 'Nedbank', 'country_code' => 'ZA'],

            // 🇰🇪 Kenya
            ['name' => 'Equity Bank', 'country_code' => 'KE'],
            ['name' => 'KCB Bank', 'country_code' => 'KE'],
            ['name' => 'Co-operative Bank', 'country_code' => 'KE'],
            ['name' => 'NCBA Bank', 'country_code' => 'KE'],

            // 🇬🇭 Ghana
            ['name' => 'GCB Bank', 'country_code' => 'GH'],
            ['name' => 'Ecobank Ghana', 'country_code' => 'GH'],
            ['name' => 'Stanbic Bank Ghana', 'country_code' => 'GH'],
            ['name' => 'Absa Bank Ghana', 'country_code' => 'GH'],

            // 🇦🇪 UAE
            ['name' => 'Emirates NBD', 'country_code' => 'AE'],
            ['name' => 'Abu Dhabi Commercial Bank', 'country_code' => 'AE'],
            ['name' => 'First Abu Dhabi Bank', 'country_code' => 'AE'],
            ['name' => 'Mashreq Bank', 'country_code' => 'AE'],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
