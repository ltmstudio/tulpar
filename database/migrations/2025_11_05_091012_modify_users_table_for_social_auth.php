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
        Schema::table('users', function (Blueprint $table) {
            // Делаем email nullable
            $table->string('email')->nullable()->change();
            // Добавляем новые столбцы
            $table->string('apple_id')->nullable()->unique()->after('id');
            $table->string('google_id')->nullable()->unique()->after('apple_id');
            $table->enum('auth_type', ['phone', 'apple', 'google'])->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Заполняем NULL-значения в email перед возвратом к not nullable
        \Illuminate\Support\Facades\DB::table('users')->whereNull('email')->update(['email' => 'default@example.com']);
        Schema::table('users', function (Blueprint $table) {
            // Возвращаем email к not nullable (предполагается, что данные не содержат NULL)
            $table->string('email')->nullable(false)->change();
            // Удаляем новые столбцы
            $table->dropColumn(['apple_id', 'google_id', 'auth_type']);
        });
    }
};
