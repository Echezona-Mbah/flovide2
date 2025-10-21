<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DonationRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $userIds = DB::table('users')->pluck('id')->toArray();
        $faker = Faker::create();
        $statuses = ['pending', 'completed', 'failed'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('donation_records')->insert([
                'user_id' => $userIds[array_rand($userIds)],
                'personal_id' => null,
                'donation_id' => 11, // related donation IDs
                'amount' => rand(1000, 10000),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'currency' => "NGN",
                'status' => "pending",
                'reference' => 'Flovide-' . strtoupper(Str::random(10)),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
