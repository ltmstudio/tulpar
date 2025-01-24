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
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tx_ride_orders', function (Blueprint $table) {
            
            $table->foreignId('driver_id')
                ->after('user_id')
                ->default(null)
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();
        });
    }
};
