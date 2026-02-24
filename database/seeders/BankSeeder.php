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
    public function run()
{
    $banks = [

        // UG
            ['UG','Absa Bank Uganda Ltd','013847','013847'],
            ['UG','Bank of Baroda','020147','020147'],
            ['UG','Stanbic Bank Ltd','040147','040147'],
            ['UG','DFCU Bank','050147','050147'],
            ['UG','Tropical Bank','060147','060147'],
            ['UG','Stanchart Bank','080147','080147'],
            ['UG','I & M Bank Uganda Ltd','110147','110147'],
            ['UG','Bank Of Africa','130447','130447'],
            ['UG','Centenary Bank','163747','163747'],
            ['UG','Cairo Bank Uganda','180147','180147'],
            ['UG','Diamond Trust Bank','190147','190147'],
            ['UG','Housing Finance Bank','230147','230147'],
            ['UG','Kenya Commercial Bank','252947','252947'],
            ['UG','United Bank for Africal','260147','260147'],
            ['UG','Guaranty Trust Bank','270147','270147'],
            ['UG','Ecobank','290147','290147'],
            ['UG','Equity Bank Uganda','300147','300147'],
            ['UG','ABC Bank','310147','310147'],
            ['UG','EXIM Bank','320147','320147'],
            ['UG','NCBA Bank','360147','360147'],
            ['UG','Finance Trust Bank','410147','410147'],
            ['UG','Uganda Development Bank','420147','420147'],
            ['UG','Post Bank Uganda','560147','560147'],
            ['UG','Bank of Indian','600147','600147'],
            ['UG','Opportunity Bank','610147','610147'],
            ['UG','UGAFODE MFI','730147','730147'],


        // NG
        ['NG','Access Bank','044',null],
        ['NG','GTBank','058',null],
        ['NG','Zenith Bank','057',null],
    ];
    foreach ($banks as $bank) {
        Bank::create([
            'name'=>$bank[1],
            'country_iso'=>$bank[0],
            'bank_code'=>$bank[2],
            'sort_code'=>$bank[3]
        ]);
    }

}
}
