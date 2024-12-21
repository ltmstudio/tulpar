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
        Schema::create('tx_shift_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')
                ->constrained(table: 'tx_driver_profiles', column: 'id')
                ->onDelete('cascade');
            $table->foreignId('class_id')
                ->default(null)
                ->nullable()
                ->constrained(table: 'tx_car_classes', column: 'id')
                ->nullOnDelete();
            $table->integer('hours');
            $table->string('hours_state')->nullable();
            $table->string('level_name')->nullable();
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_shift_orders');
    }
};
