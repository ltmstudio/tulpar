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
        Schema::create('tx_shift_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tx_shift_id')->constrained('tx_shifts')->onDelete('cascade');
            $table->foreignId('tx_level_id')->constrained('tx_levels')->onDelete('cascade');
            $table->foreignId('tx_car_class_id')->constrained('tx_car_classes')->onDelete('cascade');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_shift_prices');
    }
};
