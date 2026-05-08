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
         Schema::create('bookings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
        $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();

        $table->date('start_date');
        $table->date('end_date');

        $table->string('pickup_location')->nullable();
        $table->string('return_location')->nullable();

        $table->integer('total_days');
        $table->decimal('daily_rate', 10, 2);
        $table->decimal('subtotal', 10, 2);
        $table->decimal('commission_amount', 10, 2)->default(0);
        $table->decimal('owner_payout_amount', 10, 2)->default(0);
        $table->decimal('total_amount', 10, 2);

        $table->enum('status', [
            'pending',
            'awaiting_payment',
            'confirmed',
            'in_progress',
            'completed',
            'cancelled',
            'refunded',
            'disputed'
        ])->default('pending');

        $table->timestamps();

        $table->index(['vehicle_id', 'start_date', 'end_date']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
