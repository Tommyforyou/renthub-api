<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_availabilities', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_availabilities', 'vehicle_id')) {
                $table->foreignId('vehicle_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('vehicles')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('vehicle_availabilities', 'blocked_from')) {
                $table->date('blocked_from')->nullable()->after('vehicle_id');
            }

            if (!Schema::hasColumn('vehicle_availabilities', 'blocked_until')) {
                $table->date('blocked_until')->nullable()->after('blocked_from');
            }

            if (!Schema::hasColumn('vehicle_availabilities', 'reason')) {
                $table->string('reason')->nullable()->after('blocked_until');
            }

            if (!Schema::hasColumn('vehicle_availabilities', 'type')) {
                $table->string('type')->default('manual')->after('reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_availabilities', function (Blueprint $table) {
            foreach (['type', 'reason', 'blocked_until', 'blocked_from'] as $column) {
                if (Schema::hasColumn('vehicle_availabilities', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('vehicle_availabilities', 'vehicle_id')) {
                $table->dropConstrainedForeignId('vehicle_id');
            }
        });
    }
};