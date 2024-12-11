<?php

namespace Database\Seeders;

use App\Models\TxCarClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TxCarClass::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'Эконом',
            'cost' => 100,
            'priority' => 1,
        ]);
        TxCarClass::updateOrCreate([
            'id' => 2
        ], [
            'name' => 'Комфорт',
            'cost' => 150,
            'priority' => 2,
        ]);
        TxCarClass::updateOrCreate([
            'id' => 3
        ], [
            'name' => 'Бизнес',
            'cost' => 200,
            'priority' => 3,
        ]);
        TxCarClass::updateOrCreate([
            'id' => 4
        ], [
            'name' => 'VIP',
            'cost' => 250,
            'priority' => 4,
        ]);
    }
}
