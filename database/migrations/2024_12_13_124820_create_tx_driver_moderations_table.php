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
        Schema::create('tx_driver_moderations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->timestamp('birthdate')->nullable();
            $table->string('car_id')->nullable();
            $table->foreign('car_id')->references('id')->on('tx_catalog_cars')->onDelete('set null');
            $table->string('car_model_id')->nullable();
            $table->foreign('car_model_id')->references('id')->on('tx_catalog_car_models')->onDelete('set null');
            $table->string('car_vin')->nullable();
            $table->string('car_year')->nullable();
            $table->string('car_gos_number')->nullable();
            $table->string('car_image_1')->nullable();
            $table->string('car_image_2')->nullable();
            $table->string('car_image_3')->nullable();
            $table->string('car_image_4')->nullable();
            $table->string('driver_license_number')->nullable();
            $table->string('driver_license_front')->nullable();
            $table->string('driver_license_back')->nullable();
            $table->timestamp('driver_license_date')->nullable();
            $table->string('ts_passport_front')->nullable();
            $table->string('ts_passport_back')->nullable();
            $table->string('status')->default('preparation'); // preparation, moderation, approved, rejected
            $table->string('reject_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_driver_moderations');
    }
};
