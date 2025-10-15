<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Remita;
use Faker\Factory as Faker;


class RemitaPaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now();
        
        //Fetch remita IDs and currency together
        $remitas = Remita::select('id', 'currency')->get();

        // Handle empty remita table
        if ($remitas->isEmpty()) {
            $this->command->warn('No remita records found. Seed the remita table first.');
            return;
        }
        
        //Pick random remita record
        $randomRemita1 = $remitas->random();
        $randomRemita2 = $remitas->random();

        $faker = Faker::create();

        DB::table('remita_payments')->insert([
            [
                'remita_id' => $randomRemita1->id,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'number' => $faker->phoneNumber,
                'transaction_reference' => Str::uuid(),
                'amount_paid' => 2500.00,
                'currency' => $randomRemita1->currency,
                'channel' => 'Web',
                'status' => 'successful',
                'response_code' => '00',
                'response_message' => 'Payment successful',
                'paid_at' => $now,
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            [
                'remita_id' => $randomRemita2->id,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'number' => $faker->phoneNumber,
                'transaction_reference' => Str::uuid(),
                'amount_paid' => 10000.00,
                'currency' => $randomRemita2->currency,
                'channel' => 'Mobile',
                'status' => 'failed',
                'response_code' => '99',
                'response_message' => 'Insufficient funds',
                'paid_at' => null,
                'deleted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
