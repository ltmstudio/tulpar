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
        Schema::create('tx_driver_balance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')
                ->default(null)
                ->nullable()
                ->constrained(table: 'tx_driver_profiles', column: 'id')
                ->nullOnDelete();
            $table->double('operation_value')->default(0.0);
            $table->double('result_balance')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_driver_balance_logs');
    }
};
