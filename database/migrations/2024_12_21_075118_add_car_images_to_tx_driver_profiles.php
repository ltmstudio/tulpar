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
        Schema::table('tx_driver_profiles', function (Blueprint $table) {
            $table->string('car_image_1')->nullable();
            $table->string('car_image_2')->nullable();
            $table->string('car_image_3')->nullable();
            $table->string('car_image_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tx_driver_profiles', function (Blueprint $table) {
            //
        });
    }
};
