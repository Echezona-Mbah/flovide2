<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $provider = [
            'name' => 'Swappr',
            'is_enabled' => true,
            'priority' => 1,
            'updated_at' => now(),
        ];

        $providerId = DB::table('payment_providers')->where('key', 'swappr')->value('id');

        if ($providerId) {
            DB::table('payment_providers')->where('id', $providerId)->update($provider);
        } else {
            $providerId = DB::table('payment_providers')->insertGetId([
                'key' => 'swappr',
                ...$provider,
                'created_at' => now(),
            ]);
        }

        foreach (['CAD', 'NGN'] as $currency) {
            $mapping = DB::table('payment_provider_currencies')
                ->where('payment_provider_id', $providerId)
                ->where('currency', $currency)
                ->whereNull('transfer_method')
                ->first();

            if ($mapping) {
                DB::table('payment_provider_currencies')->where('id', $mapping->id)->update([
                    'is_enabled' => true,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('payment_provider_currencies')->insert([
                    'payment_provider_id' => $providerId,
                    'currency' => $currency,
                    'transfer_method' => null,
                    'is_enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Keep the additive provider configuration intact when rolling back code.
    }
};
