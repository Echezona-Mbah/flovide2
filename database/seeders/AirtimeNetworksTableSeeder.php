<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirtimeNetworksTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('airtime_networks')->insert([
            [
                'name' => 'MTN',
                'service_id' => 'mtn-data',
                'logo' => 'mtn.png',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Airtel',
                'service_id' => 'airtel-data',
                'logo' => 'airtel.png',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GLO',
                'service_id' => 'glo-data',
                'logo' => 'glo.png',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '9Mobile',
                'service_id' => 'etisalat-data',
                'logo' => '9mobile.png',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
