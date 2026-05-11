<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Run Migration
    |--------------------------------------------------------------------------
    |
    | Creates the payments table used for:
    | - customer payments
    | - company payouts
    | - commissions
    | - invoice tracking
    | - future Stripe/MIPS integration
    |
    */

    public function up(): void
    {
        
      
            /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        |
        | Prevents duplicate table error if the payments table already exists.
        |
        */

        if (Schema::hasTable('payments')) {
            return;
        }
    
    
        Schema::create('payments', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('rental_company_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Payment Amounts
            |--------------------------------------------------------------------------
            */

            $table->decimal('amount', 12, 2);

            $table->decimal('commission_amount', 12, 2)
                ->default(0);

            $table->decimal('company_amount', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Payment Details
            |--------------------------------------------------------------------------
            */

            $table->string('payment_method')
                ->nullable();
                // cash, juice, bank_transfer, stripe, card

            $table->string('payment_gateway')
                ->nullable();
                // stripe, mips, manual, etc

            $table->string('transaction_reference')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('pending');
                // pending, paid, failed, refunded

            /*
            |--------------------------------------------------------------------------
            | Additional Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Payment Timestamp
            |--------------------------------------------------------------------------
            */

            $table->timestamp('paid_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Laravel Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Reverse Migration
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};