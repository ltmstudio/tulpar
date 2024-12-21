<?php

namespace Database\Seeders;

use App\Models\TxShift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TxShift::updateOrCreate(
            [
                'id' => 1
            ],
            [
                'hours' => 1,
                'state' => 'час'
            ]
        );
        TxShift::updateOrCreate(
            [
                'id' => 2
            ],
            [
                'hours' => 3,
                'state' => 'часа'
            ]
        );
        TxShift::updateOrCreate(
            [
                'id' => 3
            ],
            [
                'hours' => 6,
                'state' => 'часов'
            ]
        );
        TxShift::updateOrCreate(
            [
                'id' => 4
            ],
            [
                'hours' => 12,
                'state' => 'часов'
            ]
        );
        TxShift::updateOrCreate(
            [
                'id' => 5
            ],
            [
                'hours' => 24,
                'state' => 'часа'
            ]
        );
    }
}
