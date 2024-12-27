<?php

namespace Database\Seeders;

use App\Models\TxRideOrderType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RideOrderTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TxRideOrderType::updateOrCreate(['id' => 1], ['name' => "Поездка"]);
        TxRideOrderType::updateOrCreate(['id' => 2], ['name' => "Межгород"]);
        TxRideOrderType::updateOrCreate(['id' => 3], ['name' => "Межгород груз"]);
    }
}
