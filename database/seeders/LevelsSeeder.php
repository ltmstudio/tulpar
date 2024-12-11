<?php

namespace Database\Seeders;

use App\Models\TxLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TxLevel::updateOrCreate(
            [
                'id' => 1
            ], [
            'name' => 'Standart',
            'count' => 0,
            'color' => '00A1C2'
        ]);
        TxLevel::updateOrCreate(
            [
                'id' => 2
            ], [
            'name' => 'Silver',
            'count' => 3,
            'color' => 'A6A6A6'
        ]);
        TxLevel::updateOrCreate(
            [
                'id' => 3
            ], [
            'name' => 'Gold',
            'count' => 5,
            'color' => 'D3AE36'
        ]);
    }
}
