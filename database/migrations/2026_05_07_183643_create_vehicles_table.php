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
       Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rental_company_id')->constrained()->cascadeOnDelete();

        $table->string('make');
        $table->string('model');
        $table->string('registration_number')->unique();
        $table->integer('year')->nullable();

        $table->enum('vehicle_type', ['economy', 'compact', 'suv', 'luxury', 'van', 'mpv']);
        $table->enum('transmission', ['manual', 'automatic']);
        $table->enum('fuel_type', ['petrol', 'diesel', 'hybrid', 'electric']);

        $table->integer('seats')->default(5);
        $table->decimal('daily_rate', 10, 2);
        $table->decimal('security_deposit', 10, 2)->default(0);

        $table->string('location')->nullable();

        $table->enum('status', ['draft', 'active', 'inactive', 'suspended'])
              ->default('draft');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
