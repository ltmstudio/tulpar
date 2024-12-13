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
        Schema::create('tx_catalog_car_models', function (Blueprint $table) {
            $table->string('id')->primary()->unique();
            $table->string('car_id');
            $table->foreign('car_id')->references('id')->on('tx_catalog_cars')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('cyrillic-name')->nullable();
            $table->integer('year-from')->nullable();
            $table->integer('year-to')->nullable();
            $table->string('class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_catalog_car_models');
    }
};
