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
        Schema::create('tx_system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('txkey');
            $table->string('string_val')->nullable(true);
            $table->integer('int_val')->nullable(true);
            $table->double('double_val')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_system_settings');
    }
};
