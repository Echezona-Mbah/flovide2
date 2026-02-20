<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('beneficias', function (Blueprint $table) {
            if (!Schema::hasColumn('beneficias', 'phone')) {
                $table->string('phone')->nullable()->after('personal_id');
            }

            if (!Schema::hasColumn('beneficias', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('beneficias', 'unique_reference')) {
                $table->string('unique_reference')->nullable()->after('personal_id');
            }

            if (!Schema::hasColumn('beneficias', 'customer_reference')) {
                $table->string('customer_reference')->nullable()->after('unique_reference');
            }

            if (!Schema::hasColumn('beneficias', 'beneficiary_name')) {
                $table->string('beneficiary_name')->nullable()->after('type');
            }

            if (!Schema::hasColumn('beneficias', 'first_names')) {
                $table->string('first_names')->nullable()->after('beneficiary_name');
            }

            if (!Schema::hasColumn('beneficias', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_names');
            }

            if (!Schema::hasColumn('beneficias', 'account_id')) {
                $table->string('account_id')->nullable()->after('account_number');
            }

            if (!Schema::hasColumn('beneficias', 'swift_bic')) {
                $table->string('swift_bic')->nullable()->after('sort_code');
            }

            if (!Schema::hasColumn('beneficias', 'address_line1')) {
                $table->string('address_line1')->nullable()->after('country');
            }

            if (!Schema::hasColumn('beneficias', 'address_line2')) {
                $table->string('address_line2')->nullable()->after('address_line1');
            }

            if (!Schema::hasColumn('beneficias', 'building_name')) {
                $table->string('building_name')->nullable()->after('address_line2');
            }

            if (!Schema::hasColumn('beneficias', 'city')) {
                $table->string('city')->nullable()->after('building_name');
            }

            if (!Schema::hasColumn('beneficias', 'state')) {
                $table->string('state')->nullable()->after('city');
            }

            if (!Schema::hasColumn('beneficias', 'postcode')) {
                $table->string('postcode')->nullable()->after('state');
            }
        });
    }

    public function down()
    {
        Schema::table('beneficias', function (Blueprint $table) {
            $columns = [
                'phone',
                'email',
                'unique_reference',
                'customer_reference',
                'beneficiary_name',
                'first_names',
                'last_name',
                'account_id',
                'swift_bic',
                'address_line1',
                'address_line2',
                'building_name',
                'city',
                'state',
                'postcode',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('beneficias', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
