<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;


class PaymentRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $personalIds = DB::table('personals')->pluck('id')->toArray();
        $faker = Faker::create();
        $statuses = ['pending', 'completed', 'failed'];

        for ($i = 1; $i <= 20; $i++) {
            DB::table('payment_records')->insert([
                'user_id' => null,
                'personal_id' => $personalIds[array_rand($personalIds)],
                'payment_id' => rand(1, 5), // related payment IDs
                'amount' => rand(1000, 10000),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'currency' => "NGN",
                'status' => $statuses[array_rand($statuses)],
                'reference' => 'TRX-' . strtoupper(Str::random(10)),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
