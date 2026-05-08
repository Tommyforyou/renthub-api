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
        Schema::create('rental_companies', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->string('company_name');
        $table->string('brn')->nullable();
        $table->string('vat_number')->nullable();
        $table->string('contact_person')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->text('address')->nullable();

        $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])
              ->default('pending');

        $table->decimal('commission_rate', 5, 2)->default(10.00);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_companies');
    }
};
