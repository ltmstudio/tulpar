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
        Schema::create('tx_translations', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('key');
            $table->foreignId('tx_lang_id')->constrained()->onDelete('cascade');
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_translations');
    }
};
