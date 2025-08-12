<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DstvPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dstv_plans')->insert([
            ['code'=>'dstv-padi','name'=>'DStv Padi','amount'=>1850,'description'=>'Padi plan','service_id' =>'dstv'],
            ['code'=>'dstv-yanga','name'=>'DStv Yanga','amount'=>2565,'description'=>'Yanga plan','service_id' =>'dstv'],
            ['code'=>'dstv-confam','name'=>'DStv Confam','amount'=>4615,'description'=>'Confam plan','service_id' =>'dstv'],
            ['code'=>'dstv79','name'=>'DStv Compact','amount'=>7900,'description'=>'Compact plan','service_id' =>'dstv'],
            ['code'=>'dstv3','name'=>'DStv Premium','amount'=>18400,'description'=>'Premium plan','service_id' =>'dstv'],
            ['code'=>'dstv6','name'=>'DStv Asia','amount'=>6200,'description'=>'Asia add-on','service_id' =>'dstv'],
            ['code'=>'dstv7','name'=>'DStv Compact Plus','amount'=>12400,'description'=>'Compact Plus plan','service_id' =>'dstv'],
            ['code'=>'dstv9','name'=>'DStv Premium‑French','amount'=>25550,'description'=>'French variant','service_id' =>'dstv'],
            ['code'=>'dstv10','name'=>'DStv Premium‑Asia','amount'=>20500,'description'=>'Asia variant','service_id' =>'dstv'],
            ['code'=>'confam-extra','name'=>'Confam + ExtraView','amount'=>7115,'description'=>'Confam + ExtraView','service_id' =>'dstv'],
            ['code'=>'yanga-extra','name'=>'Yanga + ExtraView','amount'=>5065,'description'=>'Yanga + ExtraView','service_id' =>'dstv'],
            ['code'=>'padi-extra','name'=>'Padi + ExtraView','amount'=>4350,'description'=>'Padi + ExtraView','service_id' =>'dstv'],
            ['code'=>'com-asia','name'=>'Compact + Asia','amount'=>14100,'description'=>'Compact + Asia package','service_id' =>'dstv'],
            ['code'=>'dstv30','name'=>'Compact + Extra View','amount'=>10400,'description'=>'Compact ExtraView','service_id' =>'dstv'],
            ['code'=>'com-frenchtouch','name'=>'Compact + French Touch','amount'=>10200,'description'=>'Compact + French Touch','service_id' =>'dstv'],
            ['code'=>'dstv33','name'=>'Premium – Extra View','amount'=>20900,'description'=>'Premium + ExtraView','service_id' =>'dstv'],
            ['code'=>'dstv40','name'=>'Compact Plus – Asia','amount'=>18600,'description'=>'Compact Plus Asia','service_id' =>'dstv'],
            ['code'=>'com-frenchtouch-extra','name'=>'Compact + French Touch + ExtraView','amount'=>12700,'description'=>'Combined bundle','service_id' =>'dstv'],
            ['code'=>'com-asia-extra','name'=>'Compact + Asia + ExtraView','amount'=>16600,'description'=>'Combined bundle','service_id' =>'dstv'],
            ['code'=>'dstv43','name'=>'Compact Plus + French Plus','amount'=>20500,'description'=>'Compact Plus French plus','service_id' =>'dstv'],
            ['code'=>'complus-frenchtouch','name'=>'Compact Plus + French Touch','amount'=>14700,'description'=>'Compact Plus French Touch','service_id' =>'dstv'],
            ['code'=>'dstv45','name'=>'Compact Plus – Extra View','amount'=>14900,'description'=>'Compact Plus Extra View','service_id' =>'dstv'],
            ['code'=>'complus-french-extraview','name'=>'Compact Plus + FrenchPlus + Extra View','amount'=>23000,'description'=>'Compact Plus big bundle','service_id' =>'dstv'],
            ['code'=>'dstv47','name'=>'Compact + French Plus','amount'=>16000,'description'=>'Compact French plus','service_id' =>'dstv'],
            ['code'=>'dstv48','name'=>'Compact Plus + Asia + ExtraView','amount'=>21100,'description'=>'Compact Plus Asia bundle','service_id' =>'dstv'],
            ['code'=>'dstv61','name'=>'Premium + Asia + Extra View','amount'=>23000,'description'=>'Premium Asia ExtraView','service_id' =>'dstv'],
            ['code'=>'dstv62','name'=>'Premium + French + Extra View','amount'=>28000,'description'=>'Premium French ExtraView','service_id' =>'dstv'],
            ['code'=>'hdpvr-access-service','name'=>'DStv HDPVR Access Service','amount'=>2500,'description'=>'HDPVR Subscription','service_id' =>'dstv'],
            ['code'=>'frenchplus-addon','name'=>'DStv French Plus Add‑on','amount'=>8100,'description'=>'French plus add-on','service_id' =>'dstv'],
            ['code'=>'asia-addon','name'=>'DStv Asian Add‑on','amount'=>6200,'description'=>'Asian add-on','service_id' =>'dstv'],
            ['code'=>'frenchtouch-addon','name'=>'DStv French Touch Add‑on','amount'=>2300,'description'=>'French Touch add‑on','service_id' =>'dstv'],
            ['code'=>'extraview-access','name'=>'ExtraView Access','amount'=>2500,'description'=>'ExtraView stand-alone','service_id' =>'dstv'],
            ['code'=>'french11','name'=>'DStv French 11','amount'=>3260,'description'=>'French 11 bouquet','service_id' =>'dstv'],
        ]);
    }
}
