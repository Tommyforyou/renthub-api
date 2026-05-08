<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->string('payment_status')
                ->default('pending');

            $table->decimal('deposit_amount', 10, 2)
                ->nullable();

            $table->decimal('remaining_balance', 10, 2)
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'payment_status',
                'deposit_amount',
                'remaining_balance'
            ]);

        });
    }
};
