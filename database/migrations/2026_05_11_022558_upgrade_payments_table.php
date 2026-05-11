<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Upgrade Payments Table
    |--------------------------------------------------------------------------
    */

    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Additional Relationships
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('payments', 'customer_id')) {

                $table->foreignId('customer_id')
                    ->nullable()
                    ->after('booking_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('payments', 'rental_company_id')) {

                $table->foreignId('rental_company_id')
                    ->nullable()
                    ->after('customer_id')
                    ->constrained()
                    ->nullOnDelete();
            }

            /*
            |--------------------------------------------------------------------------
            | Marketplace Accounting
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('payments', 'commission_amount')) {

                $table->decimal('commission_amount', 12, 2)
                    ->default(0)
                    ->after('amount');
            }

            if (!Schema::hasColumn('payments', 'company_amount')) {

                $table->decimal('company_amount', 12, 2)
                    ->default(0)
                    ->after('commission_amount');
            }

            /*
            |--------------------------------------------------------------------------
            | Payment Gateway Information
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('payments', 'payment_gateway')) {

                $table->string('payment_gateway')
                    ->nullable()
                    ->after('payment_method');
            }

            /*
            |--------------------------------------------------------------------------
            | Internal Notes
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('payments', 'notes')) {

                $table->text('notes')
                    ->nullable()
                    ->after('status');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Reverse Migration
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $columns = [
                'customer_id',
                'rental_company_id',
                'commission_amount',
                'company_amount',
                'payment_gateway',
                'notes',
            ];

            foreach ($columns as $column) {

                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
