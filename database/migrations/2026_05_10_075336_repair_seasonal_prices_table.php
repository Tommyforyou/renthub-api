<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seasonal_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('seasonal_prices', 'vehicle_id')) {
                $table->foreignId('vehicle_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('vehicles')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('seasonal_prices', 'start_date')) {
                $table->date('start_date')->nullable()->after('vehicle_id');
            }

            if (!Schema::hasColumn('seasonal_prices', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }

            if (!Schema::hasColumn('seasonal_prices', 'price_multiplier')) {
                $table->decimal('price_multiplier', 5, 2)->default(1.00)->after('end_date');
            }

            if (!Schema::hasColumn('seasonal_prices', 'label')) {
                $table->string('label')->nullable()->after('price_multiplier');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seasonal_prices', function (Blueprint $table) {
            foreach (['label', 'price_multiplier', 'end_date', 'start_date'] as $column) {
                if (Schema::hasColumn('seasonal_prices', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('seasonal_prices', 'vehicle_id')) {
                $table->dropConstrainedForeignId('vehicle_id');
            }
        });
    }
};