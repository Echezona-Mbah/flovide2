<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('virtual_cards', function (Blueprint $table) {
                        $table->unsignedBigInteger('personal_id')->nullable()->after('user_id');

            // Add foreign key to personals table
            $table->foreign('personal_id')
                  ->references('id')
                  ->on('personals')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_cards', function (Blueprint $table) {
                        $table->dropForeign(['personal_id']);
            $table->dropColumn('personal_id');
        });
    }
};
