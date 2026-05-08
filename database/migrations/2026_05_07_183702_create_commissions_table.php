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
       Schema::create('commissions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
        $table->foreignId('rental_company_id')->constrained()->cascadeOnDelete();

        $table->decimal('rental_amount', 10, 2);
        $table->decimal('commission_rate', 5, 2);
        $table->decimal('commission_amount', 10, 2);
        $table->decimal('owner_payout_amount', 10, 2);

        $table->enum('status', ['pending', 'earned', 'paid_out', 'cancelled'])
              ->default('pending');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
