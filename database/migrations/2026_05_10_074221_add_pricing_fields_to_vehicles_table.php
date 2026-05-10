<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'daily_price')) {
                $table->decimal('daily_price', 10, 2)->default(0)->after('price_per_day');
            }

            if (!Schema::hasColumn('vehicles', 'weekly_discount')) {
                $table->decimal('weekly_discount', 5, 2)->default(0)->after('daily_price');
            }

            if (!Schema::hasColumn('vehicles', 'monthly_discount')) {
                $table->decimal('monthly_discount', 5, 2)->default(0)->after('weekly_discount');
            }

            if (!Schema::hasColumn('vehicles', 'weekend_multiplier')) {
                $table->decimal('weekend_multiplier', 5, 2)->default(1.00)->after('monthly_discount');
            }

            if (!Schema::hasColumn('vehicles', 'security_deposit')) {
                $table->decimal('security_deposit', 10, 2)->default(0)->after('weekend_multiplier');
            }

            if (!Schema::hasColumn('vehicles', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0)->after('security_deposit');
            }

            if (!Schema::hasColumn('vehicles', 'minimum_days')) {
                $table->unsignedInteger('minimum_days')->default(1)->after('delivery_fee');
            }

            if (!Schema::hasColumn('vehicles', 'maximum_days')) {
                $table->unsignedInteger('maximum_days')->nullable()->after('minimum_days');
            }

            if (!Schema::hasColumn('vehicles', 'instant_booking')) {
                $table->boolean('instant_booking')->default(true)->after('maximum_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $columns = [
                'daily_price',
                'weekly_discount',
                'monthly_discount',
                'weekend_multiplier',
                'security_deposit',
                'delivery_fee',
                'minimum_days',
                'maximum_days',
                'instant_booking',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('vehicles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
