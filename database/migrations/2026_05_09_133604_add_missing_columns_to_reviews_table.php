<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {

            if (!Schema::hasColumn('reviews', 'vehicle_id')) {
                $table->foreignId('vehicle_id')
                    ->nullable()
                    ->constrained()
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('reviews', 'customer_id')) {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('reviews', 'booking_id')) {
                $table->foreignId('booking_id')
                    ->nullable()
                    ->constrained()
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('reviews', 'rating')) {
                $table->integer('rating')->nullable();
            }

            if (!Schema::hasColumn('reviews', 'comment')) {
                $table->text('comment')->nullable();
            }

        });
    }

    public function down(): void
    {
        //
    }
};