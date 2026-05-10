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
        });
    }

    public function down(): void
    {
        Schema::table('seasonal_prices', function (Blueprint $table) {
            if (Schema::hasColumn('seasonal_prices', 'vehicle_id')) {
                $table->dropConstrainedForeignId('vehicle_id');
            }
        });
    }
};