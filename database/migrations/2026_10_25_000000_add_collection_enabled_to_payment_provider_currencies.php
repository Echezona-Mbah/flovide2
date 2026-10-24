<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('payment_provider_currencies', 'collection_enabled')) {
            Schema::table('payment_provider_currencies', function (Blueprint $table) {
                $table->boolean('collection_enabled')->default(false)->after('is_enabled');
            });
        }

        $blaaizProviderId = DB::table('payment_providers')
            ->where('key', 'blaaiz_interac')
            ->value('id');

        if ($blaaizProviderId) {
            DB::table('payment_provider_currencies')
                ->where('payment_provider_id', $blaaizProviderId)
                ->where('currency', 'CAD')
                ->update(['collection_enabled' => true]);
        }
    }

    public function down(): void
    {
        // Keep provider configuration and collection enablement data intact.
    }
};
