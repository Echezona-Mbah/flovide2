<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionRecord;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscription;

class SubscriptionRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->toArray();
        $subscriptionIds = Subscription::pluck('id')->toArray();

        if (empty($userIds) || empty($subscriptionIds)) {
            $this->command->warn('No users or subscriptions found. Please seed them first.');
            return;
        }

        $statuses = ['pending', 'active', 'cancelled', 'failed'];

        for ($i = 0; $i < 20; $i++) {

            $startDate = Carbon::now()->subDays(rand(0, 60));
            $durationMonths = rand(1, 6); // subscription duration in months
            $endDate = $startDate->copy()->addMonths($durationMonths);
            $isExpired = $endDate->isPast() ? true : false;

            SubscriptionRecord::create([
                'user_id' => $userIds[array_rand($userIds)],
                'subscription_id' => $subscriptionIds[array_rand($subscriptionIds)],
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'amount' => fake()->randomFloat(2, 1000, 10000),
                'currency' => fake()->randomElement(['NGN', 'USD', 'GHS']),
                'status' => $statuses[array_rand($statuses)],
                'reference' => now()->format('YmdHis') . '_' . Str::uuid(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_expired' => $isExpired,
                'created_at' => $startDate,
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
