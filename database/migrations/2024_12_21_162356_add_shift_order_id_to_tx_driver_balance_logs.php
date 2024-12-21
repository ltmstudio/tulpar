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
        Schema::table('tx_driver_balance_logs', function (Blueprint $table) {
            $table->foreignId('shift_order_id')
                ->default(null)
                ->nullable()
                ->constrained(table: 'tx_shift_orders', column: 'id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tx_driver_balance_logs', function (Blueprint $table) {
            $table->dropForeign(['shift_order_id']);
            $table->dropColumn('shift_order_id');
        });
    }
};
