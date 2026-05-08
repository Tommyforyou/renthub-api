<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'title')) {
                $table->string('title')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'brand')) {
                $table->string('brand')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'model')) {
                $table->string('model')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'year')) {
                $table->integer('year')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'transmission')) {
                $table->string('transmission')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'fuel_type')) {
                $table->string('fuel_type')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'seats')) {
                $table->integer('seats')->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'price_per_day')) {
                $table->decimal('price_per_day', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('vehicles', 'available')) {
                $table->boolean('available')->default(true);
            }

            if (!Schema::hasColumn('vehicles', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'brand',
                'model',
                'year',
                'transmission',
                'fuel_type',
                'seats',
                'price_per_day',
                'available',
                'description',
            ]);
        });
    }
};