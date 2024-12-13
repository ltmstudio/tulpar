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
        Schema::create('tx_catalog_cars', function (Blueprint $table) {
            $table->string('id')->primary()->unique();
            $table->string('name');
            $table->string('cyrillic-name')->nullable();
            $table->boolean('popular')->default(true);
            $table->string('country')->nullable();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tx_catalog_cars');
    }
};
