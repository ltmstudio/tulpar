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
        Schema::table('tx_driver_profiles', function (Blueprint $table) {
            $table->boolean('delivery')->after('class_id')->default(false);
            $table->boolean('cargo')->after('delivery')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tx_driver_profiles', function (Blueprint $table) {
            //
        });
    }
};
