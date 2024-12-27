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
        Schema::create('tx_ride_orders', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->foreignId('type_id')
                ->default(null)
                ->nullable()
                ->constrained('tx_ride_order_types', 'id')->nullOnDelete();
            $table->foreignId('class_id')
                ->default(null)
                ->nullable()
                ->constrained('tx_car_classes', 'id')->nullOnDelete();
            $table->foreignId('user_id')
                ->default(null)
                ->nullable()
                ->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('driver_id')
                ->default(null)
                ->nullable()
                ->constrained('users', 'id')->nullOnDelete();
            $table->integer('user_cost');
            $table->integer('people')->default(1);
            $table->string('user_comment')->nullable();
            $table->string('driver_comment')->nullable();
            $table->string('point_a')->nullable();
            $table->string('point_b')->nullable();
            $table->string('geo_a')->nullable();
            $table->string('geo_b')->nullable();
            $table->foreignId('city_a_id')
                ->default(null)
                ->nullable()
                ->constrained('tx_cities', 'id')->nullOnDelete();
            $table->foreignId('city_b_id')
                ->default(null)
                ->nullable()
                ->constrained('tx_cities', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_ride_orders');
    }
};
