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
        Schema::table('tx_ride_orders', function (Blueprint $table) {
            $table->boolean('is_delivery')->after('city_b_id')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tx_ride_orders', function (Blueprint $table) {
            $table->dropColumn('is_delivery');
        });
    }
};
