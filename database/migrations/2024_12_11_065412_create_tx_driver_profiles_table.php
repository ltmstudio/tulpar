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
        Schema::create('tx_driver_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('lastname')->nullable(true);
            $table->string('avatar')->nullable(true);
            $table->string('car_name')->nullable(false);
            $table->string('car_number')->nullable(false);
            $table->integer('people')->default(3);
            $table->foreignId('class_id')
                ->default(null)
                ->nullable()
                ->constrained(table: 'tx_car_classes', column: 'id')
                ->nullOnDelete();
            $table->double('balance')->default(0);
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_driver_profiles');
    }
};
