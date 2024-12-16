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
        Schema::create('tx_sms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->nullable(false)
            ->constrained(table: 'users', column: 'id')
            ->onDelete('cascade');
            $table->string('sms');
            $table->string('salt');
            $table->boolean('active');
            $table->dateTime('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_sms');
    }
};
